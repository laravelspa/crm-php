<?php
ob_start();
session_start();
if ($_SESSION['permission'] !== '0') {
  header('Location: ../login.php');
}
$pn = $_SESSION['pnterra'] = $_GET['pn'];
include('../main/header1.php');
include('../main/database.php');
// All orders In Pending Table
$stmt = $con->prepare("SELECT * FROM terraleads WHERE test_status != 1 AND comment = 'duplicate'");
$stmt->execute();
$countDublicate = $stmt->rowCount();
// GrouP By Project Name
$stmt = $con->prepare("SELECT terraleads.comment, terraleads.test_status, count(*) as count, campaigns.campaign_id, campaigns.campaign_name, campaigns.product_name FROM terraleads LEFT JOIN campaigns ON terraleads.campaign_id = campaigns.campaign_id WHERE terraleads.test_status != 1 AND terraleads.comment = 'duplicate' GROUP BY campaigns.product_name");
$stmt->execute();
$leadsCount = $stmt->rowCount();

if ($leadsCount > 0) {
  $terraleads = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php if (!isset($_GET['pn'])) { ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white">
      <li class="breadcrumb-item"><a href="index.php">Leads</a></li>
      <li class="breadcrumb-item active"><a href="duplicate.php">Dublicate</a></li>
    </ol>
  </nav>
<?php } else { ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white">
      <li class="breadcrumb-item"><a href="/index.php">Leads</a></li>
      <li class="breadcrumb-item active"><a href="duplicate.php">Dublicate</a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="duplicate.php?pn=<?php echo $pn ?>">
          <?php echo $pn; ?>
        </a>
      </li>
    </ol>
  </nav>
<?php } ?>
<div class="d-flex flex-wrap justify-content-between">
  <!-- /.col -->
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-success"><i class="far fa-check-circle"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><a href="duplicate.php?pn=all" style="text-decoration: none" class="<?php echo $_GET['pn'] === 'all' ? 'font-weight-bold' : ''; ?>">
            All</a></span>
        <span class="info-box-number"><?php echo $countDublicate ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <?php if (!empty($terraleads)) {
    foreach ($terraleads as $value) { ?>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
          <span class="info-box-icon bg-success"><i class="far fa-check-circle"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">
              <a href="duplicate.php?pn=<?php echo $value['campaign_name'] ?>" style="text-decoration: none" class="<?php echo $_GET['pn'] === $value['campaign_name'] ? 'font-weight-bold' : ''; ?>">
                <?php echo $value['campaign_name'] ?></a>
            </span>
            <span class="info-box-number"><?php echo $value['product_name'] ?></span>
            <span class="info-box-number"><?php echo $value['count'] ?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
  <?php }
  } ?>
</div>

<!-- TABLE: DB APPROVED -->
<div class="card">
  <div class="card-header border-transparent">
    <h3 class="card-title">Dublicate</h3>

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
    <div class="table-responsive">
      <table id="table_id" class="table table-hover table-bordered table-responsive" style="width: 100%">
        <thead>
          <tr>
            <th style="min-width: 10%" class="text-center">LeadID</th>
            <th style="min-width: 10%" class="text-center">Status</th>
            <th style="min-width: 30%" class="text-center">Comment</th>
            <th style="min-width: 30%" class="text-center">Name</th>
            <th style="min-width: 10%" class="text-center">Country</th>
            <th style="min-width: 30%" class="text-center">Phone</th>
            <th style="min-width: 30%" class="text-center">Address</th>

            <th style="min-width: 10%" class="text-center">CampaignID</th>
            <th style="min-width: 30%" class="text-center">CampaignName</th>
            <th style="min-width: 30%" class="text-center">ProductName</th>

            <th style="min-width: 10%" class="text-center">Cost</th>
            <th style="min-width: 10%" class="text-center">Cost Delivery</th>
            <th style="min-width: 10%" class="text-center">Landing Cost</th>
            <th style="min-width: 10%" class="text-center">Landing Currency</th>

            <th style="min-width: 20%" class="text-center">Fingerprint</th>
            <th style="min-width: 20%" class="text-center">StreamID</th>
            <th style="min-width: 30%" class="text-center">IP</th>
            <th style="min-width: 30%" class="text-center">User Agent</th>

            <th style="min-width: 10%" class="text-center">Timezone</th>
            <th style="min-width: 30%" class="text-center">Add Date</th>
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

<?php include('../main/footer1.php'); ?>
<script>
  document.title = 'HealthyCURE | Dublicate';
  var start = moment().subtract(120, 'days');
  var end = moment();
  // var count = 0;
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

  $(document).ready(function() {
    getRecords('fetch_duplicate.php')

    $(document).on('submit', '#filter_approved', function(e) {
      e.preventDefault();
      // count++;
      // if(count===1) {
      var emp_id = $('#filter_emp').val();
      var city = $('#filter_ci').val();
      var shipping = $('#filter_wod').val();
      var network = $('#filter_db').val();
      var date_first = startDate;
      var date_last = endDate;
      $('#table_id').DataTable().destroy();

      getRecords('fetch_duplicate.php', emp_id, '', date_first, date_last, city, shipping, '#table_id', network)
      // }
    })
  });
</script>
</body>

</html>