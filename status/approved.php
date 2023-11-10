<?php
ob_start();
session_start();
if (isset($_SESSION['permission']) && !in_array($_SESSION['permission'], ['0', '7'])) {
  header('Location: ../login.php');
}
$pn = $_SESSION['pnorderd'] = $_GET && $_GET['pn'];
include('../main/header1.php');
include('../main/database.php');
// All orders In Pending Table
$stmt = $con->prepare("SELECT * FROM orderd");
$stmt->execute();
$countOrderd = $stmt->rowCount();
// GrouP By Project Name
$stmt = $con->prepare("SELECT pname, count(*) FROM orderd GROUP BY pname");
$stmt->execute();
$projectsCount = $stmt->rowCount();
if ($projectsCount > 0) {
  $projects = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Leads in Table Orderd
if (isset($pn)) {
  // Group By Employee ID
  $where = isset($pn) && $pn !== 'all' ? " WHERE pname ='" . $pn . "' GROUP BY emp_id" : ' GROUP BY emp_id';
  $stmt = $con->prepare("SELECT orderd.emp_id, count(orderd.id) as count, admins.name FROM orderd LEFT JOIN admins ON orderd.emp_id = admins.id $where");
  $stmt->execute();
  $employee_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // Group By Shipping
  $where = isset($pn) && $pn !== 'all' ? " WHERE pname ='" . $pn . "' GROUP BY wod" : ' GROUP BY wod';
  $stmt = $con->prepare("SELECT wod, count(*) as count FROM orderd $where");
  $stmt->execute();
  $wod_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // Group By Network Ads
  $where = isset($pn) && $pn !== 'all' ? " WHERE pname ='" . $pn . "' GROUP BY db_id" : ' GROUP BY db_id';
  $stmt = $con->prepare("SELECT orderd.db_id, count(orderd.id) as count, databases_connections.id, databases_connections.network_ads FROM orderd LEFT JOIN databases_connections ON databases_connections.id = orderd.db_id $where");
  $stmt->execute();
  $db_group = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php if (!isset($_GET['pn'])) { ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white">
      <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
      <li class="breadcrumb-item active"><a href="approved.php">Approved</a></li>
    </ol>
  </nav>
<?php } else { ?>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white">
      <li class="breadcrumb-item"><a href="/index.php">Home</a></li>
      <li class="breadcrumb-item active"><a href="approved.php">Approved</a></li>
      <li class="breadcrumb-item" aria-current="page">
        <a href="approved.php?pn=<?php echo $pn ?>">
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
        <span class="info-box-text"><a href="approved.php?pn=all" style="text-decoration: none" class="<?php echo $_GET['pn'] === 'all' ? 'font-weight-bold' : ''; ?>">
            All</a></span>
        <span class="info-box-number"><?php echo $countOrderd ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <?php if (!empty($projects)) {
    foreach ($projects as $key => $value) { ?>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
          <span class="info-box-icon bg-success"><i class="far fa-check-circle"></i></span>

          <div class="info-box-content">
            <span class="info-box-text"><a href="approved.php?pn=<?php echo $key ?>" style="text-decoration: none" class="<?php echo $_GET['pn'] === $key ? 'font-weight-bold' : ''; ?>">
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

<!-- TABLE: DB APPROVED -->
<div class="card">
  <div class="card-header border-transparent">
    <h3 class="card-title">Approved</h3>

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
    <div class="float-right d-flex justify-content-around col-lg-3 col-md-12 col-sm-12">
      <button type="button" id="deleteAllButton" class="col-5 p-1 btn btn-outline-danger btn-sm" onclick="deleteAll('/delete.php', 'orderd','Delete selected orders','At least one order must be selected','All orders are safe')">
        <i class="fa fa-trash"></i> Delete
      </button>
      <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addCancelOrdersToEmpModal" class="col-5 p-1 btn btn-outline-success btn-sm">
        <i class="fa fa-edit"></i> Re assiagn
      </button>
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-center col-12 p-2">
      <form class="col-12" id="filter_approved">
        <div class="d-flex flex-wrap justify-content-between">
          <select class="form-group col-lg-4 col-md-12 col-sm-12" id="filter_emp">
            <option class="form-control" value=''>Employee</option>
            <?php if ($employee_group !== null) {
              foreach ($employee_group as $key => $value) { ?>
                <option class="form-control" value="<?php echo $value['emp_id'] === NULL ? 'None' : $value['emp_id'] ?>">
                  <?php echo $value['name'] === NULL ? 'None' : $value['name']; ?> <?php echo ' ( ' . $value['count'] . ' )' ?>
                </option>
              <?php } ?>
            <?php } ?>
          </select>
          <select class="form-group col-lg-4 col-md-12 col-sm-12" id="filter_ci">
            <option class="form-control" value="">City</option>
            <option value="Alexandria">Alexandria</option>
            <option value="Aswan">Aswan</option>
            <option value="Asyout">Asyout</option>
            <option value="Beheira">Beheira</option>
            <option value="Beni Suef">Beni Suef</option>
            <option value="Cairo">Cairo</option>
            <option value="Dakahlia">Dakahlia</option>
            <option value="Damietta">Damietta</option>
            <option value="Fayyum">Fayyum</option>
            <option value="Ghardqa">Ghardqa</option>
            <option value="Gharbia">Gharbia</option>
            <option value="Giza">Giza</option>
            <option value="Ismailia">Ismailia</option>
            <option value="Kafr El Sheikh">Kafr El Sheikh</option>
            <option value="Luxor">Luxor</option>
            <option value="Matruh">Matruh</option>
            <option value="Minya">Minya</option>
            <option value="Monufia">Monufia</option>
            <option value="New Valley">New Valley</option>
            <option value="North Sinai">North Sinai</option>
            <option value="Port Said">Port Said</option>
            <option value="Qalyubia">Qalyubia</option>
            <option value="Qena">Qena</option>
            <option value="Red Sea">Red Sea</option>
            <option value="Sharqia">Sharqia</option>
            <option value="Sohag">Sohag</option>
            <option value="South Sinai ">South Sinai</option>
            <option value="Suez">Suez</option>
            <option value="Sharm el sheikh">Sharm el sheikh</option>
          </select>
          <select class="form-group col-lg-4 col-md-12 col-sm-12" id="filter_wod">
            <option class="form-control" value=''>Shipping</option>
            <?php if ($wod_group !== null) {
              foreach ($wod_group as $key => $value) { ?>
                <option class="form-control" value="<?php echo $value['wod']; ?>">
                  <?php echo $value['wod']; ?> <?php echo ' ( ' . $value['count'] . ' )' ?>
                </option>
              <?php } ?>
            <?php } ?>
          </select>
          <select class="form-group col-lg-4 col-md-12 col-sm-12 mb-0" id="filter_db">
            <option class="form-control" value=''>Network ads</option>
            <?php if ($db_group !== null) {
              foreach ($db_group as $key => $value) { ?>
                <option class="form-control" value="<?php echo $value['db_id'] === NULL ? 'None' : $value['db_id']; ?>">
                  <?php echo $value['network_ads'] === NULL ? 'None' : $value['network_ads']; ?> <?php echo ' ( ' . $value['count'] . ' )' ?>
                </option>
              <?php } ?>
            <?php } ?>
          </select>
          <div id="reportrange" class="col-lg-4 col-md-12" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar text-primary"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down text-primary"></i>
          </div>
          <button type="submit" class="col-lg-4 btn btn-sm btn-block btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
        </div>
      </form>
    </div>
    <div class="table-responsive">
      <table id="table_id" class="table table-hover table-bordered table-responsive" style="width: 100%">
        <thead>
          <tr>
            <th class="text-center" style="max-width:10px">
              <input type="checkbox" class="checkedAll" />
            </th>
            <th style="min-width: 10px">Id</th>
            <th style="min-width: 150px">Name</th>
            <th style="min-width: 100px">Phone</th>
            <th style="min-width: 200px">Address</th>
            <th style="min-width: 100px">City</th>
            <th style="min-width: 200px">Products</th>
            <th style="min-width: 100px">Total</th>
            <th style="min-width: 200px">Comment</th>
            <th style="min-width: 150px">Employee</th>
            <th style="min-width: 100px">Network ads</th>
            <th style="min-width: 100px">Fingerprint</th>

            <th style="min-width: 100px">From</th>

            <th style="min-width: 80px">WOD</th>
            <th style="min-width: 100px">DOO</th>
            <th style="min-width: 100px">DOD</th>
            <th style="min-width: 150px">Added at</th>
            <th style="min-width: 150px">Approved at</th>
            <th style="min-width: 50px">Edit</th>
            <th style="min-width: 50px">History</th>
            <th style="min-width: 50px">Invoice</th>
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

<!-- START Edit ORDERD MODAL -->
<div class="modal fade" id="EditOrderdModal" tabindex="-1" role="dialog" aria-labelledby="EditOrderdModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-danger">
        <h5 class="modal-title text-capitalize" id="EditOrderdModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_edit">
        <div class="modal-body">
          <input type="hidden" hidden="true" id="id" required />

          <div class="form-group p-2">
            <label for="name">Name</label>
            <input type="text" id="name" class="form-control" placeholder="Type a Name" required dir="auto" />
          </div>

          <div class="form-group p-2">
            <label for="phone">Phone</label>
            <input type="text" id="phone" class="form-control" placeholder="Phone Number" dir="auto" required />
          </div>

          <div class="form-group p-2">
            <label for="address">Address</label>
            <input type="text" id="address" class="form-control" placeholder="address" dir="auto" required />
          </div>
          <div class="form-group p-2">
            <label class="font-weight-bold" for="city">City</label>
            <select id="city" class="form-control" required>
              <option value="Alexandria">Alexandria</option>
              <option value="Aswan">Aswan</option>
              <option value="Asyout">Asyout</option>
              <option value="Beheira">Beheira</option>
              <option value="Beni Suef">Beni Suef</option>
              <option value="Cairo">Cairo</option>
              <option value="Dakahlia">Dakahlia</option>
              <option value="Damietta">Damietta</option>
              <option value="Fayyum">Fayyum</option>
              <option value="Ghardqa">Ghardqa</option>
              <option value="Gharbia">Gharbia</option>
              <option value="Giza">Giza</option>
              <option value="Ismailia">Ismailia</option>
              <option value="Kafr El Sheikh">Kafr El Sheikh</option>
              <option value="Luxor">Luxor</option>
              <option value="Matruh">Matruh</option>
              <option value="Minya">Minya</option>
              <option value="Monufia">Monufia</option>
              <option value="New Valley">New Valley</option>
              <option value="North Sinai">North Sinai</option>
              <option value="Port Said">Port Said</option>
              <option value="Qalyubia">Qalyubia</option>
              <option value="Qena">Qena</option>
              <option value="Red Sea">Red Sea</option>
              <option value="Sharqia">Sharqia</option>
              <option value="Sohag">Sohag</option>
              <option value="South Sinai ">South Sinai</option>
              <option value="Suez">Suez</option>
            </select>
          </div>
          <div class="form-group p-2">
            <label for="doo">Date Of Order (mm/dd/yyy)</label>
            <input type="date" id="doo" class="form-control" required />
          </div>
          <div class="form-group p-2">
            <label for="dod">Date Of Delivery (mm/dd/yyy)</label>
            <input type="date" id="dod" class="form-control" required />
          </div>
          <div class="form-group p-2">
            <label for="prname">Product</label>
            <input type="text" id="prname" class="form-control" dir="auto" required />
          </div>
          <div class="row col-12">
            <div class="form-group col-4">
              <label for="prpieces">Quantity</label>
              <input class="form-control col-12 prpieces" id="prpieces" type="number" step="1" min="1" placeholder="Pieces" required />
            </div>
            <div class="form-group col-4">
              <label for="prprice">Total</label>
              <input class="form-control col-12 prprice" id="prprice" type="number" step="0.25" min="0" placeholder="Price" required />
            </div>
            <div class="form-group col-4">
              <label for="prcurrency">Currency</label>
              <select class="form-control pb-0 prcurrency" id="prcurrency" required>
                <option value="USD" selected>USD</option>
                <option value="EGP">EGP</option>
                <option value="SAR">SAR</option>
                <option value="KWD">KWD</option>
                <option value="AED">AED</option>
              </select>
            </div>
          </div>
          <div class="form-group col-12">
            <label for="wod">Shipping</label>
            <select class="form-control pb-0 wod" id="wod">
              <option value="none">None</option>
              <option value="express">Express</option>
            </select>
          </div>
          <div class="form-group col-12">
            <label for="order-comment">Comment</label>
            <textarea type="text" id="order-comment" class="form-control col-12 order-comment" placeholder="Add Comment" dir="auto"></textarea>
          </div>
        </div>
        <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-sm btn-success save">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END Edit ORDERD MODAL -->

<!-- Start Re assgin Orders To Employee -->
<!-- Add Employee To Leads Modal -->
<div class="modal fade" id="addCancelOrdersToEmpModal" tabindex="-1" role="dialog" aria-labelledby="addCancelOrdersToEmpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-danger">
        <h5 class="modal-title text-capitalize" id="addCancelOrdersToEmpModalLabel">Select Employee</h5><br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" value="<?php echo $_SESSION['id']; ?>" id="admin_name">
        <div class="form-group col-12">
          <label for="status">Status</label>
          <select class="form-control col-12 pb-0" id="status" required>
            <option value="none">None</option>
            <option value="dod">DOD</option>
            <option value="not answer">Not Answer</option>
            <option value="call again">Call Again</option>
          </select>
        </div>
        <div class="form-group col-12">
          <label for="employee">Employee</label>
          <select class="form-control col-12 pb-0" id="employee" required style="width:100%">
            <option value="null">Null</option>}
            <?php
            // Employees Select Box
            $stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
            $stmt->execute();
            $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($employes as $emp) { ?>
              <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <div class="form-group col-12 d-flex justify-content-between">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
          <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateOrders('re_assign.php','orderd','Once Assaign Employee, This will disappearing!','You must choose any lead/leads','Your Approved Order Not Changed!')">
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Re assgin Orders To Employee -->

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
  document.title = 'HealthyCURE | Approved';
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
    getRecords('fetch_approved.php')

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

      getRecords('fetch_approved.php', emp_id, '', date_first, date_last, city, shipping, '#table_id', network)
      // }
    })

    // Edit Order 
    $(document).on('click', '#edit_approved', function() {
      const editOrderdheaderModal = document.querySelector('#EditOrderdModalLabel');
      var id = $(this).data('id');
      var name = $(this).data('name');
      var phone = $(this).data('phone');
      var add = $(this).data('add');
      var city = $(this).data('city');
      var prn = $(this).data('prn');
      var prp = $(this).data('prp');
      var prpi = $(this).data('prpi');
      var prc = $(this).data('prc');
      var emp_id = $(this).data('emp');
      var doo = $(this).data('doo');
      var dod = $(this).data('dod');
      var wod = $(this).data('wod');
      var com = $(this).data('com');

      editOrderdheaderModal.textContent = 'Edit Order ' + name;

      $('#id').val(id);
      $('#name').val(name);
      $('#phone').val(phone);
      $('#address').val(add);
      $('#city').val(city);
      $('#prname').val(prn);
      $('#prpieces').val(prpi);
      $('#prprice').val(prp);
      $('#prcurrency').val(prc);
      $('#doo').val(doo);
      $('#dod').val(dod);
      $('#wod').val(wod);
      $('#order-comment').val(com);

      const formEditOrder = $('#form_edit');

      if (formEditOrder) {
        formEditOrder.on('submit', function(e) {
          e.preventDefault();
          swal.fire({
              title: "Are you sure?",
              text: "To Edit This Order!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willEdit) => {
              if (willEdit.value === true) {
                var id = $('#id').val();
                var name = $('#name').val();
                var phone = $('#phone').val();
                var address = $('#address').val();
                var city = $('#city').val();
                var prname = $('#prname').val();
                var prpieces = $('#prpieces').val();
                var prprice = $('#prprice').val();
                var prcurrency = $('#prcurrency').val();
                var doo = $('#doo').val();
                var dod = $('#dod').val();
                var wod = $('#wod').val();
                var com = $('#order-comment').val();

                const formData = new FormData();

                formData.append('edit_id', id);
                formData.append('name', name);
                formData.append('phone', phone);
                formData.append('address', address);
                formData.append('city', city);
                formData.append('prname', prname);
                formData.append('prprice', prprice);
                formData.append('prpieces', prpieces);
                formData.append('prcurrency', prcurrency);
                formData.append('doo', doo);
                formData.append('dod', dod);
                formData.append('wayoforder', wod);
                formData.append('comment', com);

                fetch('orderd.php', {
                  method: 'POST',
                  body: formData
                }).then(res => {
                  return res.json();
                }).then(response => {
                  if (response.text === true) {
                    $('#EditOrderdModal').modal('hide')
                    $('#table_id').DataTable().ajax.reload(null, false)
                    swal.fire("Edit Done!", '', "success");
                  } else {
                    swal.fire("Something wronge!", '', "error");
                  }
                })
              } else {
                swal.fire("Your order is safe!", '', "warning");
              }
            });
        });
      }
    })
    $('#employee').select2({
      dropdownParent: $('#addCancelOrdersToEmpModal')
    });
  });
</script>
</body>

</html>