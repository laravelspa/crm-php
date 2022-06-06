<?php
ob_start();
session_start();
if ($_SESSION['permission'] !== '0') {
    header('Location: /login.php');
}
include('../../main/database.php');
include('../../main/header1.php');
// Products In Select Box
$stmt = $con->prepare("SELECT prname FROM pending GROUP BY prname");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- TABLE: DB CONNECTIONS -->
<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Campaigns</h3>

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
            <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#createCampaignModal">
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
                        <th style="min-width: 20%" class="text-center">CampaignID</th>
                        <th style="min-width: 20%" class="text-center">CampaignName</th>
                        <th style="min-width: 20%" class="text-center">ProductName</th>
                        <th style="min-width: 20%" class="text-center">Note</th>
                        <th style="min-width: 20%" class="text-center">For</th>
                        <th style="min-width: 20%" class="text-center">Date</th>
                        <th style="min-width: 20%" class="text-center">Actions</th>
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
<div class="modal fade" id="createCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create_campaign">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="createCampaignModalLabel">New Campaign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap bg-white">
                        <div class="form-group col-12">
                            <label for="c_id">Campaign ID</label>
                            <input class="form-control col-12" id="c_id" type="text" placeholder="Type campaign id" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="c_name">Campaign Name</label>
                            <input class="form-control col-12" id="c_name" type="text" placeholder="Type campaign name" required />
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="prname">Product <span class="text-danger">*</span></label>
                            <select class="form-control col-12" id="prname" required style="width:100%">
                                <?php foreach ($products as $product) { ?>
                                    <option value="<?php echo $product['prname'] ?>"><?php echo $product['prname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="status">For <span class="text-danger">*</span></label>
                            <select class="form-control col-12" id="status" required style="width:100%">
                                <option value="1">Api 1</option>
                                <option value="2">Api 2</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="note">Note</label>
                            <textarea class="form-control col-12" id="note" type="text" placeholder="Type your note"></textarea>
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
<div class="modal fade" id="editCampaignModal" tabindex="-1" role="dialog" aria-labelledby="editCampaignModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="edit_campaign_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="editCampaignModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap bg-white">
                        <input type="hidden" id="id" />
                        <div class="form-group col-12">
                            <label for="cid">Campaign ID</label>
                            <input class="form-control col-12" id="cid" type="text" placeholder="Type campaign id" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="cname">Campaign Name</label>
                            <input class="form-control col-12" id="cname" type="text" placeholder="Type campaign name" required />
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="cprname">Product <span class="text-danger">*</span></label>
                            <select class="form-control col-12" id="cprname" required style="width:100%">
                                <?php foreach ($products as $product) { ?>
                                    <option value="<?php echo $product['prname'] ?>"><?php echo $product['prname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="cstatus">For <span class="text-danger">*</span></label>
                            <select class="form-control col-12" id="cstatus" required style="width:100%">
                                <option value="1">Api 1</option>
                                <option value="2">Api 2</option>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="cnote">Note</label>
                            <textarea class="form-control col-12" id="cnote" type="text" placeholder="Type your note"></textarea>
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
<?php include('../../main/footer1.php'); ?>

<script>
    document.title = 'HealthyCURE | Api';
    getCampaigns('fetch_campaigns.php');

    $('#prname').select2({
        dropdownParent: $('#createCampaignModal')
    })

    // Checked Or Not [delete - edit]
    const checkedAllInLeads = document.querySelector('.checkedAll');

    if (checkedAllInLeads) {
        checkedAllInLeads.addEventListener('change', () => {
            const checkedList = document.querySelectorAll('.checkedList');
            if (checkedAllInLeads.checked === true) {
                for (i = 0; i < checkedList.length; i++) {
                    checkedList[i].checked = true;
                }
            } else {
                for (i = 0; i < checkedList.length; i++) {
                    checkedList[i].checked = false;
                }
            }

        })
    }
    
    $(document).ready(function() {
        // Create Campaign 
        const formCreateCampaign = $('#create_campaign');

        if (formCreateCampaign) {
            formCreateCampaign.on('submit', function(e) {
                e.preventDefault();
                swal.fire({
                        title: "Are you sure?",
                        text: "To Create This Campaign!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willEdit) => {
                        if (willEdit.value === true) {
                            let c_id = $('#c_id').val();
                            let c_name = $('#c_name').val();
                            let prname = $('#prname').val();
                            let status = $('#status').val();
                            let note = $('#note').val();

                            const formData = new FormData();

                            formData.append('c_id', c_id);
                            formData.append('c_name', c_name);
                            formData.append('prname', prname);
                            formData.append('status', status);
                            formData.append('note', note);

                            fetch('create_campaign.php', {
                                method: 'POST',
                                body: formData
                            }).then(res => {
                                return res.json();
                            }).then(response => {
                                if (response.text === true) {
                                    $('#createCampaignModal').modal('hide')
                                    $('#table_id').DataTable().ajax.reload(null, false)
                                    swal.fire("Create Done!", '', "success");
                                } else {
                                    swal.fire("Something wronge!", '', "error");
                                }
                            })
                        } else {
                            swal.fire("this campaign not created!", '', "warning");
                            $('#createCampaignModal').modal('hide')
                        }

                        $('#c_id').val(null);
                        $('#c_name').val(null);
                        $('#note').val(null);

                    });
            });
        }

        // Edit Modal
        $(document).on('click', '#edit_campaign', function() {
            const headerModal = document.querySelector('#editCampaignModalLabel');
            let id = $(this).data('id');
            let c_id = $(this).data('cid');
            let c_name = $(this).data('cname');
            let cprname = $(this).data('prname');
            let cstatus = $(this).data('status');
            let cnote = $(this).data('note');

            headerModal.textContent = 'Edit ' + c_name;

            $('#id').val(id);
            $('#cid').val(c_id);
            $('#cname').val(c_name);
            $('#cprname').val(cprname);
            $('#cstatus').val(cstatus);
            $('#cnote').val(cnote);
        })

        // Edit Form
        const formEdit = $('#edit_campaign_form');
        if (formEdit) {
            formEdit.on('submit', (e) => {
                e.preventDefault();
                $('#editCampaignModal').modal('hide');
                swal.fire({
                    title: "Are you sure change this information?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willUpdate) => {
                    const formData = new FormData();

                    formData.append('id', $('#id').val());
                    formData.append('cid', $('#cid').val());
                    formData.append('cname', $('#cname').val());
                    formData.append('cprname', $('#cprname').val());
                    formData.append('cstatus', $('#cstatus').val());
                    formData.append('cnote', $('#cnote').val());

                    if (willUpdate.value === true) {
                        fetch('edit_campaign.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            if (response.text === true) {
                                $('#table_id').DataTable().ajax.reload(null, false);
                                swal.fire("Updated Done!", '', "success");
                            } else {
                                swal.fire("Something wronge!", '', "error");
                            }
                        })
                    } else {
                        swal.fire("Your campaign is not updated!");
                    }
                    formEdit[0].reset();
                })
            })
        }

    });
</script>
</body>

</html>