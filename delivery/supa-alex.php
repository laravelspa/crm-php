<?php
    ob_start();
    session_start();
    include('../main/database.php');
    $perArray = [
        '4' => 'Assistant',
        '5' => 'Delivery Man'
    ];    
    $sessionName        = $_SESSION['username'];
    $sessionPermission  = $_SESSION['permission'];
    $sessionId          = $_SESSION['id'];
    
    if($sessionPermission !== '4') {
        header('Location: ../login.php');
    } else {
        if(isset($sessionName) && isset($sessionPermission) && isset($sessionId)) {
            $stmt = $con->prepare("SELECT id, name, permission FROM admins WHERE supervisor = $sessionId AND permission = 5 AND location = 1");
            $stmt->execute();
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
             header('Location: ../login.php');
        }
    }
?>
<?php include('header.php') ?>    
<diV class="container mt-3">
    <div class="d-flex flex-wrap align-items-center justify-content-center mb-2 col-12 bg-white p-2">
        <div class="d-flex justify-content-around bg-white col-lg-4 col-md-12 col-sm-12 mb-2">
            <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addEmpToLeadModal" class="col-5 p-1 btn btn-outline-success btn-sm">
                <i class="fa fa-edit"></i> Re assiagn
            </button>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 text-center">
            <form class="col-12" id="filter_alex">
                <div class="d-lg-flex d-md-block justify-content-lg-between">
                    <div id="reportrange" class="col-lg-6" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar text-primary"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down text-primary"></i>
                    </div>
                    <select class="form-control col-lg-5 col-sm-12 pb-0" id="filter_emp" style="width:100%">
                        <option class="form-control" value="">Employee</option>
                        <?php if(!is_null($employees)) { 
                            foreach($employees as $emp) { ?>
                            <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'].' - '. $perArray[$emp['permission']]; ?></option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-1">
                    <div  class="col-lg-12 col-sm-12">
                        <button type="submit" class="btn btn-sm btn-block btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="stage-tab" data-toggle="tab" href="#stage" role="tab" aria-controls="stage-delivery" aria-selected="true">Stage</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="processing-tab" data-toggle="tab" href="#processing" role="tab" aria-controls="processing-delivery" aria-selected="true">Processing</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved-delivery" aria-selected="false">Approved</a>
                  </li> 
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="stage" role="tabpanel" aria-labelledby="stages-tab">
                        <table id="stage_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="max-width:10px">
                                        <input type="checkbox" class="checkedAll" id="checkedListstage" />  
                                    </th>
                                    <th class="text-center" style="min-width: 150px">Type</th>
                                    <th class="text-center" style="min-width: 150px">Date of delivery</th>
                                    <th class="text-center" style="min-width: 150px">Date of time</th>
                                    <th class="text-center" style="min-width: 150px">Name</th>
                                    <th class="text-center" style="min-width: 100px">Phone</th>
                                    <th class="text-center" style="min-width: 200px">Address</th>
                                    <th class="text-center" style="min-width: 100px">City</th>
                                    <th class="text-center" style="min-width: 200px">Comment (sales)</th>
                                    <th class="text-center" style="min-width: 150px">Delivery (Call)</th>
                                    <th class="text-center" style="min-width: 200px">Comment (delivery)</th>
                                </tr>
                            </thead>
                            <tbody></tbody> 
                        </table>      
                    </div>
                    <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="process-tab">
                        <table id="processing_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="max-width:10px">
                                        <input type="checkbox" class="checkedAll" id="checkedListprocessing" />  
                                    </th>
                                    <th class="text-center" style="min-width: 150px">Type</th>
                                    <th class="text-center" style="min-width: 150px">Delivery Man</th>
                                    <th class="text-center" style="min-width: 150px">Date of delivery</th>
                                    <th class="text-center" style="min-width: 150px">Date of time</th>
                                    <th class="text-center" style="min-width: 150px">Name</th>
                                    <th class="text-center" style="min-width: 100px">Phone</th>
                                    <th class="text-center" style="min-width: 200px">Address</th>
                                    <th class="text-center" style="min-width: 100px">City</th>
                                    <th class="text-center" style="min-width: 200px">Comment (sales)</th>
                                    <th class="text-center" style="min-width: 150px">Delivery (Call)</th>
                                    <th class="text-center" style="min-width: 200px">Comment (delivery)</th>
                                </tr>
                            </thead>
                            <tbody></tbody> 
                        </table>      
                    </div>
                    <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approve-tab">
                        <table id="approved_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="max-width:10px">
                                        <input type="checkbox" class="checkedAll" id="checkedListready" />  
                                    </th>
                                    <th class="text-center" style="min-width: 150px">Type</th>
                                    <th class="text-center" style="min-width: 150px">Approved by me</th>
                                    <th class="text-center" style="min-width: 150px">Approved by delegate</th>
                                    <th class="text-center" style="min-width: 100px">Delivery date</th>
                                    <th class="text-center" style="min-width: 100px">Delivery time</th>
                                    <th class="text-center" style="min-width: 150px">Name</th>
                                    <th class="text-center" style="min-width: 100px">Phone</th>
                                    <th class="text-center" style="min-width: 200px">Address</th>
                                    <th class="text-center" style="min-width: 40px">City</th>
                                    <th class="text-center" style="min-width: 200px">Comment(delivery)</th>
                                </tr>
                            </thead>
                            <tbody></tbody> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Choose Employee -->
    <div class="modal fade" id="addEmpToLeadModal" tabindex="-1" role="dialog" aria-labelledby="addEmpToLeadModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-danger" >
            <h5 class="modal-title text-capitalize" id="addEmpToLeadModalLabel">Select Delivery Man</h5><br>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12">
                        <select class="form-control col-12 pb-0" id="delivery_man" required style="width:100%">
                            <?php if(!is_null($employees)) { 
                                    foreach($employees as $emp) { ?>
                                <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="direction:ltr">
                <div class="form-group col-12 d-flex justify-content-between">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('re_assign.php', 'orderd_delivery', 'Orders re assiagn to this delivery man','You must choose any order', 'Orders not re assiagn to this delivery man','not null')">Save</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</diV>

<!-- START INOVICE MODAL -->
<div class="modal fade" id="InvoiceModal" tabindex="-1" role="dialog" aria-labelledby="InvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-danger">
                <h5 class="modal-title text-capitalize font-weight-bold" id="InvoiceModalLabel">Invoice order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="invoice_wrapper"></div>
            <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                <button id="invoice_print" class="btn btn-sm btn-success">Image</button>
                <button id="invoice_pdf" class="btn btn-sm btn-success">PDF</button>
            </div>
        </div>
    </div>
</div>
<!-- END INOVICE MODAL -->
<?php include('footer.php') ?>
<script src="/js/delivery-supaalex.js"></script>
<script src="../js/dom-to-image.min.js"></script>
<script src="../js/jspdf.min.js"></script>
<script src="../js/jsBarcode.min.js"></script>
<script src="../js/invoice.js"></script>
</body>
</html>