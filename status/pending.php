<?php 
ob_start();
session_start();
if (isset($_SESSION['permission']) && !in_array($_SESSION['permission'], ['0', '7'])) {
  header('Location: ../login.php');
}
$pn = $_SESSION['pn'] = $_GET['pn'];
include('../main/header1.php');
include('../main/database.php');
// // All orders In Pending Table
$stmt = $con->prepare("SELECT * FROM pending");
$stmt->execute();
$countNew = $stmt->rowCount();

// GrouP By Project Name
$stmt = $con->prepare("SELECT pname, count(*) FROM pending GROUP BY pname");
$stmt->execute();
$projectsCount = $stmt->rowCount();
if ($projectsCount > 0) {
  $projects = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Leads in Table Pending
$pn = $_GET['pn'];
if (isset($pn)) {
  // Group By Employee ID
  $where = isset($pn) && $pn !== 'all' ? " WHERE pname ='" . $pn . "' GROUP BY pending.emp_id" : ' GROUP BY pending.emp_id';
  $stmt = $con->prepare("SELECT pending.emp_id,count(pending.id) as count, admins.name FROM pending LEFT JOIN admins ON pending.emp_id = admins.id $where");
  $stmt->execute();
  $employee_group = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Group By Reason
  $where = isset($pn) && $pn !== 'all' ? " WHERE pname ='" . $pn . "' GROUP BY status" : ' GROUP BY status';
  $stmt = $con->prepare("SELECT status, count(*) as count FROM pending $where");
  $stmt->execute();
  $status_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php if (!isset($_GET['pn'])) { ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white">
      <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
      <li class="breadcrumb-item active"><a href="pending.php">Pending</a></li>
    </ol>
  </nav>
<?php } else { ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white">
      <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
      <li class="breadcrumb-item active"><a href="pending.php">Pending</a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="pending.php?pn=<?php echo $pn ?>">
          <?php echo $pn; ?>
        </a>
      </li>
    </ol>
  </nav>
<?php } ?>
<div class="mb-3 d-flex flex-wrap justify-content-around">
  <!-- /.col -->
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-warning"><i class="fa fa-phone"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="pending.php?pn=all" style="text-decoration: none" class="<?php echo $_GET['pn'] === 'all' ? 'font-weight-bold' : ''; ?>">
            All</a></span>
        <span class="info-box-number"><?php echo $countNew ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <?php
  if (!empty($projects)) {
    foreach ($projects as $key => $value) { ?>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
          <span class="info-box-icon bg-warning"><i class="fa fa-phone"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><a href="pending.php?pn=<?php echo $key ?>" style="text-decoration: none" class="<?php echo $_GET['pn'] === $key ? 'font-weight-bold' : ''; ?>">
                <?php echo $key ?></a></span>
            <span class="info-box-number"><?php echo $value ?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
  <?php }
  } ?>
</div>
<!-- TABLE: DB PENDING -->
<div class="card">
  <div class="card-header border-transparent">
    <h3 class="card-title">Pending</h3>

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
    <div class="float-right d-flex justify-content-around col-lg-4 col-md-12 col-sm-12">
      <button type="button" id="deleteAllButton" class="p-1 col-5 btn btn-outline-danger btn-sm" onclick="deleteAll('/delete.php', 'pending','Delete selected orders','At least one order must be selected','All orders are safe')">
        <i class="fa fa-trash"></i> Delete
      </button>
      <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addEmpToLeadModal" class="p-1 col-5 btn btn-outline-success btn-sm">
        <i class="fa fa-edit"></i> Re assiagn
      </button>
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-center col-12 p-2">
      <form class="col-12" method="POST" id="filter_pending">
        <div class="d-lg-flex d-md-block justify-content-lg-between">
          <select class="form-group col-lg-3 col-md-12 col-sm-12" id="filter_emp">
            <option class="form-control" value=''>Employee</option>
            <?php if ($employee_group !== null) {
              foreach ($employee_group as $key => $value) { ?>
                <option class="form-control" value="<?php echo $value['emp_id'] === NULL ? 'NULL' : $value['emp_id']; ?>">
                  <?php echo $value['name'] === NULL ? 'Null' : $value['name']; ?> <?php echo ' ( ' . $value['count'] . ' )' ?>
                </option>
              <?php } ?>
            <?php } ?>
          </select>
          <select class="form-group col-lg-3 col-md-12 col-sm-12" id="filter_st">
            <option class="form-control" value="">Status</option>
            <?php if ($status_group !== null) {
              foreach ($status_group as $key => $value) { ?>
                <option class="form-control" value="<?php echo $value['status'] === NULL ? 'NULL' : $value['status'] ?>">
                  <?php echo $value['status'] === NULL ? 'NULL' . ' ( ' . $value['count'] . ' )' : $value['status'] . ' ( ' . $value['count'] . ' )'; ?>
                </option>
              <?php } ?>
            <?php } ?>
          </select>
          <div id="reportrange" class="col-lg-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar text-primary"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down text-primary"></i>
          </div>
          <button type="submit" class="col-lg-2 btn btn-sm btn-block btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
        </div>
      </form>
    </div>
    <div class="table-responsive">
      <table id="table_id" class="table table-hover table-bordered table-responsive" style="width:100%">
        <thead>
          <tr>
            <th class="text-center" style="max-width:10px">
              <input type="checkbox" class="checkedAll" />
            </th>
            <th style="min-width: 10px;">Id</th>
            <th style="min-width: 150px;">Name</th>
            <th style="min-width: 100px;">Phone</th>
            <th style="min-width: 100px;">Product</th>
            <th style="min-width: 10px;">Quantity</th>
            <th style="min-width: 100px;">Total</th>
            <th style="min-width: 100px">Fingerprint</th>
            <th style="min-width: 100px">From</th>

            <th style="min-width: 100px;">Employee</th>
            <th style="min-width: 150px;">Reason</th>
            <th style="min-width: 150px;">Doo</th>
            <th style="min-width: 200px;">Added at</th>
            <th style="min-width: 200px;">Updated at</th>
            <th style="min-width: 100px;">History</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <!-- /.table-responsive -->
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
<!-- Choose Employee -->
<div class="modal fade" id="addEmpToLeadModal" tabindex="-1" role="dialog" aria-labelledby="addEmpToLeadModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-danger">
        <h5 class="modal-title text-capitalize" id="addEmpToLeadModalLabel">Select Employee</h5><br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <small id="errorChecked" class="text-danger text-center"></small>
      <div class="modal-body">
        <div class="row">
          <div class="form-group col-12">
            <label for="employee">Select Employee</label>
            <select class="form-control col-12 pb-0" id="employee" required style="width:100%">
              <?php
              // Employees Select Box
              $stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
              $stmt->execute();
              $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
              ?>
              <option value="null">None</option>
              <?php foreach ($employes as $emp) { ?>
                <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
              <?php } ?>
            </select>
          </div>

        </div>
      </div>
      <div class="modal-footer" style="direction:ltr">
        <div class="form-group col-12 d-flex justify-content-between">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
          <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('/update.php', 'pending', 'leads re assiagn to this sales','you must choose any lead', 'leads not re assiagn to this sales','not null')">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- History Modal -->
<div class="modal fade" id="HistoryModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-danger">
        <h5 class="modal-title text-capitalize" id="historyModalLabel">History</h5><br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="history_body">

        </div>
      </div>
      <div class="modal-footer">
        <div class="form-group col-12 d-flex justify-content-between">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('../main/footer1.php'); ?>

<script>
  getRecords('fetch_pending.php');
  $(document).ready(function() {
    document.title = 'HealthyCURE | Pending';
    var start = moment().subtract(120, 'days');
    var end = moment();
    var count = 0;
    var startDate = '';
    var endDate = '';

    function cb(start, end) {
      startDate = start.format('YYYY-MM-DD')
      endDate = end.format('YYYY-MM-DD')
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#reportrange').daterangepicker({
      startDate: start,
      endDate: end,
      "opens": "center",
      "alwaysShowCalendars": true,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
    }, cb);
    cb(start, end);

    $(document).on('submit', '#filter_pending', function(e) {
      e.preventDefault();
      count++;
      if (count === 1) {
        var emp_id = $('#filter_emp').val();
        var status = $('#filter_st').val();
        var date_first = startDate;
        var date_last = endDate;
        $('#table_id').DataTable().destroy();
        getRecords('fetch_pending.php', emp_id, status, date_first, date_last);
      }
      count = 0;
    })
    $('#employee').select2({
      dropdownParent: $('#addEmpToLeadModal')
    });
  });
</script>
</body>

</html>