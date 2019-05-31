<?php
// import settings
require_once 'include/db_config.php';
require_once 'include/template_engine.php';

// create connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT)
or die("Connection to db failed: " . $db->connect_error);

// session settings
session_start();

if (empty($_SESSION["id"]) || $_SESSION["id"] != 1) {
  echo "<br>You must be logged in as an administrator to view this page";
  header("Location: forbidden.html");
  die();
}
?>

  <head>

    <!-- title and general meta -->
    <title>Kettering Student Organizations</title>
    <meta charset="utf-8">
    <meta name="description" content="Organizations and Clubs orgranized by Kettering University students.">
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
              <!-- <li><a href="#"><span class="fa fa-user"></span>My Profile</a></li>
              <li><a href="#"><span class="fa fa-gear"></span>Settings</a></li> -->
              <li class="divider"></li>
              <li>
                <?php if (!empty($_SESSION["id"])): ?>
                  <a href="/views/logout.php"><span class="fa fa-power-off"></span>Logout</a>
                <?php else: ?>
                  <a href="/views/login.php"><span class="fa fa-power-off"></span>Login</a>
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
          <h4>Kettering Student Organizations Users</h4>
        </div>

        <!-- table filters -->
        <!-- TODO: integrate these into datatables library as components -->
        <div class="wrapper-horizontal edge-centered">

          <!-- section filter dropdown 
          <div id="sectionFilter">
            <label>Section:
              <select>
                <option value="A">A</option>
                <option value="B">B</option>
              </select>
            </label>
          </div>
			-->
          <!-- day filter radio buttons 
          <div id="dayFilter">
            <label>Day:
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>M
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>T
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>W
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>Th
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>F
              </label>
            </label>
          </div>
		-->
        </div>

      </div>

      <br>
      <div class="clearfix"></div>
      <br>

      <!-- table data -->
      <div class="table-responsive">
        <table id="usersTable" class="table table-striped table-centered">

          <thead>
          <tr class='element-row'>
            <th data-field="user_id">
              <!-- add table row button -->
              <button id='open-Add' class='btn btn-success btn-md' data-title='Add' data-toggle='modal'
                      data-target='#add'>Add
              </button>
            </th>
            <th data-field="user_name">User Name</th>
            <th data-field="student_id">Student ID</th>
            <th data-field="student_email">Student Email</th>
            <th data-field="student_phone">Student Phone</th>
            <th>Edit</th>
            <th>Delete</th>

          </tr>
          </thead>
          <tbody>
          <?php
          $res = $db->query("SELECT id AS user_id, name AS user_name, student_id, email AS student_email, phone AS student_phone FROM User");
          if ($res->num_rows == 0) {
            return;
          }
          while ($row = $res->fetch_assoc()) {
            ?>
            <tr class='element-row'>
              <td class='user_id'><?php echo $row['user_id'] ?></td>
              <td class='user_name'><?php echo $row['user_name'] ?></td>
              <td class='student_id'><?php echo $row['student_id'] ?></td>
              <td class='student_email'><?php echo $row['student_email'] ?></td>
              <td class='student_phone'><?php echo $row['student_phone'] ?></td>

              <td>
                <p data-placement='top' data-toggle='tooltip' title='Edit'>
                  <button id='open-Update' class='open-Update btn btn-primary btn-xs' data-title='Edit'
                          data-toggle='modal' data-target='#edit'>
                    <span class='icon-edit'></span>
                  </button>
                </p>
              </td>
              <td>
                <p data-placement='top' data-toggle='tooltip' title='Delete'>
                  <button id='open-Delete' class='open-Delete btn btn-danger btn-xs' data-title='Delete'
                          data-toggle='modal' data-target='#delete'>
                    <span class='icon-delete'></span>
                  </button>
                </p>
              </td>
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
  <!-- TODO: copy from clubs.php and update values for this table -->

  <script src="/static/js/jquery.js"></script>
  <script src="/static/js/bootstrap.js"></script>
  <script src="/static/js/datatables.min.js"></script>
  <script src="/static/js/highlight/highlight.pack.js"></script>
  <script src="/static/js/main.js"></script>

  <script type="application/javascript">
      window.RAPID = {};
      $(document).ready(function () {
          /* query param actions */
          if (getQueryString('action') === 'add') {
              $('#add').modal('show');
          }
          /* add code syntax highlighting */
          $('pre code').each(function (i, block) {
              hljs.highlightBlock(block);
          });
      });
  </script>

  <!-- CURRENT-PAGE js -->
  <script src="/static/js/clubs.js"></script>

  </body>
  </html>

<?php
// close db connection
$db->close();
?>