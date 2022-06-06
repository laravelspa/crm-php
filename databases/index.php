<?php
ob_start();
session_start();
if($_SESSION['permission'] !== '0') {
    header('Location: ../login.php');
}
include('../main/database.php');
include('../main/header1.php');
// Products In Select Box
$stmt = $con->prepare("SELECT prname FROM pending GROUP BY prname");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- TABLE: DB CONNECTIONS -->
<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Connections</h3>

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
        <div class="float-right">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#createDatabase">
                <i class="fas fa-plus"></i> New
            </button>
        </div>
        <div class="table-responsive">
            <table id="table_id" class="table table-hover table-bordered table-responsive" style="width: 100%">
                <thead>
                    <tr>
                        <th class="text-center" style="max-width:10px">
                            <input type="checkbox" class="checkedAll" />
                        </th>
                        <th style="min-width: 100px" class="text-center">DB Name</th>
                        <th style="min-width: 100px" class="text-center">Count</th>
                        <th style="min-width: 100px" class="text-center">DB Table</th>
                        <th style="min-width: 100px" class="text-center">Network Ads</th>
                        <th style="min-width: 100px" class="text-center">Landing URL</th>
                        <th style="min-width: 100px" class="text-center">User</th>
                        <th style="min-width: 100px" class="text-center">Product</th>
                        <th style="min-width: 100px" class="text-center">Price</th>
                        <th style="min-width: 150px" class="text-center">Comment</th>
                        <th style="min-width: 50px" class="text-center">Edit</th>
                        <th style="min-width: 50px" class="text-center">Delete</th>
                        <th style="min-width: 50px" class="text-center">Recovery</th>
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

<!-- Modal -->
<div class="modal fade" id="createDatabase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create_database">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="exampleModalLabel">New Connection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap bg-white">
                        <div class="form-group col-12">
                            <label for="dbname">Database Name</label>
                            <input class="form-control col-12" id="dbname" type="text" placeholder="Ex.healthyh_database_name" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="dbtable">Database Table</label>
                            <input class="form-control col-12" value="sales" id="dbtable" type="text" placeholder="Ex.sales" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="dbuser">Database User</label>
                            <input class="form-control col-12" value="healthy_cure" id="dbuser" type="text" placeholder="Ex.healthyh_username" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="dbuserpassword">User Password</label>
                            <input class="form-control col-12" value="healthycure112# " id="dbuserpassword" type="password" placeholder="Add User Password" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="network_ads">Network Ads</label>
                            <input class="form-control col-12" id="network_ads" type="text" placeholder="Ex.facebook ads" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="landing_url">Landing URL</label>
                            <input class="form-control col-12" id="landing_url" type="text" placeholder="Ex.https://google.com" required />
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="prname">Product <span class="text-danger">*</span></label>
                            <select class="form-control col-12" id="prname" required style="width:100%">
                                <?php foreach ($products as $product) { ?>
                                    <option value="<?php echo $product["prname"] ?>"><?php echo $product['prname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="prprice">Price
                                <span class="text-danger">*</span></label>
                            <input placeholder="Ex: 200" class="form-control col-12" id="prprice" type="number" step="0.25" min="0" required />
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="prcurrency">Currency
                                <span class="text-danger">*</span></label>
                            <select class="form-control pb-0  col-12" id="prcurrency" style="width:100%">
                                <option value="USD" selected>USD</option>
                                <option value="EGP">EGP</option>
                                <option value="SAR">SAR</option>
                                <option value="KWD">KWD</option>
                                <option value="AED">AED</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="comment">Comment</label>
                            <textarea class="form-control col-12" id="comment" type="text" placeholder="Add Comment If you Need"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                </div>
        </form>
    </div>
</div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editDatabaseModal" tabindex="-1" role="dialog" aria-labelledby="editDatabaseModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-danger">
                <h5 class="modal-title text-capitalize" id="editDatabaseModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit_database">
                    <input type="hidden" class="edit_id" required />
                    <div class="form-group p-2">
                        <label for="newdbname">Database Name</label>
                        <input type="text" placeholder="Ex.healthyh_database_name" class="newdbname form-control" id="newdbname" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="newdbtable">Database Table</label>
                        <input type="text" placeholder="Ex.sales" class="newdbtable form-control" id="newdbtable" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="newdbuser">Databse User</label>
                        <input type="text" placeholder="Ex.healthyh_username" class="newdbuser form-control" id="newdbuser" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="newdbuserpassword">User Password</label>
                        <input type="password" placeholder="Add User Password" class="newdbuserpassword form-control" id="newdbuserpassword" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="newnetwork_ads">Network Ads</label>
                        <input class="form-control col-12 newnetworkads" id="newnetworkads" type="text" placeholder="Ex.facebook ads" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="newlanding_url">Landing URL</label>
                        <input class="form-control col-12 newlandingurl" id="newlandingurl" type="text" placeholder="Ex.https://google.com" required />
                    </div>
                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="newprname">Product <span class="text-danger">*</span></label>
                        <select class="form-control col-12 newprname" id="newprname" required style="width:100%">
                            <?php foreach ($products as $product) { ?>
                                <option value="<?php echo $product['prname'] ?>"><?php echo $product['prname'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="newprprice">Price
                            <span class="text-danger">*</span></label>
                        <input placeholder="Ex: 200" class="form-control col-12 newprprice" id="newprprice" type="number" step="0.25" min="0" required />
                    </div>
                    <div class="form-group p-2">
                        <label class="font-weight-bold" for="newprcurrency">Currency <span class="text-danger">*</span></label>
                        <select class="form-control col-12 pb-0 newprcurrency" id="newprcurrency" required style="width:100%">
                            <option value="USD">USD</option>
                            <option value="EGP">EGP</option>
                            <option value="SAR">SAR</option>
                            <option value="KWD">KWD</option>
                            <option value="AED">AED</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="newcomment">Comment</label>
                        <textarea class="form-control col-12 newcomment" id="newcomment" type="text" placeholder="Add Comment If you Need"></textarea>
                    </div>
            </div>
            <div class="update_done"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-success save">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php include('../main/footer1.php'); ?>
<script src="../js/databases.js"></script>
<script>
    $('#prcurrency').select2({
        dropdownParent: $('#createDatabase')
    })
    $('#prname').select2({
        dropdownParent: $('#createDatabase')
    })
    $('#newprname').select2({
        dropdownParent: $('#editDatabaseModal')
    })
    
    $('#newprcurrency').select2({
        dropdownParent: $('#editDatabaseModal')
    })
</script>
</body>

</html>