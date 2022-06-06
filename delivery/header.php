<?php 
ob_start();
session_start();
$permission = $_SESSION['permission'];
if($_SESSION['permission'] === '0' || $_SESSION['permission'] === '2') {
  header('Location: ../login.php');
}
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$last_part = end($components);
?>
<!DOCTYPE html>
<html lang="en">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <!-- Required meta tags -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>HealthyCURE | <?php echo $_SESSION['username']; ?></title>
        <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
        <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
          <!-- SweetAlert2 -->
        <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
          <!-- select2 -->
        <link href="../plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css">
        <!-- daterangepicker -->
        <link href="../plugins/daterangepicker/daterangepicker.css" rel="stylesheet" /> 
          <!-- Theme style -->
        <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
          <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <style type="text/css">
          body { padding-right: 0 !important }
          .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #561EC9ff;
          }
          .card-primary:not(.card-outline)>.card-header {
              background-color: #561EC9;
          }
        </style>
    </head>
  
    <body class="hold-transition sidebar-collapse sidebar-mini">
    <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
         <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" href="../logout.php">
              <i class="fas fa-power-off"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <img src="../dist/img/H.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">HealthyCURE</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="index.php" class="d-block"><?php echo $_SESSION['username']; ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <?php if($permission !== '3' && $permission !== '4' && $permission !== '5') { ?>
              <li class="nav-item">
                <a class="nav-link <?php echo $last_part == 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-shipping-fast"></i>
                    <p>Aramex</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo $last_part == 'cairo-alex.php' ? 'active' : ''; ?>" href="cairo-alex.php">
                    <i class="fas fa-globe"></i>
                    <p>Cairo - Alexandria</p>
                </a>
              </li>
              <?php } ?>
              <?php if($permission === '3') { ?>
              <li class="nav-item">
                  <a class="nav-link <?php echo $last_part == 'supd-alex.php' ? 'active' : ''; ?>" href="supd-alex.php">
                      <i class="fas fa-shipping-fast"></i>
                      <p>Alexandria</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php echo $last_part == 'supd-cairo.php' ? 'active' : ''; ?>" href="supd-cairo.php">
                      <i class="fas fa-shipping-fast"></i>
                      <p>Cairo</p>
                  </a>
              </li>
              <?php } ?>
              <?php if($permission === '4') { ?>
              <li class="nav-item">
                  <a class="nav-link <?php echo $last_part == 'supa-alex.php' ? 'active' : ''; ?>" href="supa-alex.php">
                      <i class="fas fa-shipping-fast"></i>
                      <p>Alexandria</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php echo $last_part == 'supa-cairo.php' ? 'active' : ''; ?>" href="supa-cairo.php">
                      <i class="fas fa-shipping-fast"></i>
                      <p>Cairo</p>
                  </a>
              </li>
              <?php } ?>
              <?php if($permission === '5') { ?>
              <li class="nav-item">    
                  <a class="nav-link <?php echo $last_part == 'delivery_man.php' ? 'active' : ''; ?>" href="delivery_man.php">
                      <i class="fas fa-shipping-fast"></i>
                      <p>My orders</p>
                  </a>
              </li>
              <?php } ?>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

     <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">