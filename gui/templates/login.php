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
$con = mysqli_connect("localhost", "cs461", "461Club", "CS461");
if (!$con) {
  die('Could not connect: ' . mysql_error());
}
$email = mysqli_escape_string($con, $_POST["email"]);
$password = mysqli_escape_string($con, $_POST["password"]);
$sql = "Select id,name from User where email='$email' AND password=SHA2('$password',512);";
if (!($query = mysqli_query($con, $sql))) {
  die('Error: ' . mysqli_error($con));
}
if (mysqli_num_rows($query) == 1) {
  session_start();
  $result = mysqli_fetch_array($query);
  $_SESSION['name'] = $result["name"];
  $_SESSION['id'] = $result["id"];
  header("Location: clubs.php");
} else {
  echo "Login Failed";
}

?>