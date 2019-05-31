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

$club_id = $_GET["id"];

//updated SQL query
$res = $db->query("SELECT id as club_id, name as club_name, faculty_advisor as club_poc, faculty_email as club_poc_email, norm_meeting_days as club_meeting_days, norm_meeting_time as club_meeting_time, norm_meeting_loc as club_meeting_loc, description, (Select U.name from User U where U.id=C.president) as president, picture FROM Club C WHERE id=$club_id");
while ($row = $res->fetch_assoc()) {
  ?>
  <h1><?php echo $row['club_name'] ?> </h1>
  <img src="<?php echo UPLOADS_URL_PATH . $row['picture'] ?>" alt="Club Image"/>
  <table border='1' style="border-collapse: collapse">
    <tr>
      <td>Club President</td>
      <td><?php echo $row['president'] ?></td>
    </tr>
    <tr>
      <td>Faculty Advisor</td>
      <td><?php echo $row['club_poc'] ?></td>
    </tr>
    <tr>
      <td>Faculty Email</td>
      <td><?php echo $row['club_poc_email'] ?></td>
    </tr>
    <tr>
      <td>Regular Meeting Days</td>
      <td><?php echo $row['club_meeting_days'] ?></td>
    </tr>
    <tr>
      <td>Regular Meeting Time</td>
      <td><?php echo $row['club_meeting_time'] ?></td>
    </tr>
    <tr>
      <td>Meeting Location</td>
      <td><?php echo $row['club_meeting_loc'] ?></td>
    </tr>
  </table>

  <p>
    Club Description:<br>
    <?php echo $row['description'] ?>
  </p>

  <?php
}
$res->free();
?>


