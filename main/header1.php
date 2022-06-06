<?php
ob_start();
session_start();

if (!isset($_SESSION['permission']) || $_SESSION['permission'] === 'NULL') {
  header("Location: /login.php");
}

$name = $_SESSION['username'];
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$last_part = end($components);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <title>HealthyCURE | Home</title>
  <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/images/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="/images/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
  <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- select2 -->
  <link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <!-- daterangepicker -->
  <link href="/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    body {
      padding-right: 0 !important
    }

    /* Important part */
    .modal-dialog {
      overflow-y: initial !important
    }

    .modal-body {
      max-height: calc(100vh - 200px);
      overflow-x: auto !important;
    }

    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
      background-color: #561EC9ff;
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
          <a class="nav-link" href="/logout.php">
            <i class="fas fa-power-off"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/index.php" class="brand-link">
        <img src="/dist/img/H.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">HealthyCURE</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="index.php" class="d-block"><?php echo $name ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/index.php" class="nav-link <?php echo (!in_array($components[1], ['users', 'products', 'databases', 'inventory', 'api', 'sales_applicants']) && $last_part == 'index.php') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Home
                </p>
              </a>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/users/index.php" class="nav-link <?php echo ($components[1] === 'users' && $last_part == 'index.php') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Users
                </p>
              </a>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/fingerprint/index.php" class="nav-link <?php echo ($components[2] === 'fingerprint' && $last_part == 'index.php') ? 'active' : ''; ?>">
                <i class="fa fa-fire-alt nav-icon"></i>
                <p>Fingerprint</p>
              </a>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/api/campaign/index.php" class="nav-link <?php echo ($components[2] === 'campaign' && $last_part == 'index.php') ? 'active' : ''; ?>">
                <i class="fa fa-fire-alt nav-icon"></i>
                <p>Campaign</p>
              </a>
            </li>
            <li class="nav-item has-treeview  <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?> <?php echo $components[1] === 'api' && $components[2] === 'api' ? 'menu-open' : ''; ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-question"></i>
                <p>
                  Api
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview" <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>>
                <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                  <a href="/api/index.php" class="nav-link <?php echo $components[1] === 'api' && $components[2] !== 'campaign' && $components[2] !== 'combo' && $last_part == 'index.php' ? 'active' : ''; ?>">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Leads</p>
                  </a>
                </li>
                <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                  <a href="/api/duplicate.php" class="nav-link <?php echo $components[1] === 'api' && $components[2] !== 'campaign' && $components[2] !== 'combo' && $last_part == 'duplicate.php' ? 'active' : ''; ?>">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Duplicate</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item has-treeview  <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?> <?php echo $components[1] === 'api' && $components[2] === 'combo' ? 'menu-open' : ''; ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-question"></i>
                <p>
                  Api 2
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview" <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>>
                <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                  <a href="/api/combo/index.php" class="nav-link <?php echo $components[1] === 'api' && $components[2] == 'combo' && $components[2] !== 'campaign' && $last_part == 'index.php' ? 'active' : ''; ?>">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Leads</p>
                  </a>
                </li>
                <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                  <a href="/api/combo/duplicate.php" class="nav-link <?php echo $components[1] === 'api' && $components[2] == 'combo' && $components[2] !== 'campaign' && $last_part == 'duplicate.php' ? 'active' : ''; ?>">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Duplicate</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/products/index.php" class="nav-link <?php echo $components[1] === 'products' && $last_part == 'index.php' || $last_part == 'one.php' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-cubes"></i>
                <p>
                  Products
                </p>
              </a>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/inventory/index.php" class="nav-link <?php echo $components[1] === 'inventory' && $last_part == 'index.php' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-university"></i>
                <p>
                  Inventory
                </p>
              </a>
            </li>
            <li class="nav-item has-treeview  <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?> <?php echo $components[1] === 'databases' || $last_part === 'leads.php' ? 'menu-open' : ''; ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  DBs
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview  <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?> <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                  <a href="/databases/index.php" class="nav-link <?php echo $last_part == 'index.php' && $components[1] === 'databases' ? 'active' : ''; ?>">
                    <i class="fas fa-home nav-icon"></i>
                    <p>Home</p>
                  </a>
                </li>
                <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
                  <a href="/databases/stats.php" class="nav-link <?php echo $last_part == 'stats.php' && $components[1] === 'databases' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie nav-icon"></i>
                    <p>Statistics</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/delivery/admin.php" class="nav-link <?php echo $last_part == 'admin.php' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-shipping-fast"></i>
                <p>
                  Delivery
                </p>
              </a>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/track.php" class="nav-link <?php echo $last_part == 'track.php' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-recycle"></i>
                <p>
                  Track
                </p>
              </a>
            </li>
            <li class="nav-item has-treeview <?php echo $components[1] === 'status' ? 'menu-open' : ''; ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-question"></i>
                <p>
                  Status
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/status/approved.php" class="nav-link <?php echo $last_part == 'approved.php' ? 'active' : ''; ?>">
                    <i class="far fa-check-circle nav-icon text-success"></i>
                    <p>Approved</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/status/canceld.php" class="nav-link <?php echo $last_part == 'canceld.php' ? 'active' : ''; ?>">
                    <i class="far fa-times-circle nav-icon text-danger"></i>
                    <p>Canceld</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/status/pending.php" class="nav-link <?php echo $last_part == 'pending.php' ? 'active' : ''; ?>">
                    <i class="fa fa-phone nav-icon text-warning"></i>
                    <p>Pending</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item <?php echo $_SESSION['permission'] !== '0' ? 'd-none' : '';  ?>">
              <a href="/sales_applicants/index.php" class="nav-link <?php echo ($components[1] === 'sales_applicants' && $last_part == 'index.php') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Sales Applicants
                </p>
              </a>
            </li>
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