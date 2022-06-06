<?php
    ob_start();
    session_start();
    include('../main/database.php');
    
    $sessionName        = $_SESSION['username'];
    $sessionPermission  = $_SESSION['permission'];
    $sessionId          = $_SESSION['id'];
    
    if(isset($sessionName) && isset($sessionPermission) && isset($sessionId)) {
        $stmt = $con->prepare("SELECT id, name FROM admins WHERE id = $sessionId OR supervisor = $sessionId");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
         header('Location: ../login.php');
    }
?>
<?php include('header.php') ?>    
<div class="d-flex flex-wrap align-items-center justify-content-center mb-2 col-12 bg-white p-2">
    <?php if($sessionPermission === '1') { ?>
    <div class="d-flex justify-content-around bg-white col-lg-4 col-md-12 col-sm-12 mb-2">
        <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addEmpToLeadModal" class="col-5 p-1 btn btn-outline-success btn-sm">
            <i class="fa fa-edit"></i> Re assiagn
        </button>
    </div>
    <?php } ?>
    <div class="<?php echo $sessionPermission === '1' ? 'col-lg-8' : 'col-lg-12' ?> col-md-12 col-sm-12 text-center">
        <form class="col-12" id="filter_ca">
            <div class="d-lg-flex d-md-block justify-content-lg-between">
                <select class="form-group <?php echo $sessionPermission === '1' ? 'col-lg-6' : 'col-lg-4' ?> col-md-12 col-sm-12" id="filter_city">
                    <option class="form-control" value="">City</option>
                    <option value="Alexandria">Alexandria</option>
                    <option value="Cairo">Cairo</option>
                </select>
                <div id="reportrange" class="<?php echo $sessionPermission === '1' ? 'col-lg-6' : 'col-lg-4' ?>" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar text-primary"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down text-primary"></i>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <?php if($sessionPermission === '1') { ?>
                <select class="form-group col-lg-6 col-sm-12 pb-0" id="filter_emp" style="width:100%">
                    <option class="form-control" value="">Employee</option>
                    <?php if(!is_null($employees)) { 
                        foreach($employees as $emp) { ?>
                        <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                    <?php } } ?>
                </select>
                <?php } ?>
                <div  class="<?php echo $sessionPermission === '1' ? 'col-lg-6' : 'col-lg-12 d-flex justify-content-center' ?> col-sm-12">
                    <button type="submit" class="btn btn-sm btn-block btn-outline-primary <?php echo $sessionPermission === '1' ? 'col-lg-12' : 'col-lg-6' ?>"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="col-12">
    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <?php if($sessionPermission === '1') { ?>
              <li class="nav-item">
                <a class="nav-link active" id="stage-tab" data-toggle="tab" href="#stage" role="tab" aria-controls="stage-delivery" aria-selected="true">Stage</a>
              </li>
              <?php } ?>
              <li class="nav-item">
                <a class="nav-link <?php echo $sessionPermission == '6' ? 'active' : ''; ?>" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new-delivery" aria-selected="true">New</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab" aria-controls="waiting-delivery" aria-selected="false">Waiting Call</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="ready-tab" data-toggle="tab" href="#ready" role="tab" aria-controls="ready-delivery" aria-selected="false">Sending</a>
              </li>
              <?php if($sessionPermission === '1') { ?>
              <li class="nav-item">
                <a class="nav-link" id="finish-tab" data-toggle="tab" href="#finish" role="tab" aria-controls="finish-delivery" aria-selected="false">Finish</a>
              </li>
              <?php } ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <?php if($sessionPermission === '1') { ?>
                <div class="tab-pane fade show active" id="stage" role="tabpanel" aria-labelledby="stages-tab">
                    <table id="stage_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="max-width:10px">
                                    <input type="checkbox" class="checkedAll" id="checkedListstage" />  
                                </th>
                                <th class="text-center" style="min-width: 150px">Type</th>
                                <th class="text-center" style="min-width: 150px">Date of delivery</th>
                                <th class="text-center" style="min-width: 150px">Time of delivery</th>
                                <th class="text-center" style="min-width: 150px">Name</th>
                                <th class="text-center" style="min-width: 100px">Phone</th>
                                <th class="text-center" style="min-width: 200px">Address</th>
                                <th class="text-center" style="min-width: 100px">City</th>
                                <th class="text-center" style="min-width: 100px">Sales</th>
                                <th class="text-center" style="min-width: 200px">Sales Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>      
                </div>
                <?php } ?>
                <div class="tab-pane fade show <?php echo $sessionPermission == '6' ? 'active' : ''; ?>" id="new" role="tabpanel" aria-labelledby="news-tab">
                    <table id="new_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <?php if($sessionPermission === '1') { ?>
                                <th class="text-center" style="max-width:10px">
                                    <input type="checkbox" class="checkedAll" id="checkedListnew" />  
                                </th>
                                <?php } ?>
                                <th class="text-center" style="min-width: 150px">Actions</th>
                                <th class="text-center" style="min-width: 150px">Type</th>
                                <th class="text-center" style="min-width: 150px">Date of delivery</th>
                                <th class="text-center" style="min-width: 150px">Employee</th>
                                <th class="text-center" style="min-width: 150px">Name</th>
                                <th class="text-center" style="min-width: 100px">Phone</th>
                                <th class="text-center" style="min-width: 200px">Address</th>
                                <th class="text-center" style="min-width: 100px">City</th>
                                <th class="text-center" style="min-width: 200px">Sales</th>
                                <th class="text-center" style="min-width: 200px">Sales Comment</th>
                                <th class="text-center" style="min-width: 200px">My Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>      
                </div>
                <div class="tab-pane fade" id="waiting" role="tabpanel" aria-labelledby="waiting-tab">
                    <table id="waiting_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <?php if($sessionPermission === '1') { ?>
                                <th class="text-center" style="max-width:10px">
                                    <input type="checkbox" class="checkedAll" id="checkedListwaiting" />  
                                </th>
                                <?php } ?>
                                <th class="text-center" style="min-width: 150px">Actions</th>
                                <th class="text-center" style="min-width: 150px">Type</th>
                                <th class="text-center" style="min-width: 100px">Delivery date</th>
                                <th class="text-center" style="min-width: 100px">Delivery time</th>
                                <th class="text-center" style="min-width: 150px">Name</th>
                                <th class="text-center" style="min-width: 100px">Phone</th>
                                <th class="text-center" style="min-width: 200px">Address</th>
                                <th class="text-center" style="min-width: 40px">City</th>
                                <th class="text-center" style="min-width: 150px">Sales Comment</th>
                                <th class="text-center" style="min-width: 150px">My Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>
                </div>
                <div class="tab-pane fade" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                    <table id="ready_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <?php if($sessionPermission === '1') { ?>
                                <th class="text-center" style="max-width:10px">
                                    <input type="checkbox" class="checkedAll" id="checkedListready" />  
                                </th>
                                <?php } ?>
                                <th class="text-center" style="min-width: 150px">Type</th>
                                <th class="text-center" style="min-width: 100px">Delivery date</th>
                                <th class="text-center" style="min-width: 100px">Delivery time</th>
                                <th class="text-center" style="min-width: 150px">Employee</th>
                                <th class="text-center" style="min-width: 150px">Name</th>
                                <th class="text-center" style="min-width: 100px">Phone</th>
                                <th class="text-center" style="min-width: 200px">Address</th>
                                <th class="text-center" style="min-width: 40px">City</th>
                                <th class="text-center" style="min-width: 150px">Sales Comment</th>
                                <th class="text-center" style="min-width: 150px">My Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>
                </div>
                <?php if($sessionPermission === '1') { ?>
                <div class="tab-pane fade" id="finish" role="tabpanel" aria-labelledby="finish-tab">
                    <table id="finish_orders" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="max-width:10px">
                                    <input type="checkbox" class="checkedAll" id="checkedListfinish" />  
                                </th>
                                <th style="min-width: 80px">Type</th>
                                <th style="min-width: 80px">Delivered</th>
                                <th style="min-width: 100px">Delivery date</th>
                                <th style="min-width: 100px">Delivery time</th>
                                <th class="text-center" style="min-width: 150px">Employee</th>
                                <th style="min-width: 150px">Name</th>
                                <th style="min-width: 100px">Phone</th>
                                <th style="min-width: 100px">Address</th>
                                <th style="min-width: 80px">City</th>
                                <th style="min-width: 100px">Delivery Comment</th>
                            </tr>
                        </thead>
                        <tbody></tbody> 
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
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
      <div class="modal-body">
            <div class="row">
                <div class="form-group col-12">
                    <label for="employee">Select Employee</label>
                    <select class="form-control col-12 pb-0" id="employee" required style="width:100%">
                        <?php if(!is_null($employees)) { 
                                foreach($employees as $emp) { ?>
                            <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="form-group col-12">
                    <label for="employee">Send To</label>
                    <select class="form-control col-12 pb-0" id="d_status" required style="width:100%">
                        <option value="4">New</option>
                        <option value="5">Waiting</option>
                        <option value="6">Send</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="direction:ltr">
            <div class="form-group col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('re_assign.php', 'orderd_delivery', 'Orders re assiagn to this employee','You must choose any order', 'Orders not re assiagn to this employee','not null')">Save</button>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- START WAITING MODAL -->
<div class="modal fade" id="WaitingModal" tabindex="-1" role="dialog" aria-labelledby="waitingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="waiting_form">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="waitingModalLabel">Waiting call order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                      <a class="btn btn-sm btn-default" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Details <i class="fa fa-eye"></i>
                      </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                           <label class="font-weight-bold"><span class="text-primary">Name:</span> <span id="name"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Phone:</span> <span id="phone"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Address:</span> <span id="address"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">City:</span> <span id="city"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Product:</span></label><ul id="prname"></ul> 
                           <label class="font-weight-bold"><span class="text-primary">Date of order:</span>  <span id="doo"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Date of delivery(Sales):</span> <span id="dod"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Sales Comment:</span> <span id="order-comment"></span></label>
                        </div>
                    </div>
                    <div class="pending-comment-container">
                        <div class="d-flex">
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="waiting-date">Date</label>
                                <div class='input-group date' id='datetimepicker3'>
                                    <input type="text" id="waiting-date" class="form-control col-12 waiting-date" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-calendar-alt p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="waiting-time">Time</label>
                                <div class='input-group date' id='datetimepicker4'>
                                    <input type="text" id="waiting-time" class="form-control col-12 waiting-time" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-clock p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>    
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="waiting-comment">Comment</label>
                            <textarea type="text" id="waiting-comment" class="form-control col-12 waiting-comment" placeholder="Add Comment" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success Waiting">Waiting</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END WAITING MODAL -->
<!-- START READY MODAL -->
<div class="modal fade" id="ReadyModal" tabindex="-1" role="dialog" aria-labelledby="readyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="ready_form">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="raedyModalLabel">Send order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                      <a class="btn btn-sm btn-default" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Details <i class="fa fa-eye"></i>
                      </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                           <label class="font-weight-bold"><span class="text-primary">Name:</span> <span id="rname"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Phone:</span> <span id="rphone"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Address:</span> <span id="raddress"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">City:</span> <span id="rcity"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Product:</span></label><ul id="rprname"></ul>  
                           <label class="font-weight-bold"><span class="text-primary">Date of order:</span>  <span id="rdoo"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Date of delivery(Sales):</span> <span id="rdod"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Sales Comment:</span> <span id="sales-comment"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Date of delivery:</span> <span id="rdodd"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Time of delivery:</span> <span id="rtod"></span></label>
                        </div>
                    </div>
                    <input type="hidden" hidden="true" id="rorderd_id" required />
                    <input type="hidden" hidden="true" id="rstatus" required />
                    <div class="pending-comment-container">
                        <div class="d-flex">
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="send-date">Date</label>
                                <div class='input-group date' id='datetimepicker5'>
                                    <input type="text" id="send-date" class="form-control col-12 send-date" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-calendar-alt p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="send-time">Time</label>
                                <div class='input-group date' id='datetimepicker6'>
                                    <input type="text" id="send-time" class="form-control col-12 send-time" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-clock p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>    
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="ready-comment">Comment</label>
                            <textarea type="text" id="ready-comment" class="form-control col-12 ready-comment" placeholder="Add Comment" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success Ready">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END READY MODAL -->
<!-- START CODE MODAL -->
<div class="modal fade" id="CodeModal" tabindex="-1" role="dialog" aria-labelledby="codeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="code_form">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="codeModalLabel">Code order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                      <a class="btn btn-sm btn-default" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Details <i class="fa fa-eye"></i>
                      </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                           <label class="font-weight-bold"><span class="text-primary">Name:</span> <span id="cname"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Phone:</span> <span id="cphone"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Address:</span> <span id="caddress"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">City:</span> <span id="ccity"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Product:</span> <span id="cprname"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Quantity:</span> <span id="cprpieces"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Total:</span> <span id="cprprice"></span> <span id="cprcurrency"></span></label> 
                           <label class="font-weight-bold"><span class="text-primary">Date of order:</span>  <span id="cdoo"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Date of delivery(Sales):</span> <span id="cdod"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Sales Comment:</span> <span id="csales-comment"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Date of delivery:</span> <span id="cdodd"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Time of delivery:</span> <span id="ctod"></span></label>
                           <label class="font-weight-bold"><span class="text-primary">Delivery Comment:</span> <span id="cdelivery-comment"></span></label>
                        </div>
                    </div>
                    <div class="pending-comment-container">
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="add-code">Add Code</label>
                            <textarea type="text" id="add-code" class="form-control col-12 add-code" placeholder="Add Code" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END CODE MODAL -->

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
<script src="../js/delivery_ca.js"></script>
<script src="../js/dom-to-image.min.js"></script>
<script src="../js/jspdf.min.js"></script>
<script src="../js/jsBarcode.min.js"></script>
<script src="../js/invoice.js"></script>
</body>
</html>