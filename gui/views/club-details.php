<?PHP
/* DEBUG:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once 'include/db_config.php';
require_once 'include/app_config.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT)
or die("Connection to db failed: " . $db->connect_error);

// session settings
session_start();
?>
<head>
    <link rel="stylesheet" href="/static/css/bootstrap.css">
    <link rel="stylesheet" href="/static/css/bootstrap-theme.css">
    <link rel="stylesheet" href="/static/css/fa.css">
    <link rel="stylesheet" href="/static/css/datatables.min.css">
    <link rel="stylesheet" href="/static/css/highlight/github.css">
    <link rel="stylesheet" href="/static/css/main.css">
</head>
 <header>
      <nav class="nav-bar navbar-inverse wrapper-horizontal" role="navigation">
        <div>
          <a class="navbar-brand" href="/views/clubs.php">
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
<body>
 <?PHP
$club_id = $_GET["id"];

//updated SQL query
$res = $db->query("SELECT id as club_id, name as club_name, faculty_advisor as club_poc, faculty_email as club_poc_email, norm_meeting_days as club_meeting_days, norm_meeting_time as club_meeting_time, norm_meeting_loc as club_meeting_loc, description, (Select U.name from User U where U.id=C.president) as president,(Select U.email from User U where U.id=C.president) as president_email, picture FROM Club C WHERE id=$club_id");
while ($row = $res->fetch_assoc()) {
  ?>

  <h1><?php echo $row['club_name'] ?> </h1>
  <table width="100%" border="0" cellpadding="10">
  <tr>
  <td width="5%"></td>
  <td width="30%">
  <img src="<?php echo $row['picture'] ?>" alt="Club Image"/>
  </td>
  <td width="10%"></td>
  <td>
	  <table width="50%" class="table table-striped table-centered table-hover dataTable no-footer" border='1' style="border-collapse: collapse">
		<tr>
		  <td width="50%">Club President</td>
		  <td><?php echo $row['president'] ?></td>
		</tr>
		<tr>
		  <td width="50%">President Contact</td>
		  <td><?php echo $row['president_email'] ?></td>
		</tr>
		<tr>
		  <td width="50%">Faculty Advisor</td>
		  <td><?php echo $row['club_poc'] ?></td>
		</tr>
		<tr>
		  <td width="50%">Faculty Email</td>
		  <td><?php echo $row['club_poc_email'] ?></td>
		</tr>
		<tr>
		  <td width="50%">Regular Meeting Days</td>
		  <td><?php echo $row['club_meeting_days'] ?></td>
		</tr>
		<tr>
		  <td width="50%">Regular Meeting Time</td>
		  <td><?php echo $row['club_meeting_time'] ?></td>
		</tr>
		<tr>
		  <td width="50%">Meeting Location</td>
		  <td><?php echo $row['club_meeting_loc'] ?></td>
		</tr>
	  </table>
  </td>
  <td width="10%"></td>
  </tr>
  <tr>
	  <td></td>
	  <td style="color:white;font-size:16px;font-weight:bold">
	  <h4>Club Description:</h4>
		<?php echo $row['description'] ?></td>
	  <td></td>
	  <td></td>
	  <td></td>
  </tr>
  </table>

  <?php
}
$res->free();
?>
</body>


