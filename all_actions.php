<?php 
include('main/header1.php');  
include('main/database.php');  
?>

<!-- TABLE: LATEST ORDERS -->
<div class="card">
  <div class="card-header border-transparent">
    <h3 class="card-title">All actions</h3>

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
      <table id="all_actions" class="table m-0">
        <thead>
        <tr>
          <th>id</th>
          <th>Sales</th>
          <th>Status</th>
          <th>Comment</th>
          <th>Time on</th>
        </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <!-- /.table-responsive -->
  </div>
</div>
<!-- /.card -->


<?php include('main/footer1.php'); ?>
<script>
	getRecords('fetch_actions.php','','','','','','','#all_actions')
</script>
</body>
</html>