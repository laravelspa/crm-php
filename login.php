<?php
ob_start();
session_start();
include "main/database.php";

if (isset($_SESSION['login']) && $_SESSION['login']) {
  header("Location:users/index.php");
  return;
}

$msg = '';
if (isset($_POST['submit'])) {
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  $stmt = $con->prepare('SELECT * FROM users WHERE name=:name LIMIT 1');
  $stmt->bindParam("name", $name, PDO::PARAM_STR);
  $stmt->execute();
  $mainCount = $stmt->rowCount();
  $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($mainCount > 0) {
    if (password_verify($password, $fetch['password'])) {
      $_SESSION['username'] = $name;
      $_SESSION['id'] = $fetch['id'];
      $_SESSION['login'] = 1;
      $_SESSION['is_admin'] = $fetch['is_admin'];

      header("Location:users/index.php");
      exit;
    }

    $msg = "<span class='text-danger'>الأسم أو كلمة المرور غير صحيحة</span>";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>شهادات | تسجيل الدخول</title>
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
  <!-- RTL style -->
  <link rel="stylesheet" href="css/custom.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <p><b>نظام</b> الشهادات</p>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">الدخول الى النظام</p>
        <?php if (isset($msg) != '') {
          echo '<p class="text-center">' . $msg . '</p>';
        } ?>
        <form method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="إسم المستخدم" name="name" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="كلمة المرور" name="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="submit" class="btn btn-primary btn-block">تسجيل الدخول</button>
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