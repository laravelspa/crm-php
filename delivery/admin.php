<?php  
ob_start();
session_start();
if($_SESSION['permission'] !== '0') {
  header('Location: ../login.php');
}
include('../main/database.php');
include('../main/header1.php'); 

// Aramex Status And Approved
$arrayStatus = [
    0 => 'Distributed To Call',
    1 => 'Waiting Call',
    2 => 'Ready',
    3 => 'Waiting Delivery'
];
$arrayApproved = [
    0 => 'Waiting Approved',
    1 => 'Canceld',
    2 => 'Delivered'
];
$stmt = $con->prepare("SELECT status,count(*) as count FROM orderd_delivery WHERE status IN(0,1,2,3) AND approved = 0 GROUP BY status");
$stmt->execute();
$orders_aramex = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare("SELECT approved,count(*) as count FROM orderd_delivery WHERE status IN(3) AND approved != 0 GROUP BY approved");
$stmt->execute();
$orders_aramex_approved = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cairo And Alexandria Status And approved
$arrayCA = [
  4 => 'Distributed To Call',
  5 => 'Waiting Call',
  6 => 'Distributed To S-D'
];

$stmt = $con->prepare("SELECT status,count(*) as count FROM orderd_delivery WHERE status IN(4,5,6) AND emp_delivery_id IS NULL GROUP BY status");
$stmt->execute();
$orders_cairo_alex = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
// Cairo And Alex Status 6,7 Employee Delivery IS NOT NULL = ON Supervisor Assistant And Delivery Man
$arrayCaNext = [
  6 => 'Distributed To Supervisor Assistant',
  7 => 'Distributed To Delivery Man',
];
$stmt = $con->prepare("SELECT status,count(*) as count FROM orderd_delivery WHERE status IN(6,7) AND emp_delivery_id IS NOT NULL AND approved = 0 GROUP BY status");
$stmt->execute();
$orders_cairo_alex_to_assistant_or_delivery_man = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cairo And Alex Status 7 Delivery Man Group By Approved 
$stmt = $con->prepare("SELECT approved,count(*) as count FROM orderd_delivery WHERE status IN(7) AND emp_delivery_id IS NOT NULL AND approved != 0 GROUP BY approved");
$stmt->execute();
$orders_cairo_alex_group_approved = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cairo And Alex Status 8,9 Supervisor Assistant Approved 
$arrayCaNextNext = [
  8 => 'Approved By Supervisor Assistant',
  9 => 'Approved By Supervisor Delivery',
];
$stmt = $con->prepare("SELECT status,count(*) as count FROM orderd_delivery WHERE status IN(8,9) GROUP BY status");
$stmt->execute();
$orders_cairo_alex_assistant_supervisors_approved = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Delivery Orders - Aramex</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="delivery_aramex" class="table table-hover table-bordered table-responsive"  style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 248px;">Status</td>
                        <td style="min-width: 200px;">Count</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders_aramex as $value) { ?>
                    <tr>
                        <td><?php echo $arrayStatus[$value['status']]; ?></td>
                        <td><?php echo $value['count'].' orders'; ?></td>
                    </tr>
                    <?php } ?>
                    <?php foreach ($orders_aramex_approved as $approve) { ?>
                      <tr>
                        <td><?php echo $arrayApproved[$approve['approved']]; ?></td>
                        <td><?php echo $approve['count'].' orders'; ?></td>
                      </tr>
                    <?php } ?>
                </tbody> 
            </table>        
          <!-- /.card-body -->
          </div>
        <!-- /.card -->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Delivery Orders - Cairo + Alex</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="delivery_aramex" class="table table-hover table-bordered table-responsive"  style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 248px;">Status</td>
                        <td style="min-width: 200px;">Count</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders_cairo_alex as $value) { ?>
                    <tr>
                        <td><?php echo $arrayCA[$value['status']]; ?></td>
                        <td><?php echo $value['count'].' orders'; ?></td>
                    </tr>
                    <?php } ?>
                    <?php foreach ($orders_cairo_alex_to_assistant_or_delivery_man as $value) { ?>
                    <tr>
                        <td><?php echo $arrayCaNext[$value['status']]; ?></td>
                        <td><?php echo $value['count'].' orders'; ?></td>
                    </tr>
                    <?php } ?>
                    <?php foreach ($orders_cairo_alex_group_approved as $value) { ?>
                    <tr>
                        <td><?php echo $arrayApproved[$value['approved']] . ' By Delivery Man'; ?></td>
                        <td><?php echo $value['count'].' orders'; ?></td>
                    </tr>
                    <?php } ?>
                    <?php foreach ($orders_cairo_alex_assistant_approved as $value) { ?>
                    <tr>
                        <td>Approved By Supervisor Assistant</td>
                        <td><?php echo $value['count'].' orders'; ?></td>
                    </tr>
                    <?php } ?>
                    <?php foreach ($orders_cairo_alex_assistant_supervisors_approved as $value) { ?>
                    <tr>
                        <td><?php echo $arrayCaNextNext[$value['status']]; ?></td>
                        <td><?php echo $value['count'].' orders'; ?></td>
                    </tr>
                    <?php } ?>
                </tbody> 
            </table>        
          <!-- /.card-body -->
          </div>
        <!-- /.card -->
        </div>
    </div>
</div>
<!-- /.row -->
<?php include('../main/footer1.php'); ?>
<!-- <script src="../js/delivery_admin.js"></script> -->
</body>
</html>