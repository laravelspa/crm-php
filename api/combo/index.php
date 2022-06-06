<?php 
ob_start();
session_start();
if($_SESSION['permission'] !== '0') {
    header('Location: ../../login.php');
  }
  
include('../../main/header1.php');
include('../../main/database.php');
$stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
$stmt->execute();
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con->prepare('SELECT pname FROM pending GROUP BY pname');
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <button type="button" id="deleteAllButton" class="btn btn-outline-danger btn-sm" onclick="deleteAll('../delete_lead.php', 'combo','Delete this leads','You must be Choose user to delete it. ','you are canceld this deleting operation.')">
                <i class="fa fa-trash"></i> Delete
            </button>
            <button type="button" id="updateAllButton" data-toggle="modal" data-target="#reAssignModal" class="btn btn-outline-success btn-sm">
                <i class="fa fa-edit"></i> Re assiagn
            </button>
        </div>
        <div class="table-responsive">   
           <table id="combo" class="table table-hover table-bordered table-responsive">
                <thead>
                    <tr>
                        <th class="text-center"style="max-width:10px">
                            <input type="checkbox" class="checkedAll" />  
                        </th>
                        <th style="min-width: 10%" class="text-center">OrderID</th>
                        <th style="min-width: 10%" class="text-center">Fingerprint</th>
                        <th style="min-width: 30%" class="text-center">Name</th>
						<th style="min-width: 30%" class="text-center">Phone</th>
						<th style="min-width: 30%" class="text-center">Address</th>
                        <th style="min-width: 10%" class="text-center">CampaignID</th>                  
                        <th style="min-width: 30%" class="text-center">CampaignName</th>
                        <th style="min-width: 30%" class="text-center">ProductName</th>
                        <th style="min-width: 30%" class="text-center">Comment</th>
                        <th style="min-width: 30%" class="text-center">Created at</th>
                      
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
        
