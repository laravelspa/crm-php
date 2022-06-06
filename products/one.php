<?php
    ob_start();
    session_start();
    $_SESSION['project'] = $_GET['project'];  
    include('../main/header1.php'); 
    include('group.php'); ?>
        <!-- row -->
<div class="row">
    <div class="col-12">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title"><?php echo $_GET['project']; ?></h3>
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
            <div class="d-flex justify-content-center mb-2 col-12">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <form class="col-12" id="filter_form">
                        <div class="d-lg-flex d-md-block justify-content-lg-between">
                            <select class="form-group col-lg-3 col-md-12 col-sm-12" id="filter_emp">
                                <option class="form-control" value="">Employee</option>
                                <?php if($emp_group !== null) {
                                    foreach($emp_group as $key => $value) { ?>
                                    <option class="form-control" value="<?php echo $value['emp_id'] === NULL ? 'NULL' : $value['emp_id']; ?>">
                                    <?php echo $value['name'] === NULL ? 'Null' : $value['name']; ?> <?php echo ' ( '. $value['count'] . ' )' ?> 
                                    </option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                            <select class="form-group col-lg-3 col-md-12 col-sm-12" id="filter_st">
                                <option class="form-control" value="">Status</option>
                                <?php if($st_group !== null) { 
                                    foreach($st_group as $key => $value) { ?>
                                    <option class="form-control" value="<?php echo $value['status']; ?>">
                                        <?php echo $value['status'].' ( '.$value['count'].' )'; ?>
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
            </div>
            <div class="d-flex justify-content-around col-lg-3 col-md-12 col-sm-12 mb-2 float-right">
                <button type="button" id="deleteAllButton" class="btn btn-outline-danger btn-sm" onclick="deleteAll('/delete.php', 'pending','Delete selected orders','At least one order must be selected','All orders are safe')">
                    <i class="fa fa-trash"></i> Delete
                </button>
                <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addEmpToLeadModal" class="btn btn-outline-success btn-sm">
                    <i class="fa fa-edit"></i> Re assiagn
                </button>
            </div>
            <div class="table-responsive">
                <table id="table_id" class="table table-hover table-bordered table-responsive" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-center"style="max-width:10px">
                                <input type="checkbox" class="checkedAll" />  
                            </th>
                            <th style="min-width: 10px" class="text-center">id</th>
                            <th style="min-width: 150px" class="text-center">Name</th>
                            <th style="min-width: 100px" class="text-center">Phone</th>
                            <th style="min-width: 200px" class="text-center">Address</th>
                            <th style="min-width: 100px" class="text-center">Product</th>
                            <th style="min-width: 10px" class="text-center">Quantity</th>
                            <th style="min-width: 100px" class="text-center">Total</th>
                            <th style="min-width: 100px" class="text-center">Employee</th>
                            <th style="min-width: 100px" class="text-center">Date of order</th>
                            <th style="min-width: 100px" class="text-center">Status</th>
                            <th style="min-width: 200px" class="text-center">Created at</th>
                            <th style="min-width: 100px" class="text-center">History</th>
                        </tr>
                    </thead>
                    <tbody></tbody> 
                </table>
            </div>
          <!-- /.card-body -->
          </div>
        <!-- /.card -->
        </div>
    </div>
<!-- /.row -->
</div>
<!-- Choose Employee -->
<div class="modal fade" id="addEmpToLeadModal" tabindex="-1" role="dialog" aria-labelledby="addEmpToLeadModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-danger" >
        <h5 class="modal-title text-capitalize" id="addEmpToLeadModalLabel">Select Employee</h5><br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <small id="errorChecked" class="text-danger text-center"></small>
      <div class="modal-body">
            <div class="row">
                <div class="form-group col-12">
                    <select class="form-control col-12 pb-0" id="employee" required style="width:100%">
                        <option value="null">None</option>
                        <?php
                            // Employees Select Box
                            $stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
                            $stmt->execute();
                            $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach($employes as $emp) { ?>
                            <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        <div class="modal-footer" style="direction:ltr">
            <div class="form-group col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('/update.php', 'pending','selected leads re assiagn to this sales','At least one order must be selected','Your lead/leads is safe','not null')">Save</button>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="HistoryModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header text-danger" >
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
    getRecords('fetch_project.php');
    $(document).ready(function() {
        document.title = 'HealthyCURE | ' + "<?php echo $_GET['project']; ?>" ;
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
        
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            count++;
            if(count===1) {
                var emp_id = $('#filter_emp').val();
                var status = $('#filter_st').val();
                var date_first = startDate;
                var date_last = endDate;
                $('#table_id').DataTable().destroy();
                getRecords('fetch_project.php',emp_id,status,date_first,date_last);
            }
            count=0;
        })
        
        $('#employee').select2({
            dropdownParent: $('#addEmpToLeadModal')
        });
    });
    </script>
</body>
</html>