<?php
ob_start();
session_start();
if ($_SESSION['permission'] !== '0') {
    header('Location: ../login.php');
}
include('main/header1.php');
include('main/database.php');
$emp_id = $_GET['emp_id'];
$table = $_GET['table'];
$name = $_GET['name'];
$status = $_GET['status'];

$stmt = $con->prepare("SELECT id,name FROM admins WHERE permission = '2'");
$stmt->execute();
$sales = $stmt->fetchall(PDO::FETCH_ASSOC);

// All Sales
$stmt = $con->prepare("SELECT name, id FROM admins WHERE permission = 2");
$stmt->execute();
$countSales = $stmt->rowCount();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count Orderd By emp_id
$stmt = $con->prepare("SELECT id FROM orderd WHERE emp_id = :emp_id");
$param = [
    'emp_id' => $emp_id
];
$stmt->execute($param);
$countOrderd = $stmt->rowCount();

if (isset($emp_id, $table, $name)) {
    $where = '';
    if (isset($status)) {
        $where = " AND status = '" . $status . "'";
    }

    $stmt = $con->prepare("SELECT * FROM $table WHERE emp_id = $emp_id $where $date");
    $stmt->execute();
    $countOrderd = $stmt->rowCount();
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['filter'])) {
        $df = $_GET['date_first'];
        $dl = $_GET['date_last'];
        if (isset($df, $dl) && $df != '' && $dl != '') {
            $column = $table === 'canceld' ? 'canceld_at' : 'created_at';
            $date = " AND DATE($column) BETWEEN '" . $df . "' AND '" . $dl . "'";
            $stmt = $con->prepare("SELECT * FROM $table WHERE emp_id = $emp_id $where $date");
            $stmt->execute();
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white mb-1">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active"><a href="track.php">Tracking</a></li>
        <?php if (isset($name)) { ?>
            <li class="breadcrumb-item" aria-current="page">
                <a href="track.php?name=<?php echo $name ?>&table=<?php echo $table ?>&emp_id=<?php echo $emp_id; ?>">
                    <?php echo $name; ?>
                </a>
            </li>
        <?php } ?>
        <?php if (isset($table)) { ?>
            <li class="breadcrumb-item" aria-current="page">
                <a href="track.php?table=<?php echo $table ?>&name=<?php echo $name ?>&emp_id=<?php echo $emp_id; ?>">
                    <?php echo $table; ?>
                </a>
            </li>
        <?php } ?>
    </ol>
</nav>
<?php if (isset($name)) { ?>
    <div class="d-flex flex-wrap justify-content-between">
        <div class="col-lg-2 col-md-6 col-sm-12 text-center p-2 bg-white">
            <a href="track.php?emp_id=<?php echo $emp_id ?>&table=orderd&name=<?php echo $name; ?>" style="text-decoration: none">Orderd <span class="badge badge-fill badge-danger"><?php if ($table === 'orderd') {
                                                                                                                                                                                            echo $countOrderd;
                                                                                                                                                                                        } ?></span></a>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 text-center p-2 bg-white">
            <a href="track.php?emp_id=<?php echo $emp_id ?>&table=pending&name=<?php echo $name; ?>&status=dod" style="text-decoration: none">DOD <span class="badge badge-fill badge-danger"><?php if ($table === 'pending' && $status === 'dod') {
                                                                                                                                                                                                    echo $countOrderd;
                                                                                                                                                                                                } ?></span></a>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 text-center p-2 bg-white">
            <a href="track.php?emp_id=<?php echo $emp_id ?>&table=canceld&name=<?php echo $name; ?>" style="text-decoration: none">Canceld <span class="badge badge-fill badge-danger"><?php if ($table === 'canceld') {
                                                                                                                                                                                            echo $countOrderd;
                                                                                                                                                                                        } ?></span></a>
        </div>
        <div class="col-lg-2 col-md-5 col-sm-12 text-center p-2 bg-white">
            <a href="track.php?emp_id=<?php echo $emp_id ?>&table=pending&name=<?php echo $name; ?>&status=not answer" style="text-decoration: none">Not answer <span class="badge badge-fill badge-danger"><?php if ($table === 'pending' && $status === 'not answer') {
                                                                                                                                                                                                                echo $countOrderd;
                                                                                                                                                                                                            } ?></span>
            </a>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 text-center p-2 bg-white">
            <a href="track.php?emp_id=<?php echo $emp_id ?>&table=pending&name=<?php echo $name; ?>&status=call again" style="text-decoration: none">Call again <span class="badge badge-fill badge-danger"><?php if ($table === 'pending' && $status === 'call again') {
                                                                                                                                                                                                                echo $countOrderd;
                                                                                                                                                                                                            } ?></span></a>
        </div>
    </div>
<?php } ?>
<?php if (isset($emp_id, $table)) { ?>
    <div class="bg-white mt-1 p-1 d-flex align-items-center justify-content-center mb-2 col-12">
        <div class="d-flex justify-content-around col-lg-4 col-md-12 col-sm-12 mb-2">
            <button type="button" id="deleteAllButton" class="col-5 p-1 btn btn-danger btn-sm" onclick="deleteAll('delete.php', '<?php echo $table ?>')">
                <i class="fa fa-trash"></i> Delete
            </button>
            <?php if ($table === 'canceld') { ?>
                <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addEmpToLeadModalCancel" class="col-5 p-1 btn btn-success btn-sm">
                    <i class="fa fa-edit"></i> Re assiagn
                </button>
            <?php } ?>
            <?php if ($table === 'pending') { ?>
                <button type="button" id="updateAllButton" data-toggle="modal" data-target="#addEmpToLeadModalNew" class="col-5 p-1 btn btn-success btn-sm">
                    <i class="fa fa-edit"></i> Re assiagn
                </button>
            <?php } ?>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 text-center">
            <form class="col-12" method="GET">
                <input type="hidden" value="<?php echo $_GET['emp_id']; ?>" name="emp_id" />
                <input type="hidden" value="<?php echo $_GET['table']; ?>" name="table" />
                <input type="hidden" value="<?php echo $_GET['name']; ?>" name="name" />
                <input type="hidden" value="<?php echo $_GET['status']; ?>" name="status" />
                <div class="d-lg-flex d-md-block justify-content-lg-between">
                    <input type="date" class="form-group col-lg-5 col-md-12 form-control" name="date_first" value="<?php echo $_GET['date_first']; ?>" />
                    <input type="date" class="form-group col-lg-5 col-md-12 form-control" name="date_last" value="<?php echo $_GET['date_last']; ?>" />
                </div>
                <div class="d-block">
                    <button type="submit" name="filter" class="btn btn-sm btn-block btn-dark"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header border-transparent">
            <h3 class="card-title"><?php echo isset($table) ? $table : 'Sales'; ?></h3>

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
                <table class="tracking table table-hover table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" class="checkedAll" />
                            </th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Product</th>
                            <th>Pieces</th>
                            <th>Price</th>
                            <th><?php if ($table === 'orderd' || $table === 'pending') {
                                    echo 'City';
                                }
                                if ($table === 'canceld') {
                                    echo 'Status';
                                } ?></th>
                            <?php if ($table === 'orderd' || $table === 'pending') { ?>
                                <th>Address</th>
                                <th>Doo</th>
                                <th>Dod</th>
                                <?php if (!isset($status)) { ?>
                                    <th>Shipping</th>
                                <?php } ?>
                                <th>Comment</th>
                                <?php if ($status === 'call again') { ?>
                                    <td>Pending Time</td>
                                    <td>Pending Date</td>
                                <?php } ?>
                            <?php } ?>
                            <th><?php if ($table === 'orderd' || $table === 'pending') {
                                    echo 'Created at';
                                }
                                if ($table === 'canceld') {
                                    echo 'Canceld at';
                                } ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($leads) > 0) { ?>
                            <?php foreach ($leads as $lead) { ?>
                                <tr id="<?php echo 'row-' . $lead['id'] ?>">
                                    <td style="min-width:10px;" class="text-center">
                                        <input type="checkbox" class="checkedList" name="checkedList" value="<?php echo $lead['id'] ?>" />
                                    </td>
                                    <td style="min-width: 10px;"><?php echo $lead['id']; ?></td>
                                    <td style="min-width: 150px;"><?php echo $lead['name']; ?></td>
                                    <td style="font-weight:bold;min-width: 100px;"><?php echo $lead['phone']; ?></td>
                                    <td style="min-width: 100px;"><?php echo $lead['prname']; ?></td>
                                    <td style="min-width: 10px;"><?php echo $lead['prpieces']; ?></td>
                                    <td style="min-width: 100px;"><span class="badge badge-danger badge-pill  p-2"><?php echo $lead['prprice'] * $lead['prpieces'] . ' ' . $lead['prcurrency']; ?></span></td>
                                    <td style="min-width: 150px;"><?php if ($table === 'orderd' || $table === 'pending') {
                                                                        echo $lead['city'];
                                                                    }
                                                                    if ($table === 'canceld') {
                                                                        echo $lead['status'];
                                                                    } ?></td>
                                    <?php if ($table === 'orderd' || $table === 'pending') { ?>
                                        <td style="min-width: 150px;"><?php echo $lead['address']; ?></td>
                                        <td style="min-width: 150px;"><?php echo $lead['doo']; ?></td>
                                        <td style="min-width: 150px;"><?php echo $lead['dod']; ?></td>
                                        <?php if (!isset($status)) { ?>
                                            <td style="min-width: 150px;"><?php echo $lead['wod']; ?></td>
                                        <?php } ?>
                                        <td style="min-width: 200px;"><?php if ($table === 'orderd') {
                                                                            echo $lead['comment'];
                                                                        }
                                                                        if ($status === 'call again') {
                                                                            echo $lead['pending_comment'];
                                                                        } ?></td>
                                        <?php if (isset($status) && $status === 'call again') { ?>
                                            <td style="min-width: 150px;"><?php echo $lead['pending_time']; ?></td>
                                            <td style="min-width: 150px;"><?php echo $lead['pending_date']; ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                    <td style="min-width: 200px;"><?php if ($table === 'orderd' || $table === 'pending') {
                                                                        echo $lead['created_at'];
                                                                    }
                                                                    if ($table === 'canceld') {
                                                                        echo $lead['canceld_at'];
                                                                    } ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
<?php } ?>
<?php if ($table === 'canceld') { ?>
    <!-- Add Employee To Leads Modal Canceld -->
    <div class="modal fade" id="addEmpToLeadModalCancel" tabindex="-1" role="dialog" aria-labelledby="addEmpToLeadModalLabel" aria-hidden="true">
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
                    <input type="hidden" value="<?php echo $_SESSION['username']; ?>" id="admin_name">
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
                        <select class="form-control col-12 pb-0" id="employee" required>
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
                        <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateCancel('canceld.php')">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($table === 'pending') { ?>
    <!-- Choose Employee For New Orders-->
    <div class="modal fade" id="addEmpToLeadModalNew" tabindex="-1" role="dialog" aria-labelledby="addEmpToLeadModalLabel" aria-hidden="true">
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
                            <select class="form-control col-12 pb-0" id="employee" required>
                                <?php
                                // Employees Select Box
                                $stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
                                $stmt->execute();
                                $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <option value="0">None</option>
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
                        <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('update.php', 'pending')">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- TABLE: TRACKING -->
<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Sales</h3>

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
            <table class="tracking table table-hover table-bordered table-responsive">
                <thead>
                    <tr>
                        <th style="min-width: 300px" class="text-center">Name</th>
                        <th style="min-width: 100px" class="text-center">Pending</th>
                        <th style="min-width: 100px" class="text-center">Not answer</th>
                        <th style="min-width: 100px" class="text-center">DOD</th>
                        <th style="min-width: 100px" class="text-center">Orderd</th>
                        <th style="min-width: 100px" class="text-center">Canceld</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($sales as $value) { ?>
                        <tr>
                            <td>
                                <a href="track.php?emp_id=<?php echo $value['id']; ?>&name=<?php echo $value['name']; ?>&emp_id=<?php echo $value['id']; ?>"><?php echo $value['name'] ?></a>
                            </td>
                            <td>
                                <?php
                                $stmt = $con->prepare("SELECT count(*) as count FROM pending WHERE emp_id ='" . $value['id'] . "' AND status !='dod'");
                                $stmt->execute();
                                $count = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo '<span class="badge badge-pill badge-primary">' . $count['count'] . ' Lead' . '</span>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $stmt = $con->prepare("SELECT count(*) as count FROM pending WHERE emp_id ='" . $value['id'] . "' AND status ='not answer'");
                                $stmt->execute();
                                $count = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo '<span class="badge badge-pill badge-danger">' . $count['count'] . ' Lead' . '</span>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $stmt = $con->prepare("SELECT count(*) as count FROM pending WHERE emp_id ='" . $value['id'] . "' AND status ='dod'");
                                $stmt->execute();
                                $count = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo '<span class="badge badge-pill badge-success">' . $count['count'] . ' Lead' . '</span>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $stmt = $con->prepare("SELECT count(*) as count FROM orderd WHERE emp_id ='" . $value['id'] . "'");
                                $stmt->execute();
                                $count = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo '<span class="badge badge-pill badge-success">' . $count['count'] . ' Lead' . '</span>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $stmt = $con->prepare("SELECT count(*) as count FROM canceld WHERE emp_id ='" . $value['id'] . "'");
                                $stmt->execute();
                                $count = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo '<span class="badge badge-pill badge-danger">' . $count['count'] . ' Lead' . '</span>';
                                ?>
                            </td>
                        </tr>
                    <?php }
                    ?>

                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
<?php include('main/footer1.php'); ?>

<script>
    $.fn.dataTable.ext.classes.sPageButton = 'paginate_custom_buttons';
    $('.tracking').DataTable({
        dom: 'lBfrtip',
        language: {
            search: '',
            searchPlaceholder: "Search....."
        },
        stateSave: true,
        buttons: [{
                "extend": 'colvis',
                "text": "<i class='fas fa-eye'></i>",
                "title": '',
                "collectionLayout": 'fixed two-column',
                "className": "btn btn-sm btn-outline-dark",
                "bom": "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                }
            },
            {
                "extend": "csv",
                "text": "<i class='fas fa-file-csv'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-success",
                "charset": "utf-8",
                "bom": "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                }
            },
            {
                "extend": "excel",
                "text": "<i class='fas fa-file-excel'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-danger",
                "charset": "utf-8",
                "bom": "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                "extend": "print",
                "text": "<i class='fas fa-file-pdf'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-primary",
                "charset": "utf-8",
                "bom": "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                "extend": "copy",
                "text": "<i class='fas fa-copy'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-info",
                "charset": "utf-8",
                "bom": "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [':visible']
                }
            }
        ],
        "columnDefs": [{
            "orderable": false,
            "targets": 0
        }],
        "lengthMenu": [
            [10,15,20,25,30, 50, 100, 200,300],
            [10,15,20,25,30, 50, 100, 200,300]
        ]
    });
    $(document).ready(function() {
        document.title = 'HealthyCURE | Tracking';
    });
</script>
</body>

</html>