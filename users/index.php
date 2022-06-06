<?php 
ob_start();
session_start();
include('../main/database.php');
$stmtp = $con->prepare("SELECT pname FROM pending GROUP BY pname ORDER BY id ASC");
$stmtp->execute();
$group_projects = $stmtp->fetchAll(PDO::FETCH_ASSOC);

if($_SESSION['permission'] !== '0') {
  header('Location: ../login.php');
}

include('../main/header1.php');

?>
<!-- row -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-transparent">
        <h3 class="card-title">Users</h3>

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
        <div class="d-flex justify-content-around col-lg-6 col-md-12 col-sm-12 mt-2 float-right">
            <!-- Button trigger modal -->
            <button type="button" class="col-3 btn btn-outline-success btn-sm" data-toggle="modal" data-target="#newUserModal">
                <i class="fa fa-plus"></i> New User
            </button>
            <button type="button" id="deleteAllButton" class="col-3 btn btn-outline-danger btn-sm" onclick="deleteAll('../delete.php', 'admins','Once you deleted users his orders became in wall.','You must be Choose user to delete it. ','you are canceld this deleting operation.')">
                <i class="fa fa-trash"></i> Delete
            </button>
            <button type="button" id="updateAllButton" data-toggle="modal" data-target="#wallModal" class="col-3 btn btn-outline-primary btn-sm">
                <i class="fa fa-edit"></i> Wall
            </button>
        </div>  
        <div class="table-responsive">
            <table id="table_id" class="table table-hover table-bordered table-responsive" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center"style="max-width:10px">
                            <input type="checkbox" class="checkedAll" />  
                        </th>
                        <th style="min-width: 10%" class="text-center">id</th>
                        <th style="min-width: 150px" class="text-center">Name</th>
                        <th style="min-width: 150%" class="text-center">Permission</th>
                        <th style="min-width: 10%" class="text-center">Wall</th>
                        <th style="min-width: 30%" class="text-center">Supervisor</th>
                        <th style="min-width: 30%" class="text-center">Projects</th>
                        <th style="min-width: 30%" class="text-center">Location</th>
                        <th style="min-width: 10%" class="text-center">Edit</th>
                        <th style="min-width: 10%" class="text-center">Delete</th>
                    </tr>
                </thead>
                <tbody></tbody>        
            </table>
        </div>       
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<!-- Modal -->
<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="create_user"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="newUserModalLabel">Create New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group col-12">
            <label for="name" class="font-weight-bold">Name</label>
            <input class="form-control col-12" id="name" type="text" placeholder="Add Name" required />
        </div>
        <div class="form-group col-12">
            <label for="password" class="font-weight-bold">Password</label>
            <input class="form-control col-12 password" id="password" type="password" placeholder="Add Password" required />
        </div>
        <div class="form-group col-12">
            <label for="permission" class="font-weight-bold">Permission</label>
            <select class="form-control col-12 permission" id="permission" required>
                <option value="0">Admin</option>
                <option value="2">Sales</option>
                <option value="1">Supervisor - Calling</option>
                <option value="3">Supervisor - Delivery</option>
                <option value="4">Supervisor assistant</option>
                <option value="5">Delivery Man</option>
                <option value="6">Delivery Call</option>
                <option value="7">Status Watcher</option>
            </select>
        </div>
        <div class="form-group col-12 supervisor-container" style="display:none">
            <label class="font-weight-bold" for="supervisor">Supervisor</label>
            <select id="supervisor" class="form-control col-12 supervisor"></select>
        </div>
        <div class="form-group col-12 wall-container" style="display:none">
            <label for="wall" class="font-weight-bold">Wall</label>
            <select id="wall" class="wall form-control" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group col-12 projects-container" style="display:none">
            <label for="projects" class="font-weight-bold">Projects</label>
            <select id="projects" class="col-12 projects form-control" multiple style="width:100%">
                <?php foreach($group_projects as $project) { ?>
                      <option value="<?php echo $project['pname']; ?>"><?php echo $project['pname']; ?></option>  
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-12 location-container" style="display:none">
            <label for="location" class="font-weight-bold">Location</label>
            <select id="location" class="col-12 location form-control" style="width:100%">
                <option value="0">Cairo</option>
                <option value="1">Alexandria</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        <button name="submit" class="btn btn-sm btn-success create">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
      <div class="modal-header text-danger">
        <h5 class="modal-title text-capitalize text-danger" id="editAdminModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="edit_user" method="post">
            <input type="hidden" class="edit_id" required/>
            <input type="hidden" class="oldpassword" required/>
            <div class="form-group p-2">
                <label for="newname" class="font-weight-bold">Name</label>
                <input type="text" placeholder="Add Name" class="newname form-control" id="newname" required/>
            </div>
            <div class="form-group col-12">
                <label for="newpassword" class="font-weight-bold">Password</label>
                <input class="form-control col-12 newpassword" id="newpassword" type="password" placeholder="Add Password" />
            </div>
            <div class="form-group p-2">
                <label for="newpermission" class="font-weight-bold">Permission</label>
                <select id="newpermission" class="newpermission form-control" required>
                    <option value="0">Admin</option>
                    <option value="2">Sales</option>
                    <option value="1">Supervisor[Calling]</option>
                    <option value="3">Supervisor[Delivery]</option>
                    <option value="4">Supervisor assistant</option>
                    <option value="5">Delivery Man</option>
                    <option value="6">Delivery Call</option>
                    <option value="7">Status Watcher</option>
                </select>
            </div>
            <div class="form-group p-2 newsupervisor-container" style="display:none">
                <label class="font-weight-bold" for="supervisor">Supervisor</label>
                <select id="newsupervisor" class="form-control newsupervisor"></select>
            </div>
            <div class="form-group p-2 newwall-container" style="display:none">
                <label for="newwall" class="font-weight-bold">Wall</label>
                <select id="newwall" class="newwall form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group p-2 newprojects-container" style="display:none">
                <label for="newprojects">Projects</label>
                <select id="newprojects" class="newprojects form-control" multiple style="width:100%">
                    <?php foreach($group_projects as $project) { ?>
                          <option value="<?php echo $project['pname']; ?>"><?php echo $project['pname']; ?></option>  
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-12 newlocation-container" style="display:none">
                <label for="newlocation" class="font-weight-bold">Location</label>
                <select id="newlocation" class="col-12 newlocation form-control" style="width:100%">
                    <option value="0">Cairo</option>
                    <option value="1">Alexandria</option>
                </select>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-success save">Save</button>
      </div>
      </form>
        </div>
    </div>
</div>

 <!-- Modal Wall Off Or On -->
<div class="modal fade" id="wallModal" tabindex="-1" role="dialog" aria-labelledby="wallModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-danger" >
        <h5 class="modal-title text-capitalize" id="addEmpToLeadModalLabel">Wall visibility</h5><br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group p-2">
                <label for="wallUpdate">Wall</label>
                <select id="wallUpdate" name="wall" class="wallUpdate form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>    
        </div>
        <div class="modal-footer" style="direction:ltr">
            <div class="form-group col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('update_wall.php', 'admins','Once you delete this user all orders became null', 'Select any user to edit it!','Your user is save!')">Save</button>
            </div>
        </div>
    </div>
  </div>
</div>
   <?php include('../main/footer1.php'); ?>
   <script src="../js/users.js"></script>
</body>
</html>