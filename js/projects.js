document.title = 'HealthyCURE | Projects';
getRecords('fetch_projects.php', '', '', '', '', '', '', '#table_id');
getRecords('fetch_dublicate.php', '', '', '', '', '', '', '#table_dublicate');

$('#employee').select2({ width: '100%' });
$(document).ready(function() {
    const formCreateProject = $('#create_project_form');
    if (formCreateProject) {
        // Create Project Excel sheet
        const LeadsExcel = $('#create_leads_excel');
        LeadsExcel.on('change', () => {
            readXlsxFile(LeadsExcel[0].files[0]).then((data) => {
                sessionStorage.setItem('create_add_excel', JSON.stringify(data));
            })
        })
        formCreateProject.on('submit', (e) => {
            e.preventDefault();
            const ProjectName = $('#product_name').val();
            const ProductName = $('#product_name').val();
            const ProductPrice = $('#product_price').val();
            const ProductCurrency = $('#currency').val();
            const Employee = $('#employee').val();
            const AdminName = $('#admin_name').val();
            const FirstTime = $('#first_time').val();
            // Excel File  Error 
            if (LeadsExcel[0].files.length === 0) {
                swal.fire("Please Select Excel File!", '', "warning");
            } else {
                if (sessionStorage.getItem('create_add_excel') !== null) {
                    var formdata = new FormData();
                    formdata.append('project_name', ProjectName);
                    formdata.append('product_name', ProductName);
                    formdata.append('product_price', ProductPrice);
                    formdata.append('product_currency', ProductCurrency);
                    formdata.append('employee_id', Employee);
                    formdata.append('admin_name', AdminName);
                    formdata.append('first_time', FirstTime);
                    formdata.append('leads', sessionStorage.getItem('create_add_excel'));
                    $('#createProjectModal').modal('hide');

                    fetch('insert_project.php', {
                        method: 'POST',
                        body: formdata
                    }).then(res => {
                        return res.json();
                    }).then(r => {
                        if (r.text === true) {
                            swal.fire("Created Done!", ProjectName, "success");
                            $('#table_id').DataTable().ajax.reload();
                            $('#table_dublicate').DataTable().ajax.reload();
                            formCreateProject[0].reset();
                            sessionStorage.removeItem('create_add_excel');
                        } else {
                            $('#table_id').DataTable().ajax.reload();
                            $('#table_dublicate').DataTable().ajax.reload();
                            formCreateProject[0].reset();
                            swal.fire("Something wronge!", '', "error");
                            sessionStorage.removeItem('create_add_excel');
                        }
                    })
                } else {
                    swal.fire("Please Select Excel File!", '', "warning");
                }
            }
        });
    }

    // Edit Project Modal
    $(document).on('click', '#edit_project', function(e) {
        const headerEditModal = document.querySelector('#editModalLabel');
        let prn = $(this).data('prn');
        let prp = $(this).data('prp');
        let prc = $(this).data('prc');

        headerEditModal.textContent = 'Edit ' + prn;

        $('#product_n').val(prn);
        $('#product_p').val(prp);
        $('#product_c').val(prc);

        const formEditProject = document.querySelector('form[name=edit_project]');

        if (formEditProject) {
            formEditProject.addEventListener('submit', (e) => {
                e.preventDefault();
                var prnn = document.getElementById('product_n').value;
                var prpn = document.getElementById('product_p').value;
                var prcn = document.getElementById('product_c').value;

                const formData = new FormData();

                formData.append('oldproject_name', prn);
                formData.append('editproject_name', prnn);
                formData.append('editproduct_name', prnn);
                formData.append('editproduct_price', prpn);
                formData.append('editproduct_currency', prcn);

                fetch('/update.php', {
                    method: 'POST',
                    body: formData
                }).then(res => {
                    return res.json();
                }).then(response => {
                    if (response.text === true) {
                        $('#editModal').modal('hide');
                        $('#table_id').DataTable().ajax.reload();
                        swal.fire("Edit Done!", '', "success");
                    } else {
                        swal.fire("Something wronge!", '', "error");
                    }
                })
            })
        }
    });

    $(document).on('click', '#add_lead', function() {
        let pn = $(this).data('pn');
        let prn = $(this).data('prn');
        let prp = $(this).data('prp');
        let prc = $(this).data('prc');

        $('#hidden_pn').val(pn);
        $('#hidden_prn').val(prn);
        $('#hidden_prp').val(prp);
        $('#hidden_prc').val(prc);

        const formAddLead = $('#add_leads_form');

        if (formAddLead) {
            const AddLeadsExcel = $('#add_leads_excel');
            AddLeadsExcel[0].value = ''
            var count = 0;
            // Add Lead In Project By Excel sheet
            $(document).on('change', '#add_leads_excel', () => {
                count++
                readXlsxFile(AddLeadsExcel[0].files[0]).then((data) => {
                    if (count === 1) {
                        sessionStorage.setItem('add_excel', JSON.stringify(data));
                    }
                })
            })
            formAddLead.on('submit', (e) => {
                e.preventDefault();
                // Excel File  Error 
                if (AddLeadsExcel[0].files.length === 0) {
                    swal.fire("Please Select Excel File!", '', "warning");
                } else {
                    $('#AddLeadModal').modal('hide');
                    if (count === 1) {
                        var pn = document.getElementById('hidden_pn').value;
                        var prn = document.getElementById('hidden_prn').value;
                        var prp = document.getElementById('hidden_prp').value;
                        var prc = document.getElementById('hidden_prc').value;
                        var emp = document.getElementById('add_employee').value;
                        const AdminName = document.getElementById('admin_name_1').value;

                        const formData = new FormData();

                        formData.append('project_name', pn);
                        formData.append('product_name', prn);
                        formData.append('product_price', prp);
                        formData.append('product_currency', prc);
                        formData.append('employee_id', emp);
                        formData.append('admin_name', AdminName);
                        formData.append('leads', sessionStorage.getItem('add_excel'));

                        fetch('insert_project.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            if (response.text === true) {
                                $('#table_id').DataTable().ajax.reload();
                                $('#table_dublicate').DataTable().ajax.reload();
                                swal.fire("Add Lead Done!", '', "success");
                                AddLeadsExcel[0].value = ''
                                sessionStorage.removeItem('add_excel');
                            } else {
                                swal.fire("Something wronge!", '', "error");
                                AddLeadsExcel[0].value = ''
                                sessionStorage.removeItem('add_excel');
                            }
                        })
                    }
                }
            })
        }
    });
});


function deleteProject(name = '', url = '', table_html = '', table_db = '') {
    const formData = new FormData();
    formData.append('project_name', name);
    formData.append('table_name', table_db);
    swal.fire({
            title: "Are you sure?",
            text: "Once deleted, You will remove all leads!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete.value === true) {
                fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(res => {
                    return res.json();
                }).then(response => {
                    if (response.text === true) {
                        $('#table_id').DataTable().ajax.reload();
                        $('#table_dublicate').DataTable().ajax.reload();
                        swal.fire("Your project has been deleted!", '', 'success');
                    } else {
                        swal.fire("Something wronge!", '', "error");
                    }
                })
            } else {
                swal.fire("Your project is safe!", '', "warning");
            }
        });
}