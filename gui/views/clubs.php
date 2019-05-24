<?php
// php settings
ini_set('file_uploads', 1);
error_reporting(E_ALL);

// import settings
require_once 'include/db_config.php';
require_once 'include/template_engine.php';

// create connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT)
or die("Connection to db failed: " . $db->connect_error);

// session settings
session_start();

try {
  print_r($_POST);
}
catch (Exception $e) {
  true;
}

/* TODO: finish form handling */
// form handler
if ($_POST && isset($_POST['rowid'])) {

  if ($_POST['action'] === 'add') {
    $res = $db->query("INSERT INTO Club VALUES (" .
        "NULL," .
        $_POST['club_name'] . "," .
        $_POST['club_president'] . ',' .
        $_POST['club_section'] . ',' .
        $_POST['club_description'] . ',' .
        $_POST['club_poc'] . ',' .
        $_POST['club_poc_email'] . ',' .
        $_POST['club_meeting_days'] . ',' .
        $_POST['club_meeting_time'] . ',' .
        $_POST['club_meeting_loc'] . ',' .
        $_POST['club_picture'] . ')'
    );

    header("Location: views/clubs.php");
    exit;
  }
  elseif ($_POST['action'] === 'edit') {

    header("Location: views/clubs.php");
    exit;
  }
  elseif ($_POST['action'] === 'delete') {

    header("Location: views/clubs.php");
    exit;
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
          <h4>Kettering Student Organizations</h4>
        </div>

      </div>

      <br>
      <div class="clearfix"></div>

      <!-- table data -->
      <div class="table-responsive">
        <table id="clubsTable" class="table table-striped table-centered table-hover">

          <thead>
          <tr class='element-row'>
            <th data-field="club_id">
              <!-- add table row button -->
              <?PHP
              if (!empty($_SESSION["id"])) {
                echo "<button id='open-Add' class='btn btn-success btn-md' title='Add Row' data-toggle='modal' data-target='#add'>Add </button>";
              }
              ?>
            </th>
            <th data-field="club_name">Club Name</th>
            <th data-field="club_poc">Point of Contact</th>
            <th data-field="club_poc_email">POC Email</th>
            <th data-field="club_meeting_days">Meeting Days</th>
            <th data-field="club_meeting_time">Meeting Time</th>
            <th data-field="club_meeting_loc">Meeting Location</th>
            <?PHP
            if (!empty($_SESSION["id"])) {
              echo "
                    <th>Edit</th>
                    <th>Delete</th>
                ";
            }
            ?>
          </tr>
          </thead>
          <tbody>

          <?php
          $res = $db->query("SELECT id AS club_id, name AS club_name, faculty_advisor AS club_poc, faculty_email AS club_poc_email, norm_meeting_days AS club_meeting_days, norm_meeting_time AS club_meeting_time, norm_meeting_loc AS club_meeting_loc FROM Club");
          if ($res->num_rows == 0) {
            return;
          }
          while ($row = $res->fetch_assoc()) {
            ?>

            <tr class='element-row'>
              <td class='club_id'><?php echo $row['club_id'] ?></td>
              <td class='club_name'><?php echo "<a href=club-details.php?id=" . $row['club_id'] . ">" . $row['club_name'] . "</a>"; ?></td>
              <td class='club_poc'><?php echo $row['club_poc'] ?></td>
              <td class='club_poc_email'><?php echo $row['club_poc_email'] ?></td>
              <td class='club_meeting_days'><?php echo $row['club_meeting_days'] ?></td>
              <td class='club_meeting_time'><?php echo $row['club_meeting_time'] ?></td>
              <td class='club_meeting_loc'><?php echo $row['club_meeting_loc'] ?></td>
              <?PHP
              if (!empty($_SESSION["id"])) {
                echo "<td>
                    <button id='open-Update' class='open-Update btn btn-primary btn-xs' title='Edit Row'
                            data-toggle='modal' data-target='#edit'>
                      <span class='icon-edit'></span>
                    </button>
                  </td>";
                echo "<td>
                    <button id='open-Delete' class='open-Delete btn btn-danger btn-xs' title='Delete Row'
                            data-toggle='modal' data-target='#delete'>
                      <span class='icon-delete'></span>
                    </button>
                  </td>";
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
      <div class="club_meeting_days">
        <label>Meeting Days:
          <label class="checkbox-inline">
            <input type="checkbox" name="monday">M
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="tuesday">T
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="wednesday">W
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="thursday">Th
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="friday">F
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
    </div>
  ');

  $template->set('edit_form_body', '
    <div class="form-group">
        <input class="form-control club_name" type="text" name="club_name" placeholder="Club Name">
      </div>
      
      <div class="form-group">
        <input class="form-control club_poc" type="text" name="club_poc" placeholder="Point of Contact">
      </div>
      
      <div class="form-group">
        <input class="form-control club_poc_email" type="email" name="club_poc_email" placeholder="Point of Contact Email">
      </div>
      
      <div class="form-group">
        <div class="club_meeting_days">
          <label>Meeting Days:
            <label class="checkbox-inline">
              <input type="checkbox" name="monday">M
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="tuesday">T
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="wednesday">W
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="thursday">Th
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" name="friday">F
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