<?PHP
session_start();
$_SESSION = [];
session_destroy();
header("Location: clubs.php");
?>