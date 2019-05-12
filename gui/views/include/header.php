<?PHP
session_start();
if (empty($_SESSION["id"])) {
  echo "<a href='../login.php'>Login</a>";

}
else {

  echo "Welcome " . $_SESSION["name"] . "! <a href='../logout.php'>Logout</a>";


  if ($_SESSION["id"] == 1) {
    $navbar = "<table border='1' style='border-collapse:collapse' width='100%'><tr>";
    $navbar .= "<td align='center'><a href='../clubs.php'>Clubs</a></td>";
    $navbar .= "<td align='center'><a href='../users.php'>Users</a></td>";
    $navbar .= "</tr></table>";
    echo $navbar;
  }

}
?>