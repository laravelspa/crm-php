<?php 
ob_start();
session_start();
if($_SESSION['permission'] !== '0') {
    header('Location: ../login.php');
}
include('../main/database.php');
include('../main/header1.php');
// All DBS Name
$stmt = $con->prepare("SELECT * FROM databases_connections");
$stmt->execute();
$DBS = $stmt->fetchAll(PDO::FETCH_ASSOC);

function get_count($dbname,$dbuser,$dbpassword,$table) {
    $dsn2 = "mysql:host=localhost;port=45478;dbname=$dbname";
    try {
        $con2 = new PDO($dsn2, $dbuser, $dbpassword);
        $stmt = $con2->prepare("SELECT id FROM $table");
        $stmt->execute();
        return $stmt->rowCount();
    }
    catch(PDOException $e) {
        header('location: error.php');
    }
}
?>
<!-- TABLE: DB LEADS -->
<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Statistics</h3>

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
            <table class="stats table table-hover table-bordered table-responsive">
                <thead>
                    <tr>
                        <th style="min-width: 300px" class="text-center">DB Name</th>
                        <th style="min-width: 100px" class="text-center">New</th>
                        <th style="min-width: 100px" class="text-center">Orderd</th>
                        <th style="min-width: 100px" class="text-center">Pending</th>
                        <th style="min-width: 100px" class="text-center">Canceld</th>
                        <th style="min-width: 100px" class="text-center">All</th>
                    </tr>
                </thead>
                <tbody>
                        <?php 
                            foreach ($DBS as $value) { ?>
                            <tr>
                                <td>
                                     <a href="leads.php?dbid=<?php echo $value['id'] ?>&dbname=<?php echo $value['db_name']?>&dbtable=<?php echo $value['db_table']?>&dbuser=<?php echo $value['db_user']?>&dbpassword=<?php echo $value['db_password']?>&prn=<?php echo $value['prname']?>&prp=<?php echo $value['prprice']?>&prc=<?php echo $value['prcurrency']?>"><?php echo $value['db_name']?></a>
                                </td>
                                <td>
                                    <?php $countNew = get_count($value['db_name'],$value['db_user'],$value['db_password'],$value['db_table']); ?>
                                    <?php echo '<span class="p-2 badge badge-pill badge-warning">'.get_count($value['db_name'],$value['db_user'],$value['db_password'],$value['db_table']) . ' Lead</span>' ?>
                                </td>
                                <td>
                                    <?php
                                        $stmt = $con->prepare("SELECT orderd.db_id, databases_connections.id,databases_connections.db_name ,count(*) as count FROM orderd LEFT JOIN databases_connections ON orderd.db_id = databases_connections.id WHERE orderd.db_id = '". $value['id']."'");
                                        $stmt->execute();
                                        $dbo = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        $countOrderd = $dbo[0]['count'];
                                        echo '<span class="p-2 badge badge-pill badge-success">'.$dbo[0]['count'].' Lead'.'</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $stmt = $con->prepare("SELECT pending.db_id, databases_connections.id,databases_connections.db_name ,count(*) as count FROM pending LEFT JOIN databases_connections ON pending.db_id = databases_connections.id WHERE pending.db_id = '". $value['id']."'");
                                        $stmt->execute();
                                        $dbp = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        $countPending = $dbp[0]['count'];
                                       echo '<span class="p-2 badge badge-pill badge-primary">'.$dbp[0]['count'].' Lead'.'</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $stmt = $con->prepare("SELECT canceld.db_id, databases_connections.id,databases_connections.db_name ,count(*) as count FROM canceld LEFT JOIN databases_connections ON canceld.db_id = databases_connections.id WHERE canceld.db_id = '". $value['id']."'");
                                        $stmt->execute();
                                        $dbc = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        $countCanceld = $dbc[0]['count'];
                                        echo '<span class="p-2 badge badge-pill badge-danger">'.$dbc[0]['count'].' Lead'.'</span>';
                                    ?>                                
                                </td>
                                <td>
                                    <span class="p-2 badge badge-pill badge-dark"><?php echo $countCanceld + $countOrderd + $countPending + $countNew . ' Lead'; ?></span>
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
   <?php include('../main/footer1.php'); ?>
   <script>
        $.fn.dataTable.ext.classes.sPageButton = 'paginate_custom_buttons';
        $('.stats').DataTable({
            dom: 'lBfrtip',
            language: { search: '', searchPlaceholder: "Search....." },
            stateSave: true,
            buttons: [
                {
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
            ]
        });
    </script>
</body>
</html>