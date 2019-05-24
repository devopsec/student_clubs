<?php
// import settings
require_once 'include/db_config.php';

// session settings
session_start();
?>

<html lang="en">

  <head>

    <!-- title and general meta -->
    <title>Kettering Clubs Login</title>
    <meta charset="utf-8">
    <meta name="description" content="Organizations and Clubs organized by Kettering University students.">
    <meta name="keywords" content="organizations,clubs,student,kettering,groups,university,login">

    <!-- css libraries and styles -->
    <link rel="stylesheet" href="/static/css/bootstrap.css">
    <link rel="stylesheet" href="/static/css/bootstrap-theme.css">
    <link rel="stylesheet" href="/static/css/fa.css">
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
    <link rel="stylesheet" href="/static/css/login.css">

  </head>

  <body>

  <div class="login-wrapper wrapper-horizontal centered">
    <form class="login" action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>' method='POST'>

      <header class="form-header">
        <h3 class="title">Login</h3>
      </header>

      <div class="form-body">
        <div class="form-group">
          <input type="email" placeholder="Email" name="email" autofocus required>
        </div>

        <div class="form-group">
          <input type="password" placeholder="Password" name="password" required>
        </div>
      </div>

      <footer class="form-footer">
        <p>
          <i class="fa fa-question-circle" aria-hidden="true"></i>
          <a href="#">Forgot Password</a>
        </p>
        <input type="submit" name="submit" value="Login">
      </footer>

    </form>
  </div>

  </body>

</html>

<?php
if (!isset($_POST["submit"])) {
  die();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
if (!$conn) {
  die('Could not connect: ' . mysqli_error($conn));
}

$email = mysqli_escape_string($conn, $_POST["email"]);
$password = mysqli_escape_string($conn, $_POST["password"]);
$sql = "Select id,name from User where email='$email' AND password=SHA2('$password', 512);";
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