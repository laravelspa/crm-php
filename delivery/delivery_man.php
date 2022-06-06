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
    if($sessionPermission !== '5') {
        header('Location: ../login.php');
    } else {
        if(isset($sessionName) && isset($sessionPermission) && isset($sessionId)) {
            $stmt = $con->prepare("SELECT id, name, permission FROM admins WHERE supervisor = $sessionId AND permission = 4");
            $stmt->execute();
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
             header('Location: ../login.php');
        }
    }
?>
<?php include('header.php') ?>    
<div class="d-flex flex-wrap align-items-center justify-content-center mb-2 col-12 bg-white p-2">
    <div class="col-lg-8 col-md-12 col-sm-12 text-center">
        <form class="col-12" id="filter_delivery">
            <div class="d-lg-flex d-md-block justify-content-lg-between">
                <div id="reportrange" class="col-lg-6" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar text-primary"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down text-primary"></i>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <button type="submit" class="btn btn-sm btn-block btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="col-12">
    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new-delivery" aria-selected="true">New</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="finish-tab" data-toggle="tab" href="#finish" role="tab" aria-controls="finish-delivery" aria-selected="true">Finish</a>
              </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="news-tab">
                    <table id="new_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="min-width: 150px">Approved</th>
                                <th class="text-center" style="min-width: 150px">Update</th>
                                <th class="text-center" style="min-width: 150px">Type</th>
                                <th class="text-center" style="min-width: 150px">Date of delivery</th>
                                <th class="text-center" style="min-width: 150px">Date of time</th>
                                <th class="text-center" style="min-width: 150px">Name</th>
                                <th class="text-center" style="min-width: 100px">Phone</th>
                                <th class="text-center" style="min-width: 200px">Address</th>
                                <th class="text-center" style="min-width: 100px">City</th>
                                <th class="text-center" style="min-width: 150px">Delivery (Call)</th>
                                <th class="text-center" style="min-width: 200px">Delivery Comment</th>
                                <th class="text-center" style="min-width: 200px">Sales Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>      
                </div>
                <div class="tab-pane fade" id="finish" role="tabpanel" aria-labelledby="finish-tab">
                    <table id="finish_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="min-width: 150px">Approved</th>
                                <th class="text-center" style="min-width: 150px">Type</th>
                                <th class="text-center" style="min-width: 150px">Date of delivery</th>
                                <th class="text-center" style="min-width: 150px">Date of time</th>
                                <th class="text-center" style="min-width: 150px">Name</th>
                                <th class="text-center" style="min-width: 100px">Phone</th>
                                <th class="text-center" style="min-width: 200px">Address</th>
                                <th class="text-center" style="min-width: 100px">City</th>
                                <th class="text-center" style="min-width: 150px">Delivery (Call)</th>
                                <th class="text-center" style="min-width: 200px">Delivery Comment</th>
                                <th class="text-center" style="min-width: 200px">Sales Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>      
                </div>
            </div>
        </div>
    </div>
</div>
<!-- START UPDATE MODAL -->
<div class="modal fade" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="delivery_form">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="waitingModalLabel">Update Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="form-group col-6">
                            <label class="font-weight-bold" for="delivery-date">Date</label>
                            <div class='input-group date' id='datetimepicker3'>
                                <input type="text" id="delivery-date" class="form-control col-12 delivery-date" required />
                                <span class="input-group-addon">
                                    <i class="fas fa-calendar-alt p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label class="font-weight-bold" for="delivery-time">Time</label>
                            <div class='input-group date' id='datetimepicker4'>
                                <input type="text" id="delivery-time" class="form-control col-12 delivery-time" required />
                                <span class="input-group-addon">
                                    <i class="fas fa-clock p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group col-12">
                        <label class="font-weight-bold" for="address">Update address</label>
                        <input type="text" id="address" class="form-control col-12 address" />
                    </div> -->
                    <div class="form-group col-12">
                        <label class="font-weight-bold" for="delivery-comment">Comment</label>
                        <textarea type="text" id="delivery-comment" class="form-control col-12 delivery-comment" placeholder="Add Comment" ></textarea>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success delivery">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END WAITING MODAL -->

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
<script src="/js/delivery_man.js"></script>
<script src="../js/dom-to-image.min.js"></script>
<script src="../js/jspdf.min.js"></script>
<script src="../js/jsBarcode.min.js"></script>
<script src="../js/invoice.js"></script>
</body>
</html>