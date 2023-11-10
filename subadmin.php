<?php

    ob_start();

    session_start();

    

    if($_SESSION['permission'] !== '1' || $_SESSION['permission'] == '0') {

        header('Location: login.php');

    }

    include('/main/database.php');

    

    $sessionName        = $_SESSION['username'];

    $sessionPermission  = $_SESSION['permission'];

    $sessionId          = $_SESSION['id'];

    

    if(isset($sessionName) && isset($sessionPermission) && isset($sessionId)) {

        if(isset($_POST['submit-status'])) {

            $id = $_POST['update_id'];

            $stmt = $con->prepare("UPDATE orderd SET status = 1 WHERE id = $id");

    

            if($stmt->execute()) {

                $id = '';

            }

        }

        

        $stmt = $con->prepare("SELECT pname, count(*) FROM orderd WHERE status = 0 GROUP BY pname");

        $stmt->execute();

        $countPro = $stmt->rowCount();

        

        if($countPro > 0) {

            $projects = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        }

        

        // // Leads in $_SESSION One Employee

        if(isset($_GET['pn']) && $_GET['pn'] != '') {

            $pn = $_GET['pn'];

            $stmt = $con->prepare("SELECT * FROM orderd WHERE status = 0 AND pname = '". $pn ."'");

            $stmt->execute();

            $countLeads = $stmt->rowCount();



            if($countLeads > 0) {

                $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

            }

        }

    } else {

        header('Location:login.php');

    }

?>







<!DOCTYPE html>

<html lang="en">

    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <!-- Required meta tags -->

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>لوحة المشرف - <?php echo $_SESSION['username']; ?></title>

        <link rel="stylesheet" href="/css/responsive.bootstrap4.min.css" />

        <link rel="stylesheet" href="/css/jquery.dataTables.min.css" />

        <link rel="stylesheet" href="/css/bootstrap.min.css" />

        <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css" />

        

        <link rel="stylesheet" href="/css/all.css">

        <link rel="stylesheet" href="https://estsmarblog.com/MyFormCpanel/css/form-panel.css">

    

        <style>

            .navbar-dark .navbar-brand {

                color: #ffd401;

            }

        </style>

    </head>

    <body>

        <nav class="navbar navbar-expand-lg navbar-light bg-dark">

            <div class="container-fluid">

                <a class="nav-link text-warning text-capitalize" href="logout.php">

                    <?php echo $_SESSION['username'] ?>

                    <i class="fas fa-power-off"></i>

                </a>

            </div>

        </nav>

        <?php if($countPro > 0) { ?>

        <div class="container mt-3">

            <div class="row justify-content-center">

            <?php foreach($projects as $key => $value) {?>

                <div class="col-lg-3 col-md-6 col-sm-12 text-center">

                    <div class="card">

                        <div class="card-body">

                            <h5 class="card-title"><?php echo $key ?></h5>

                            <p class="card-text"><?php echo $value ?></p>

                            <a href="subadmin.php?pn=<?php echo $key ?>" class="btn btn-sm <?php echo $_GET['pn'] === $key ? 'btn-success' : 'btn-outline-success'; ?>">الذهاب الى الطلبات</a>

                        </div>

                    </div>

                </div>

            <?php } ?>

            </div>

        </div>

        <?php } ?>

        <diV class="container mt-3 text-center">

            <h3><?php echo $_GET['pn']; ?></h3>

            <table id="table_id" class="table table-striped table-bordered table-responsive mt-5" dir="rtl" style="width:100%">

                <thead>

                    <tr>

                        <th>id</th>

                        <th>أسم العميل</th>

                        <th>رقم الهاتف</th>

                        <th>المنتج</th>

                        <th>السعر</th>

                        <th>لعنوان</th>

                        <th>تاريخ الطلب</th>

                        <th>تاريخ التوصيل</th>

                        <th>طريق الشحن</th>

                        <th>تم التوصيل</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if($countLeads > 0) { ?>

                    <?php foreach($leads as $lead) { ?>

                        <tr id="<?php echo 'row-' . $lead['id'] ?>">

                            <td style="min-width:100px;"><?php echo $lead['id']; ?></td>

                            <td style="min-width:100px;"><?php echo $lead['name']; ?></td>

                            <td style="min-width:100px;"><?php echo $lead['phone']; ?></td>

                            <td style="min-width:100px;"><?php echo $lead['prname']; ?></td>

                            <td style="min-width:100px;"><span class="badge badge-danger badge-pill p-2"><?php echo $lead['prprice'] . ' ' . $lead['prcurrency']; ?></span></td>

                            <td style="min-width:100px;"><?php echo $lead['address']; ?></td>

                            <td style="min-width:100px;"><?php echo $lead['doo']; ?></td>

                            <td style="min-width:100px;"><?php echo $lead['dod']; ?></td>

                            <td style="min-width:100px;"><?php echo $lead['wod']; ?></td>

                            <td class="text-center">

                                <form method="post">

                                    <input hidden="true" type="hidden" name="update_id" value="<?php echo $lead['id'] ?>" />

                                    <button class="btn btn-outline-success btn-sm" type="submit" name="submit-status">تم التوصيل</button>

                                </form>

                            </td>

                        </tr>

                    <?php } ?>

                    <?php } ?>

                </tbody> 

            </table>

        </diV>

        <script src="/js/jquery-3.3.1.min.js"></script>

	    <script src="/js/jquery.dataTables.min.js"></script>

	    <script src="/js/dataTables.bootstrap4.min.js"></script>

	    <script src="/js/dataTables.responsive.min.js"></script>

	    <script src="/js/responsive.bootstrap4.min.js"></script>

	    <script src="/js/bootstrap.min.js"></script>

	    

	    

	    <script>

	        $(document).ready( function () {

                $.fn.dataTable.ext.classes.sPageButton = 'paginate_custom_buttons';

                $('#table_id').DataTable( {

                    dom: 'lfrtip',

                    "language": {

                        "paginate": {

                        "previous": "السابق",

                        "next": "التالى"

                        },

                        "search": "البحث:",

                    }

                } );

            }); 

	    </script> 

    </body>

</html>