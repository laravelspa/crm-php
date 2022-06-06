<?php
ob_start();
session_start();
if (!isset($_SESSION['permission']) || $_SESSION['permission'] !== "2") {
    header("location: ../login.php");
    exit;
}
include('../main/database.php');

$sessionName        = $_SESSION['username'];
$sessionPermission  = $_SESSION['permission'];
$sessionId          = $_SESSION['id'];
$pn = $_SESSION['pn'] = $_GET['pn'];
if (isset($sessionName) && isset($sessionPermission) && isset($sessionId)) {
    $stmt = $con->prepare("SELECT pname, count(*) FROM pending WHERE emp_id ='" . $sessionId . "' GROUP BY pname");
    $stmt->execute();
    $countPro = $stmt->rowCount();
    if ($countPro > 0) {
        $projects = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    $stmt = $con->prepare("SELECT prname FROM pending GROUP BY prname");
    $stmt->execute();
    $pro_model = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: ../login.php');
}
?>
<?php include('header.php') ?>
<?php if ($countPro > 0) { ?>
    <!-- /.col -->
    <div class="d-flex flex-wrap">
        <?php foreach ($projects as $key => $value) { ?>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-check-circle"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><a href="employee.php?pn=<?php echo $key ?>" style="text-decoration: none" class="<?php echo $_GET['pn'] === $key ? 'font-weight-bold' : ''; ?>">
                                <?php echo $key ?></a></span>
                        <span class="info-box-number"><?php echo $value ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        <?php } ?>
    </div>
<?php } ?>
<div class="col-12">
    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#lead" role="tab" aria-controls="home" aria-selected="true">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#lead_dod" role="tab" aria-controls="profile" aria-selected="false">DOD</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="lead" role="tabpanel" aria-labelledby="home-tab">
                    <table id="my_leads" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 10px">id</th>
                                <th style="min-width: 150px">Name</th>
                                <th style="min-width: 100px">Phone</th>
                                <th style="min-width: 100px">Product</th>
                                <th style="min-width: 10px">Quantity</th>
                                <th style="min-width: 100px">Total</th>
                                <th style="min-width: 200px" class="text-center">Actions</th>
                                <th style="min-width: 100px">Status</th>
                                <th style="min-width: 150px">Comment</th>
                                <th style="min-width: 100px">Date</th>
                                <th style="min-width: 100px">Time</th>
                                <th style="min-width: 100px">Date of order</th>
                                <th style="min-width: 100px">Date of delivery</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="lead_dod" role="tabpanel" aria-labelledby="profile-tab">
                    <table id="my_leads_dod" class="table table-hover table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 10px">id</th>
                                <th style="min-width: 150px">Name</th>
                                <th style="min-width: 100px">Phone</th>
                                <th style="min-width: 200px">Address</th>
                                <th style="min-width: 100px">Product</th>
                                <th style="min-width: 10px">Quantity</th>
                                <th style="min-width: 100px">Total</th>
                                <th style="min-width: 150px" class="text-center">Actions</th>
                                <th style="min-width: 100px">Status</th>
                                <th style="min-width: 100px">Comment</th>
                                <th style="min-width: 100px">Date</th>
                                <th style="min-width: 100px">Time</th>
                                <th style="min-width: 100px">Date of order</th>
                                <th style="min-width: 100px">Date of delivery</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- START ORDERD MODAL -->
<div class="modal fade" id="orderdModal" tabindex="-1" role="dialog" aria-labelledby="orderdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <form name="orderd" method="post">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="orderdModalLabel">Orderd Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" hidden="true" class="id" required />
                    <input type="hidden" hidden="true" class="db_id" required />
                    <input type="hidden" hidden="true" class="emp_id" required />
                    <input type="hidden" hidden="true" class="name" required />
                    <input type="hidden" hidden="true" class="lead_from" required />
                    <input type="hidden" hidden="true" class="web_id" required />
                    <input type="hidden" hidden="true" class="added_at" required />

                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="phone">Phone</label>
                        <input type="text" id="phone" class="form-control phone" placeholder="address" required disabled />
                    </div>

                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="address">Address</label>
                        <input type="text" id="address" class="form-control address" placeholder="address" required />
                    </div>
                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="city">City</label>
                        <select id="city" class="form-control city" required style="width: 100%">
                            <option value="none" selected>None</option>
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
                    </div>
                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="doo">Date Of Order</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input id="doo" type='text' class="form-control doo" disabled />
                            <span class="input-group-addon">
                                <i class="fas fa-calendar-alt p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group p-2">
                        <div class="d-flex">
                            <div class="form-group col-8">
                                <label class="font-weight-bold" for="dod">Date Of Delivery</label>
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type="text" id="dod" class="form-control dod" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-calendar-alt p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="order-time">Time of order</label>
                                <div class='input-group date' id='datetimepicker4'>
                                    <input type="text" id="order-time" class="form-control col-12 order-time" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-clock p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="prname">Product</label>
                        <select class="form-control prname" required disabled>
                            <?php foreach ($pro_model as $value) { ?>
                                <option value="<?php echo $value['prname'] ?>"><?php echo $value['prname'] ?></option>
                            <?php } ?>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="row col-12">
                        <div class="form-group col-4">
                            <label class="font-weight-bold" for="prpieces">Quantity</label>
                            <input class="form-control col-12 prpieces" id="prpieces" type="number" step="1" min="1" placeholder="Pieces" required />
                        </div>
                        <div class="form-group col-4">
                            <label class="font-weight-bold" for="prprice">Total</label>
                            <input class="form-control col-12 prprice" id="prprice" type="number" step="0.25" min="0" placeholder="Price" required />
                        </div>
                        <div class="form-group col-4">
                            <label class="font-weight-bold" for="prcurrency">Currency</label>
                            <select class="form-control pb-0 prcurrency" id="prcurrency" required>
                                <option value="USD" selected>USD</option>
                                <option value="EGP">EGP</option>
                                <option value="SAR">SAR</option>
                                <option value="KWD">KWD</option>
                                <option value="AED">AED</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div>
                        <a href="#" class="add-another-1"><i class="fas fa-plus"></i> add second product</a>
                    </div> -->

                    <div class="col-12" id="add-another-1">
                        <div class="form-group p-2">
                            <label class="font-weight-bold" for="prname_1">Product</label>
                            <select class="form-control prname_1" disabled>
                                <?php foreach ($pro_model as $value) { ?>
                                    <option value="<?php echo $value['prname'] ?>"><?php echo $value['prname'] ?></option>
                                <?php } ?>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="row col-12">
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="prpieces_1">Quantity</label>
                                <input class="form-control col-12 prpieces_1" id="prpieces_!" type="number" step="1" min="1" placeholder="Pieces" />
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="prprice_1">Total</label>
                                <input class="form-control col-12 prprice_1" id="prprice_1" type="number" step="0.25" min="0" placeholder="Price" />
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="prcurrency_1">Currency</label>
                                <select class="form-control pb-0 prcurrency_1" id="prcurrency_1">
                                    <option value="USD" selected>USD</option>
                                    <option value="EGP">EGP</option>
                                    <option value="SAR">SAR</option>
                                    <option value="KWD">KWD</option>
                                    <option value="AED">AED</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div>
                            <a href="#" class="add-another-2"><i class="fas fa-plus"></i> add third product</a>
                        </div> -->
                    </div>
                    <div class="col-12" id="add-another-2">
                        <div class="form-group p-2">
                            <label class="font-weight-bold" for="prname_2">Product</label>
                            <select class="form-control prname_2" disabled>
                                <?php foreach ($pro_model as $value) { ?>
                                    <option value="<?php echo $value['prname'] ?>"><?php echo $value['prname'] ?></option>
                                <?php } ?>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="row col-12">
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="prpieces_2">Quantity</label>
                                <input class="form-control col-12 prpieces_2" id="prpieces_2" type="number" step="1" min="1" placeholder="Pieces" />
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="prprice_2">Total</label>
                                <input class="form-control col-12 prprice_2" id="prprice_2" type="number" step="0.25" min="0" placeholder="Price" />
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold" for="prcurrency_2">Currency</label>
                                <select class="form-control pb-0 prcurrency_2" id="prcurrency_2">
                                    <option value="USD" selected>USD</option>
                                    <option value="EGP">EGP</option>
                                    <option value="SAR">SAR</option>
                                    <option value="KWD">KWD</option>
                                    <option value="AED">AED</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label class="font-weight-bold" for="waydelivery">Shipping</label>
                        <select class="form-control pb-0 waydelivery" id="waydelivery">
                            <option value="none">None</option>
                            <option value="express">Express</option>
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <label class="font-weight-bold" for="order-comment">Comment</label>
                        <textarea type="text" id="order-comment" class="form-control col-12 order-comment" placeholder="Add Comment"></textarea>
                    </div>
                    <div class="form-group col-12 error-group">
                        <ul class="pl-0"></ul>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button name="dod_button" type="button" class="btn btn-sm btn-info dod_button">DOD</button>
                    <button type="submit" class="btn btn-sm btn-success approve">Approve</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END ORDERD MODAL -->
<!-- START CANCELD MODAL -->
<div class="modal fade" id="canceldModal" tabindex="-1" role="dialog" aria-labelledby="canceldModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="canceld" method="post">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="canceldModalLabel">Cancelation Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" hidden="true" class="id" required />
                    <input type="hidden" hidden="true" class="db_id" required />
                    <input type="hidden" hidden="true" class="name" required />
                    <input type="hidden" hidden="true" class="phone" required />
                    <input type="hidden" hidden="true" class="pname" required />
                    <input type="hidden" hidden="true" class="emp_id" required />
                    <input type="hidden" hidden="true" class="prname" required />
                    <input type="hidden" hidden="true" class="prprice" required />
                    <input type="hidden" hidden="true" class="prpieces" required />
                    <input type="hidden" hidden="true" class="prcurrency" required />
                    <input type="hidden" hidden="true" class="lead_from" required />
                    <input type="hidden" hidden="true" class="web_id" required />
                    <input type="hidden" hidden="true" class="added_at" required />

                    <div class="form-group col-12">
                        <label class="font-weight-bold" for="cancel-reason">Reason</label>
                        <select class="form-control pb-0 cancel-reason" id="cancel-reason" required style="width:100%">
                            <option value="invalid_phone_number">Invalid Phone Number</option>
                            <option value="fake_order">Fake Order</option>
                            <option value="expensive">Expensive</option>
                            <option value="changed_mind">Changed mind</option>
                            <option value="health_issues">Health Issues</option>
                            <option value="cannot_reach_client">Cannot Reach Client</option>
                            <option value="consultation">Consultation</option>

                            <option value="comment">Another reason</option>
                        </select>
                    </div>
                    <div class="form-group col-12 comment-container" style="display:none">
                        <label class="font-weight-bold" for="comment">Comment</label>
                        <textarea type="text" id="comment" class="form-control col-12 comment" placeholder="Add reason for cancellation"></textarea>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success cancel">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END CANCELD MODAL -->
<!-- START PENDING MODAL -->
<div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="pendingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="pending_form">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-capitalize font-weight-bold" id="pendingModalLabel">Pending Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" hidden="true" class="id" required />
                    <div class="form-group col-12">
                        <label class="font-weight-bold" for="pending-reason">Reason</label>
                        <select class="form-control pb-0 pending-reason" id="pending-reason" required>
                            <option value="not answer">Not answer</option>
                            <option value="call again">Call again</option>
                        </select>
                    </div>
                    <div class="pending-comment-container">
                        <div class="d-flex">
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="pending-date">Date</label>
                                <div class='input-group date' id='datetimepicker3'>
                                    <input type="text" id="pending_date" class="form-control col-12 pending-date" value="<?php echo date('Y-m-d'); ?>" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-calendar-alt p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="pending-time">Time</label>
                                <div class='input-group date' id='datetimepicker4'>
                                    <input type="text" id="pending-time" class="form-control col-12 pending-time" required />
                                    <span class="input-group-addon">
                                        <i class="fas fa-clock p-1 ml-1 text-warning" style="font-size:1.8rem"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="pending-comment">Comment</label>
                            <textarea type="text" id="pending-comment" class="form-control col-12 pending-comment" placeholder="Add Comment"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success pending">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END PENDING MODAL -->
<!-- History Modal -->
<div class="modal" id="HistoryModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="overlay d-flex justify-content-center align-items-center">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
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
<?php include('footer.php') ?>
<script>
    document.title = 'HealthyCURE | My Leads'
    $(document).ready(function() {
        $('#datetimepicker1').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datetimepicker2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datetimepicker3').datetimepicker({
            format: 'YYYY-MM-DD',
        });
        $('#datetimepicker4').datetimepicker({
            format: 'LT'
        });
        getRecordsForSales('#my_leads', 'fetch/fetch_my_leads.php');
        getRecordsForSales('#my_leads_dod', 'fetch/fetch_my_leads_dod.php');
        $("#add-another-1").hide();
        $("#add-another-2").hide();
    });
</script>
</body>

</html>