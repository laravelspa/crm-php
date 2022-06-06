<?php
ob_start();
session_start();
if ($_SESSION['permission'] !== '0') {
    header('Location: /login.php');
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
        <h3 class="card-title">Fingerprint</h3>

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
            <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#createFingerprintModal">
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
                        <th style="min-width: 20%" class="text-center">ID</th>
                        <th style="min-width: 20%" class="text-center">Old Fingerprint</th>
                        <th style="min-width: 20%" class="text-center">New Fingerprint</th>
                        <th style="min-width: 20%" class="text-center">Note</th>
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
<div class="modal fade" id="createFingerprintModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create_fingerprint">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="createFingerprintModalLabel">New Fingerprint</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap bg-white">
                        <div class="form-group col-12">
                            <label for="f_name">Old Fingerprint</label>
                            <input class="form-control col-12" id="f_name" type="text" placeholder="Type fingerprint name" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="f_value">New Fingerprint</label>
                            <input class="form-control col-12" id="f_value" type="text" placeholder="Type fingerprint value" required />
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
<div class="modal fade" id="editFingerprintModal" tabindex="-1" role="dialog" aria-labelledby="editFingerprintModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="edit_fingerprint_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="editFingerprintModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap bg-white">
                        <input type="hidden" id="id" />
                        <div class="form-group col-12">
                            <label for="fname">Old Fingerprint</label>
                            <input class="form-control col-12" id="fname" type="text" placeholder="Type fingerprint name" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="fvalue">New Fingerprint</label>
                            <input class="form-control col-12" id="fvalue" type="text" placeholder="Type fingerprint value" required />
                        </div>
                        <div class="form-group col-12">
                            <label for="fnote">Note</label>
                            <textarea class="form-control col-12" id="fnote" type="text" placeholder="Type your note"></textarea>
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
<?php include('../main/footer1.php'); ?>

<script>
    document.title = 'HealthyCURE | Fingerprint';
    getFingerprints('fetch_fingerprints.php');

    $('#prname').select2({
        dropdownParent: $('#createFingerprintModal')
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
        // Create Fingerprint 
        const formCreateFingerprint = $('#create_fingerprint');

        if (formCreateFingerprint) {
            formCreateFingerprint.on('submit', function(e) {
                e.preventDefault();
                swal.fire({
                        title: "Are you sure?",
                        text: "To Create This Fingerprint!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willEdit) => {
                        if (willEdit.value === true) {
                            let f_name = $('#f_name').val();
                            let f_value = $('#f_value').val();
                            let note = $('#note').val();

                            const formData = new FormData();

                            formData.append('f_name', f_name);
                            formData.append('f_value', f_value);
                            formData.append('note', note);

                            fetch('create_fingerprint.php', {
                                method: 'POST',
                                body: formData
                            }).then(res => {
                                return res.json();
                            }).then(response => {
                                if (response.text === true) {
                                    $('#createFingerprintModal').modal('hide')
                                    $('#table_id').DataTable().ajax.reload(null, false)
                                    swal.fire("Create Done!", '', "success");
                                } else {
                                    swal.fire("Something wronge!", '', "error");
                                }
                            })
                        } else {
                            swal.fire("this fingerprint not created!", '', "warning");
                            $('#createFingerprintModal').modal('hide')
                        }

                        $('#f_name').val(null);
                        $('#f_value').val(null);
                        $('#note').val(null);

                    });
            });
        }

        // Edit Modal
        $(document).on('click', '#edit_fingerprint', function() {
            const headerModal = document.querySelector('#editFingerprintModalLabel');
            let id = $(this).data('id');
            let f_name = $(this).data('fname');
            let f_value = $(this).data('fvalue');
            let fnote = $(this).data('note');

            headerModal.textContent = 'Edit ' + f_name;

            $('#id').val(id);
            $('#fname').val(f_name);
            $('#fvalue').val(f_value);
            $('#fnote').val(fnote);
        })

        // Edit Form
        const formEdit = $('#edit_fingerprint_form');
        if (formEdit) {
            formEdit.on('submit', (e) => {
                e.preventDefault();
                $('#editFingerprintModal').modal('hide');
                swal.fire({
                    title: "Are you sure change this information?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willUpdate) => {
                    const formData = new FormData();

                    formData.append('id', $('#id').val());
                    formData.append('fname', $('#fname').val());
                    formData.append('fvalue', $('#fvalue').val());
                    formData.append('fnote', $('#fnote').val());

                    if (willUpdate.value === true) {
                        fetch('edit_fingerprint.php', {
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
                        swal.fire("Your fingerprint is not updated!");
                    }
                    formEdit[0].reset();
                })
            })
        }

    });
</script>
</body>

</html>