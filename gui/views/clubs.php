<?php
/* DEBUG:*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// php settings
ini_set('file_uploads', 1);

// import settings
require_once 'include/db_config.php';
require_once 'include/app_config.php';
require_once 'include/template_engine.php';

// create connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT)
or die("Connection to db failed: " . $db->connect_error);

// session settings
session_start();

/* DEBUG: */
try {
  echo "<script>console.log('|=========== REQUEST ===========|')</script>";
  echo "<script>console.log(" . json_encode($_REQUEST) . ")</script>";
  echo "<script>console.log('|=========== POST ===========|')</script>";
  echo "<script>console.log(" . json_encode($_POST) . ")</script>";
  echo "<script>console.log('|=========== FILES ===========|')</script>";
  echo "<script>console.log(" . json_encode($_FILES) . ")</script>";
}
catch (Exception $e) {
  echo "<script>console.error('[ERR]: " . print_r($e, true) . "')</script>";
}

/* TODO: finish form handling */
// form handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($_POST['action'] === 'add') {
    /*
    TODO: this is very inefficient as more files are uploaded
    we should run this at startup and then track the state across the server
    */
    /* get the next available upload name (incremental) */
    $image_filenames = glob(UPLOADS_FILE_PATH . "/images/*");
    $next_image_num = max(preg_replace("|[^0-9]|", "", $image_filenames)) + 1;
    $upload_name = $next_image_num . "'" . pathinfo($_FILES['club_picture']['name'])['extension'];

    $res = $db->query("INSERT INTO Club VALUES (" .
        "NULL," .
        "'" . $_POST['club_name'] . "'" . "," .
        "'" . $_POST['club_president'] . "'" . "," .
        "'" . $_POST['club_section'] . "'" . "," .
        "'" . $_POST['club_description'] . "'" . "," .
        "'" . $_POST['club_poc'] . "'" . "," .
        "'" . $_POST['club_poc_email'] . "'" . "," .
        "'" . $_POST['club_meeting_days'] . "'" . "," .
        "'" . $_POST['club_meeting_time'] . "'" . "," .
        "'" . $_POST['club_meeting_loc'] . "'" . "," .
        "'" . $upload_name . "')"
    );

    if ($res === TRUE) {
      echo "<script>console.log('[OK]: Record created successfully')</script>";
    }
    else {
      echo "<script>console.error('[ERR]: " . $db->error . "')</script>";
    }

    //header("Location: /views/clubs.php");
    //exit;
  }
  elseif ($_POST['action'] === 'edit') {
    if (isset($_POST['rowid'])) {

      if ($res === TRUE) {
        echo "<script>console.log('[OK]: Record updated successfully')</script>";
      }
      else {
        echo "<script>console.error('[ERR]: " . $db->error . "')</script>";
      }

      $res->free();

      header("Location: views/clubs.php");
      exit;
    }
  }
  elseif ($_POST['action'] === 'delete') {
    if (isset($_POST['rowid'])) {

      if ($res === TRUE) {
        echo "<script>console.log('[OK]: Record deleted successfully')</script>";
      }
      else {
        echo "<script>console.error('[ERR]: " . $db->error . "')</script>";
      }

      $res->free();

      header("Location: views/clubs.php");
      exit;
    }
  }

}
?>

  <html lang="en">
  <head>

    <!-- title and general meta -->
    <title>Kettering Student Clubs</title>
    <meta charset="utf-8">
    <meta name="description" content="Organizations and Clubs organized by Kettering University students.">
    <meta name="keywords" content="organizations,clubs,student,kettering,groups,university">

    <!-- css libraries and styles -->
    <link rel="stylesheet" href="/static/css/bootstrap.css">
    <link rel="stylesheet" href="/static/css/bootstrap-theme.css">
    <link rel="stylesheet" href="/static/css/fa.css">
    <link rel="stylesheet" href="/static/css/datatables.min.css">
    <link rel="stylesheet" href="/static/css/highlight/github.css">
    <link rel="stylesheet" href="/static/css/main.css">

    <!-- favicon icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/static/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon/favicon-16x16.png">
    <link rel="manifest" href="/static/favicon/site.webmanifest">
    <link rel="mask-icon" href="/static/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/static/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/static/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!-- CURRENT-PAGE css -->
    <link rel="stylesheet" href="/static/css/clubs.css">

  </head>

  <body>

  <div class="container">

    <!-- header content -->
    <header>
      <nav class="nav-bar navbar-inverse wrapper-horizontal" role="navigation">
        <div>
          <a class="navbar-brand" href="#">
            <img src="/static/images/bulldog.svg" alt="bulldog">
          </a>
        </div>

        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown movable">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="caret"></span>
              <?php if (empty($_SESSION["id"])) {
                echo "Guest";
              }
              else {
                echo $_SESSION["name"];
              } ?>
            </a>
            <ul class="dropdown-menu" role="menu">
              <!-- TODO: allow non admin users to edit their profile (parts of it) -->
              <li><a href="#"><span class="fa fa-user"></span>My Profile</a></li>
              <!-- TODO: add display settings page -->
              <!-- <li><a href="#"><span class="fa fa-gear"></span>Settings</a></li> -->
              <li class="divider"></li>
              <li>
                <?php if (!empty($_SESSION["id"])): ?>
                  <a href="/views/logout.php"><span class="fa fa-power-off"></span>Logout</a>
                <?php else: ?>
                  <a href="/views/login.php"><span class="fa fa-power-on"></span>Login</a>
                <?php endif; ?>
              </li>
            </ul>
          </li>
        </ul>
      </nav>

      <div class="top-bar hidden centered">
        <div class="message-bar"></div>
      </div>
    </header>

    <!-- CURRENT-PAGE content -->
    <section>

      <div class="wrapper-vertical">

        <!-- table title -->
        <div class="wrapper-horizontal edge-centered">
          <h4 style="Color:white">Kettering Student Organizations</h4>
        </div>

      </div>

      <br>
      <div class="clearfix"></div>

      <!-- table data -->
      <div class="table-responsive">
        <table id="clubsTable" class="table table-striped table-centered table-hover">

          <thead>
          <tr class='element-row'>
            <!-- dont need to show details for summary page, some data is hidden -->
            <th data-field="club_id">
              <!-- add row button only for admin -->
              <?PHP
              if (!empty($_SESSION["id"]) && $_SESSION["id"] == 1) {
                echo "<button id='open-Add' class='btn btn-success btn-md' title='Add Row' data-toggle='modal' data-target='#add'>Add </button>";
              }
              ?>
            </th>
            <th data-field="club_name">Club Name</th>
            <th class="hidden" data-field="club_president"></th>
            <th class="hidden" data-field="club_section"></th>
            <th class="hidden" data-field="club_description"></th>
            <th data-field="club_poc">Point of Contact</th>
            <th data-field="club_poc_email">POC Email</th>
            <th data-field="club_meeting_days">Meeting Days</th>
            <th data-field="club_meeting_time">Meeting Time</th>
            <th data-field="club_meeting_loc">Meeting Location</th>
            <th class="hidden" data-field="club_picture"></th>

            <?PHP
            if (!empty($_SESSION["id"])) {
              /* only show edit / delete column if auth permissions high enough */
              $res = $db->query("SELECT president FROM Club WHERE president=" . $_SESSION["id"] . " LIMIT 1");
              if (mysqli_fetch_row($res) || $_SESSION["id"] == 1) {
                echo "<th>Edit</th>";
              }
              if ($_SESSION["id"] == 1) {
                echo "<th>Delete</th>";
              }
              $res->free();
            }
            ?>
          </tr>
          </thead>
          <tbody>

          <?php
          $res = $db->query("SELECT c.id AS club_id, c.name AS club_name, u.id as president_id, c.section as club_section, 
            c.description as club_description, u.name AS club_poc, u.email AS club_poc_email, norm_meeting_days AS club_meeting_days, 
            norm_meeting_time AS club_meeting_time, norm_meeting_loc AS club_meeting_loc, c.picture as club_picture
            FROM Club c LEFT JOIN  User u on c.president = u.id");
          if ($res->num_rows == 0) {
            return;
          }
          while ($row = $res->fetch_assoc()) {
            ?>

            <tr class='element-row'>
              <td class='club_id'><?php echo $row['club_id'] ?></td>
              <td class='club_name'><?php echo "<a href=club-details.php?id=" . $row['club_id'] . ">" . $row['club_name'] . "</a>"; ?></td>
              <td class="club_president hidden"><?php echo $row['president_id'] ?></td>
              <td class="club_section hidden"><?php echo $row['club_section'] ?></td>
              <td class="club_description hidden"><?php echo $row['club_description'] ?></td>
              <td class='club_poc'><?php echo $row['club_poc'] ?></td>
              <td class='club_poc_email'><?php echo $row['club_poc_email'] ?></td>
              <td class='club_meeting_days'><?php echo $row['club_meeting_days'] ?></td>
              <td class='club_meeting_time'><?php echo $row['club_meeting_time'] ?></td>
              <td class='club_meeting_loc'><?php echo $row['club_meeting_loc'] ?></td>
              <th class="club_picture hidden"><?php echo $row['club_picture'] ?></th>
              <?PHP
              if (!empty($_SESSION["id"])) {

                if ($_SESSION["id"] == $row['president_id'] || $_SESSION["id"] == 1) {
                  echo "<td>
                    <button id='open-Update' class='open-Update btn btn-primary btn-xs' title='Edit Row'
                            data-toggle='modal' data-target='#edit'>
                      <span class='icon-edit'></span>
                    </button>
                  </td>";
                }
                else {
                  echo "<td></td>";
                }

                if ($_SESSION["id"] == 1) {
                  echo "<td>
                    <button id='open-Delete' class='open-Delete btn btn-danger btn-xs' title='Delete Row'
                            data-toggle='modal' data-target='#delete'>
                      <span class='icon-delete'></span>
                    </button>
                  </td>";
                }
                else {
                  echo "<td></td>";
                }

              }
              else {
                echo "<td></td><td></td>";
              }

              ?>
            </tr>
            <?php
          }
          $res->free();
          ?>
          </tbody>
        </table>
      </div>

    </section>

  </div>

  <!-- footer content -->
  <!-- not used yet -->
  <footer>
    <div class="bottom-bar hidden centered">
      <div></div>
    </div>
  </footer>

  <!-- interactive modals -->
  <?php
  $template = new Template('templates/modals.php');

  $pres_option_tags = '';
  $res = $db->query("SELECT t2.id AS user_id,t2.name AS president_name FROM Officers t1, User t2 WHERE t1.user_id = t2.id AND t1.position = 'president'");
  if ($res->num_rows !== 0) {
    while ($row = $res->fetch_assoc()) {
      $pres_option_tags .= '<option value="' . $row['user_id'] . '">' . $row['president_name'] . '</option>\n';
    }
  }
  $res->free();

  $template->set('add_form_body', '
    <div class="form-group">
      <input class="form-control club_name" type="text" name="club_name" placeholder="Club Name" autofocus>
    </div>
    
    <div class="form-group">
      <label>Club President:
        <select name="club_president">' .
      $pres_option_tags .
      '</select>
      </label>
    </div>
    
    <div class="form-group">
      <label>Section:
        <select name="club_section">
          <option value="A">A</option>
          <option value="B">B</option>
        </select>
      </label>
    </div>

    <div class="form-group">
      <input class="form-control club_description" type="text" name="club_description" placeholder="Club Description">
    </div>
    
    <div class="form-group">
      <input class="form-control club_poc" type="text" name="club_poc" placeholder="Point of Contact">
    </div>
    
    <div class="form-group">
      <input class="form-control club_poc_email" type="email" name="club_poc_email" placeholder="Point of Contact Email">
    </div>
    
    <div class="form-group">
      <div class="club_meetings">
        <input type="hidden" class="club_meeting_days" name="club_meeting_days">
        <label>Meeting Days:
          <label class="checkbox-inline">
            <input type="checkbox">M
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">T
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">W
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">Th
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">F
          </label>
        </label>
      </div>
    </div>
    
    <div class="form-group">
      <input class="form-control club_meeting_time" type="time" name="club_meeting_time" placeholder="Meeting Time">
    </div>
    
    <div class="form-group">
      <input class="form-control club_meeting_loc" type="text" name="club_meeting_loc" placeholder="Meeting Location">
    </div>
    
    <div class="form-group">
      <input class="form-control club_picture" type="file" name="club_picture">
      <img class="club_pic_preview img-thumbnail hidden" src="#" alt="preview">
    </div>
  ');

  $template->set('edit_form_body', '
    <div class="form-group">
      <input class="form-control club_name" type="text" name="club_name" placeholder="Club Name" autofocus>
    </div>
    
    <div class="form-group">
      <label>Club President:
        <select name="club_president">' .
      $pres_option_tags .
      '</select>
      </label>
    </div>
    
    <div class="form-group">
      <label>Section:
        <select name="club_section">
          <option value="A">A</option>
          <option value="B">B</option>
        </select>
      </label>
    </div>

    <div class="form-group">
      <input class="form-control club_description" type="text" name="club_description" placeholder="Club Description">
    </div>
    
    <div class="form-group">
      <input class="form-control club_poc" type="text" name="club_poc" placeholder="Point of Contact">
    </div>
    
    <div class="form-group">
      <input class="form-control club_poc_email" type="email" name="club_poc_email" placeholder="Point of Contact Email">
    </div>
    
    <div class="form-group">
      <div class="club_meetings">
        <input type="hidden" class="club_meeting_days" name="club_meeting_days">
        <label>Meeting Days:
          <label class="checkbox-inline">
            <input type="checkbox">M
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">T
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">W
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">Th
          </label>
          <label class="checkbox-inline">
            <input type="checkbox">F
          </label>
        </label>
      </div>
    </div>
    
    <div class="form-group">
      <input class="form-control club_meeting_time" type="time" name="club_meeting_time" placeholder="Meeting Time">
    </div>
    
    <div class="form-group">
      <input class="form-control club_meeting_loc" type="text" name="club_meeting_loc" placeholder="Meeting Location">
    </div>
    
    <div class="form-group">
      <input class="form-control club_picture" type="file" name="club_picture">
      <img class="club_pic_preview img-thumbnail hidden" src="#" alt="preview">
    </div>
  ');

  echo $template->render();
  ?>

  <!-- js libraries and default imports -->
  <script src="/static/js/jquery.js"></script>
  <script src="/static/js/bootstrap.js"></script>
  <script src="/static/js/datatables.min.js"></script>
  <script src="/static/js/highlight/highlight.pack.js"></script>
  <script src="/static/js/main.js"></script>

  <!-- CURRENT-PAGE js -->
  <script src="/static/js/clubs.js"></script>

  </body>
  </html>

<?php
// close db connection
$db->close();
?>