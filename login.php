<?php
ob_start();
session_start();

if (isset($_SESSION['permission']) && $_SESSION['permission'] === "0") {
  header("Location:index.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "1") {
  header("Location:delivery/index.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "3") {
  header("Location:delivery/supd-cairo.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "4") {
  header("Location:delivery/supa-cairo.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "5") {
  header("Location:delivery/delivery_man.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "6") {
  header("Location:delivery/index.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "2") {
  header("Location: employee/employee.php");
  exit;
}
if (isset($_SESSION['permission']) && $_SESSION['permission'] === "7") {
  header("Location: status/approved.php");
  exit;
}

include "main/database.php";
if (isset($_POST['submit'])) {
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  $stmt = $con->prepare('SELECT * FROM admins WHERE name=:name LIMIT 1');
  $stmt->bindParam("name", $name, PDO::PARAM_STR);
  $stmt->execute();
  $mainCount = $stmt->rowCount();
  $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($mainCount > 0) {
    if (password_verify($password, $fetch['password'])) {
      $_SESSION['username'] = $name;
      $_SESSION['id'] = $fetch['id'];
      $_SESSION['permission'] = $fetch['permission'];

      $stmt = $con->prepare("UPDATE admins SET online = 1 WHERE id = :id");
      $stmt->bindParam("id", $_SESSION['id'], PDO::PARAM_STR);
      $stmt->execute();

      if ($fetch['permission'] === "0") {
        header("Location:index.php");
        exit;
      }
      if ($fetch['permission'] === "1") {
        header("Location:delivery/index.php");
        exit;
      }
      if ($fetch['permission'] === "3") {
        header("Location:delivery/supd-cairo.php");
        exit;
      }
      if ($fetch['permission'] === "4") {
        header("Location:delivery/supa-cairo.php");
        exit;
      }
      if ($fetch['permission'] === "5") {
        header("Location:delivery/delivery_man.php");
        exit;
      }
      if ($fetch['permission'] === "6") {
        header("Location:delivery/index.php");
        exit;
      }
      if ($fetch['permission'] === "2") {
        header("Location:employee/employee.php");
        exit;
      }
      if ($fetch['permission'] == "7") {
        header("Location: status/approved.php");
        exit;
      }
    }
  } else {
    $msg = "<span class='text-danger'>Username or Password Invalid!</span>";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HealthyCURE | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <p><b>Healthy</b>CURE</p>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php if (isset($msg) != '') {
          echo '<p class="text-center">' . $msg . '</p>';
        } ?>
        <form method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" name="name" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="submit" class="btn btn-primary btn-block">Log In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>