<!-- Re assign Modal -->
<div class="modal fade" id="reAssignModal" tabindex="-1" role="dialog" aria-labelledby="reAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="re_assign_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize font-weight-bold" id="reAssignModalLabel">
                        Re assign To Sales                            
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <div class="form-group col-12">
                            <input type="hidden" id="admin_id" value="<?php echo $_SESSION['id']; ?>" />
                            <label class="font-weight-bold" for="employee">Employee <span class="text-danger">*</span></label>
                            <select class="form-control col-12 pb-0" id="employee" style="width:100%">
                                <option value="null">None</option>
                                <?php foreach($employes as $emp) { ?>
                                    <option class="text-capitalize" value="<?php echo $emp['id'] ?>">
                                        <?php echo $emp['name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button id="re_assign" type="submit" class="btn btn-sm btn-success">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- START Edit Status MODAL -->
<div class="modal fade" id="EditStatusModal" tabindex="-1" role="dialog" aria-labelledby="EditStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-danger">
                <h5 class="modal-title text-capitalize" id="EditStatusModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit">
                <div class="modal-body">
                    <input type="hidden" hidden="true" id="id" required/>
                  	<input type="hidden" hidden="true" id="lead_id" required/>
                  	<input type="hidden" hidden="true" id="name" required/>

                    <div class="row col-12">                
                        <div class="form-group col-12">
                            <label for="status">Status</label>
                            <select class="form-control pb-0 status" id="status" required>
                                <option value="null" selected>-- Choose Status --</option>
                              	<option value="accept">Accept</option>
                              	<option value="expect">Expect</option>
                              	<option value="confirm">Confirm</option>	
                              	<option value="cancel">Cancel</option>
                              	<option value="fail">Fail</option>
                                <option value="reject">Reject</option>
                                <option value="trash">Trash</option>
                              	<option value="error">Error</option>
                            </select>
                          <label class="font-weight-bold" for="comment">Comment</label>
                         <textarea type="text" id="comment" class="form-control col-12 comment" placeholder="Add Comment" required></textarea>
                        </div>
                    </div>         
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success save" >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Edit Status MODAL -->

<!-- START Edit Lead MODAL -->
<div class="modal fade" id="EditLeadModal" tabindex="-1" role="dialog" aria-labelledby="EditLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-danger">
                <h5 class="modal-title text-capitalize" id="EditLeadModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit_lead">
                <div class="modal-body">
                    <input type="hidden" hidden="true" id="lead_id" required/>
                    
                    <div class="form-group p-2">
                        <label for="lead_name">Name</label>
                        <input type="text" id="lead_name" class="form-control" placeholder="Type a Name" required dir="auto" />
                    </div>

                    <div class="form-group p-2">
                        <label for="lead_phone">Phone</label>
                        <input type="text" id="lead_phone" class="form-control" placeholder="Phone Number" dir="auto" required/>
                    </div>

                    <div class="form-group col-12">
                        <label for="lead_comment">Comment</label>
                        <textarea type="text" id="lead_comment" class="form-control col-12 comment" placeholder="Add Comment" dir="auto"></textarea>
                    </div>
                </div>
                <div class="modal-footer form-group col-12 d-flex justify-content-between" style="direction:ltr">
                    <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success save" >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Edit Lead MODAL -->

   <?php include('../../main/footer1.php'); ?>
   <script>
       document.title = 'HealthyCURE | Api';
        $('#employee').select2({
            dropdownParent: $('#reAssignModal')
        })
        getComboLeads('fetch_leads.php');
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
        const formReassignForm = $('#re_assign_form');
        // var count = 0;
        if(formReassignForm) {
            formReassignForm.on('submit', (e) => {
                e.preventDefault();
                const checked = document.querySelectorAll('input[name=checkedList]:checked');
                if(checked.length === 0) {
                    swal.fire('Please select leads!', '', "warning");
                } else {
                    // count++;
                    var selected = [];
                    var ids = [];
                    const checked = document.querySelectorAll('input[name=checkedList]:checked');
                    for(i=0; i < checked.length; i++) {
                        if(checked[i].checked) {
                            ids.push(checked[i].value)
                            selected.push(
                                {
                                    name: checked[i].getAttribute("data-name"),  
                                    phone: checked[i].getAttribute("data-phone"),  
                                    country: 'NULL',  
                                    address: checked[i].getAttribute("data-address"),  
                                    prname: checked[i].getAttribute("data-prn"),
                                    id: checked[i].getAttribute("data-id"),
                                    lead_id: checked[i].getAttribute("data-oid"),
                                    user_id: checked[i].getAttribute("data-uid"),
                                    user_id: checked[i].getAttribute("data-uid"),
                                    created_at: checked[i].getAttribute("data-cra"),
                                }
                            )
                        }
                    }
                    const Employee = $('#employee').val();
                    const AdminID = $('#admin_id').val();
                    
                    var formdata = new FormData();
                    
                    formdata.append('employee_id', Employee);
                    formdata.append('admin_id', AdminID);
                    formdata.append('selected', JSON.stringify(selected));
                    formdata.append('ids', ids);
                    
                    $('#reAssignModal').modal('hide');
                  
                    fetch('re_assign.php', {
                        method: 'POST',
                        body: formdata
                    }).then(res => {
                        return res.json();
                    }).then(r => { 
                        if(r.message === true) {
                            swal.fire("Re assign Done!!", '', "success");
                            $('#combo').DataTable().ajax.reload();
                        } else {
                            $('#combo').DataTable().ajax.reload();
                            swal.fire("Something wronge!", r.text, "error");
                        }
                        formReassignForm[0].reset();
                    })
                }
            });
        }

        
        // Edit Order 
        $(document).on('click','#edit_lead', function() {
            const editLeadheaderModal = document.querySelector('#EditLeadModalLabel');
            var id          = $(this).data('id');
            var name        = $(this).data('name');
            var phone       = $(this).data('phone');
            var com         = $(this).data('com');
            
            editLeadheaderModal.textContent =  'Edit Lead ' + name;
            
            $('#lead_id').val(id);
            $('#lead_name').val(name);
            $('#lead_phone').val(phone);
            $('#lead_comment').val(com);

            const formEditLead = $('#form_edit_lead');

            if(formEditLead) {   
                formEditLead.on('submit', function(e) {
                    e.preventDefault();
                    swal.fire({
                      title: "Are you sure?",
                      text: "To Edit This Lead!",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((willEdit) => {
                      if (willEdit.value === true) {   
                        var id          =   $('#lead_id').val();
                        var name        =   $('#lead_name').val();
                        var phone       =   $('#lead_phone').val();
                        var com         =   $('#lead_comment').val();
      
                        const formData = new FormData();
                        
                        formData.append('id', id);
                        formData.append('name', name);
                        formData.append('phone', phone);
                        formData.append('comment', com);
                            
                        fetch('update_lead.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            if(response.text === true) {
                                $('#EditLeadModal').modal('hide')
                                $('#combo').DataTable().ajax.reload(null,false)
                                swal.fire("Edit Done!", '', "success");
                            } else {
                                swal.fire("Something wronge!", '', "error");
                            }
                        })
                      } else {
                        swal.fire("Your lead do not updated!", '', "warning");
                      }
                    });    
                });
            }   
        })
   </script>
</body>
</html>