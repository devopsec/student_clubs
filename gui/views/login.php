<?php
// import settings
require_once 'include/db_config.php';

// session settings
session_start();
?>

<html>
<form action='' method='POST'>
  <table>
    <tr>
      <td>Email:</td>
      <td><input type='text' name='email' required/></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input type='password' name='password' required/></td>
    </tr>
    <tr>
      <td colspan='2'><input type='submit' value='Login' name='submit'/></td>
    </tr>
  </table>

</form>
</html>

<?PHP
if (!isset($_POST["submit"])) {
  die();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
if (!$conn) {
  die('Could not connect: ' . mysql_error());
}

$email = mysqli_escape_string($conn, $_POST["email"]);
$password = mysqli_escape_string($conn, $_POST["password"]);
$sql = "Select id,name from User where email='$email' AND password=SHA2('$password',512);";
if (!($query = mysqli_query($conn, $sql))) {
  die('Error: ' . mysqli_error($conn));
}

if (mysqli_num_rows($query) == 1) {
  $result = mysqli_fetch_array($query);
  $_SESSION['name'] = $result["name"];
  $_SESSION['id'] = $result["id"];
  header("Location: /views/clubs.php");
}
else {
  echo "Login Failed";
}

?>