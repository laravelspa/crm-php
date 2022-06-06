<?php 
ob_start();
session_start();
include('../main/database.php');

if(isset($_GET['dbname'], $_GET['dbuser'], $_GET['dbtable'])) {
    // Employees In Select Box
    $stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
    $stmt->execute();
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $_SESSION['dbid'] = $_GET['dbid']; 
    $_SESSION['dbname'] = $_GET['dbname']; 
    $_SESSION['dbtable'] = $_GET['dbtable']; 
    $_SESSION['dbuser'] = $_GET['dbuser']; 
    //$_SESSION['dbpassword'] = $_GET['dbpassword']; 
    $_SESSION['prname'] = $_GET['prn']; 
    $_SESSION['prprice'] = $_GET['prp']; 
    $_SESSION['prcurrency'] = $_GET['prc']; 
} else {
    header('location: index.php');
}
include('../main/header1.php');
?>  
<!-- TABLE: DB LEADS -->
<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Leads</h3>

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
            <button type="button" id="deleteAllButton" class="btn btn-outline-danger btn-sm" onclick="deleteAll('delete_lead.php', '<?php echo $_SESSION['dbtable']; ?>','Once you deleted users his orders became in wall.','You must be Choose user to delete it. ','you are canceld this deleting operation.')">
                <i class="fa fa-trash"></i> Delete
            </button>
            <button type="button" id="updateAllButton" data-toggle="modal" data-target="#createProjectModal" class="btn btn-outline-success btn-sm">
                <i class="fa fa-edit"></i> Re assiagn
            </button>
        </div>
        <div class="table-responsive">   
           <table id="table_id" class="table table-hover table-bordered table-responsive">
                <thead>
                    <tr>
                        <th class="text-center"style="max-width:10px">
                            <input type="checkbox" class="checkedAll" />  
                        </th>
                        <th style="min-width: 10%" class="text-center">id</th>
                        <th style="min-width: 30%" class="text-center">Name</th>
                        <th style="min-width: 20%" class="text-center">Phone</th>
                        <th style="min-width: 20%" class="text-center">From</th>
                        <th style="min-width: 30%" class="text-center">Orderd_at</th>
                        <th style="min-width: 10%" class="text-center">Delete</th>
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
        
<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create_project_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize font-weight-bold" id="createProjectModalLabel">
                        Re assign To Sales                            
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <input type="hidden" id="admin_id" value="<?php echo $_SESSION['id']; ?>" />
                        <input type="hidden" id="db_id" value="<?php echo $_GET['dbid']; ?>" />
                        <input type="hidden" id="product_name" value="<?php echo $_GET['prn']; ?>" />
                        <input type="hidden" id="product_price" value="<?php echo $_GET['prp']; ?>" />
                        <input type="hidden" id="currency" value="<?php echo $_GET['prc']; ?>" />
                        
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="employee">Employee <span class="text-danger">*</span></label>
                            <select class="form-control col-12 pb-0" id="employee" style="width:100%">
                                <option value="null">None</option>
                                <?php foreach($employes as $emp) { ?>
                                    <option class="text-capitalize" value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button id="create_project" type="submit" class="btn btn-sm btn-success">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- EDIT Lead Modal -->
<div class="modal fade" id="editLeadModal" tabindex="-1" role="dialog" aria-labelledby="editLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="edit_lead_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize font-weight-bold" id="editLeadModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <input type="hidden" id="lead_id" />
                        
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="lead_name">Name<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="lead_name" placeholder="Type Name">
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="lead_phone">Phone<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="lead_phone" placeholder="Type phone">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
   <?php include('../main/footer1.php'); ?>
   <script>
       document.title = 'HealthyCURE | Leads';
        $('#employee').select2({
            dropdownParent: $('#createProjectModal')
        })
        getRecords('fetch_leads.php');
        // Checked Or Not [delete - edit]
        const checkedAllInLeads = document.querySelector('.checkedAll');
        
        if(checkedAllInLeads) {
            checkedAllInLeads.addEventListener('change', () => {
                const checkedList = document.querySelectorAll('.checkedList');
                if(checkedAllInLeads.checked === true) {
                    for(i = 0; i < checkedList.length; i++) {
                        checkedList[i].checked = true;
                    }
                } else {
                    for(i = 0; i < checkedList.length; i++) {
                        checkedList[i].checked = false;
                    }
                }
                
            })
        }
        const formCreateProject = $('#create_project_form');
        // var count = 0;
        if(formCreateProject) {
            formCreateProject.on('submit', (e) => {
                e.preventDefault();
                const checked = document.querySelectorAll('input[name=checkedList]:checked');
                if(checked.length === 0) {
                    swal.fire('Please select leads!', '', "warning");
                } else {
                    // count++;
                    var selected = [];
                    const checked = document.querySelectorAll('input[name=checkedList]:checked');
                    for(i=0; i < checked.length; i++) {
                        if(checked[i].checked) {
                            selected.push(checked[i].value)
                        }
                    }
                    const ProductName = $('#product_name').val();
                    const ProductPrice = $('#product_price').val();
                    const ProductCurrency = $('#currency').val();
                    const Employee = $('#employee').val();
                    const AdminID = $('#admin_id').val();
                    const DbID = $('#db_id').val();
                    
                    var formdata = new FormData();
                    formdata.append('project_name', ProductName);
                    formdata.append('product_name', ProductName);
                    formdata.append('product_price', ProductPrice);
                    formdata.append('product_currency', ProductCurrency);
                    formdata.append('employee_id', Employee);
                    formdata.append('admin_id', AdminID);
                    formdata.append('db_id', DbID);
                    formdata.append('ids', selected);
                    $('#createProjectModal').modal('hide');
                  
                    fetch('re_assign.php', {
                        method: 'POST',
                        body: formdata
                    }).then(res => {
                        return res.json();
                    }).then(r => { 
                        if(r.text === true) {
                            swal.fire("Re assign Done!!", ProductName, "success");
                            $('#table_id').DataTable().ajax.reload();
                            formCreateProject[0].reset();                    
                        } else {
                            $('#table_id').DataTable().ajax.reload();
                            formCreateProject[0].reset();
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                }
            });
        }

        $(document).on('click','#edit_lead',function(e) {
            e.preventDefault();
            const ModalHeaderEdit = $('#editLeadModalLabel');
            $('#editLeadModal').modal('show');
            var id = $(this).data('id');
            var name = $(this).data('name');
            var phone = $(this).data('phone');
            ModalHeaderEdit.text('Edit ' + name)
            
            $('#lead_id').val(id)
            $('#lead_name').val(name)
            $('#lead_phone').val(phone)
            const formEditLead = $('#edit_lead_form');
           
            if(formEditLead) {
                formEditLead.on('submit', (e) => {
                    e.preventDefault();
                    const LeadId = $('#lead_id').val();
                    const LeadName = $('#lead_name').val();
                    const LeadPhone = $('#lead_phone').val();
                  
                    var formdata = new FormData();
                    formdata.append('lead_id', LeadId);
                    formdata.append('lead_name', LeadName);
                    formdata.append('lead_phone', LeadPhone);
                    $('#editLeadModal').modal('hide');
                  
                    fetch('edit_lead.php', {
                        method: 'POST',
                        body: formdata
                    }).then(res => {
                        return res.json();
                    }).then(r => { 
                        if(r.text === true) {
                            swal.fire("Edit Done!!", LeadName, "success");
                            $('#table_id').DataTable().ajax.reload();                    
                        } else {
                            $('#table_id').DataTable().ajax.reload();
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                });
            }
        })
   </script>
</body>
</html>