document.title = 'HealthyCURE | Uesrs';
$('#projects').select2();
getRecords('fetch_users.php');
$(document).ready(function() {      
    // Edit Modal
    $(document).on('click','#edit_admin', function(){
        const headerModal = document.querySelector('#editAdminModalLabel');
        var id = $(this).data('id');
        var name = $(this).data('name');
        var password = $(this).data('pass');
        var permission = $(this).data('permission');
        var supervisor = $(this).data('sup');
        var wall = $(this).data('wall');
        var projects = $(this).data('projects');
        var location = $(this).data('location');
        var split = '';
        if(projects !== '') {
            split = projects.split(",")
        }

        headerModal.textContent = 'Edit ' + name;
        
        $('.edit_id').val(id);
        $('.newname').val(name);
        $('.oldpassword').val(password);
        $('.newpassword').val();
        $('.newpermission').val(permission);
        $('.newwall').val(wall);
        $('.newprojects').val(split);
        $('#newprojects').select2({'val': split});
        $('.newlocation').val(location);
        $('#newsupervisor').val(supervisor);

        var selectVal   = $('#newpermission').val();
        var supervisorCont = $('.newsupervisor-container')[0];
        var wallCont = $('.newwall-container')[0];
        var projectsCont = $('.newprojects-container')[0];
        var locationCont = $('.newlocation-container')[0];
            
        if(selectVal === '0' || selectVal === '1' || selectVal === '3') {
            supervisorCont.style.display = 'none';
            wallCont.style.display = 'none';
            projectsCont.style.display = 'none';
            locationCont.style.display = 'none';
            $('.newsupervisor').removeAttr('required');
            $('.newwall').removeAttr('required');
            $('.newprojects').removeAttr('required');
            $('.newlocation').removeAttr('required');
        } 
        if (selectVal === '2') {
            supervisorCont.style.display = 'none';
            locationCont.style.display = 'none';
            wallCont.style.display = 'block';
            projectsCont.style.display = 'block';
            $('.newsupervisor').removeAttr('required');
            $('.newlocation').removeAttr('required');
            $('.newprojects').removeAttr('required');
            $('.newwall').attr('required', 'required');
        }
        if(selectVal === '4') {
            fetch('ajax_fetch.php', {
                method:'post'
            }).then(response => {
                return response.json();
            }).then(res => {
                if(res.supervisors) {
                    var html = '';
                    for (var i = 0; i < res.supervisors.length; i++) {
                        html += '<option value="'+res.supervisors[i].id+'">'+res.supervisors[i].name+'</option>'
                    }
                    $('#newsupervisor').html(html)
                    $('#newsupervisor').val(supervisor);                    
                }
            })
            supervisorCont.style.display = 'block';
            wallCont.style.display = 'none';
            locationCont.style.display = 'none';
            projectsCont.style.display = 'none';
            $('.newsupervisor').attr('required', 'required');
            $('.newprojects').removeAttr('required');
            $('.newwall').removeAttr('required');
            $('.newlocation').removeAttr('required');
        }
        if (selectVal === '5') {
            fetch('ajax_fetch.php', {
                method:'post'
            }).then(response => {
                return response.json();
            }).then(res => {
                if(res.supervisors_assistant) {
                    var html = '';
                    for (var i = 0; i < res.supervisors_assistant.length; i++) {
                        html += '<option value="'+res.supervisors_assistant[i].id+'">'+res.supervisors_assistant[i].name+'</option>'
                    }
                    $('#newsupervisor').html(html)
                    $('#newsupervisor').val(supervisor);                    
                }
            })
            supervisorCont.style.display = 'block';
            wallCont.style.display = 'none';
            projectsCont.style.display = 'none';
            locationCont.style.display = 'block';
            $('.newsupervisor').attr('required', 'required');
            $('.newwall').removeAttr('required');
            $('.newprojects').removeAttr('required');
            $('.newlocation').attr('required', 'required');
        }
        if (selectVal === '6') {
            fetch('ajax_fetch.php', {
                method:'post'
            }).then(response => {
                return response.json();
            }).then(res => {
                if(res.supervisors_call) {
                    var html = '';
                    for (var i = 0; i < res.supervisors_call.length; i++) {
                        html += '<option value="'+res.supervisors_call[i].id+'">'+res.supervisors_call[i].name+'</option>'
                    }
                    $('#newsupervisor').html(html)
                    $('#newsupervisor').val(supervisor)
                }
            })
            supervisorCont.style.display = 'block';
            wallCont.style.display = 'none';
            projectsCont.style.display = 'none';
            locationCont.style.display = 'none';
            $('.newsupervisor').attr('required', 'required');
            $('.newwall').removeAttr('required');
            $('.newprojects').removeAttr('required');
            $('.newlocation').removeAttr('required');
        }    
    })
    
    // Edit Form
    const formEdit = $('#edit_user');
    if(formEdit) {
        $(document).on('change','#newpermission', function(e){ 
            selectVal   = $('#newpermission').val();
            supervisorCont = $('.newsupervisor-container')[0];
            wallCont = $('.newwall-container')[0];
            projectsCont = $('.newprojects-container')[0];
            locationCont = $('.newlocation-container')[0];
    
            if(selectVal === '0' || selectVal === '1' || selectVal === '3') {
                supervisorCont.style.display = 'none';
                wallCont.style.display = 'none';
                projectsCont.style.display = 'none';
                locationCont.style.display = 'none';
                $('.newsupervisor').removeAttr('required');
                $('.newwall').removeAttr('required');
                $('.newprojects').removeAttr('required');
                $('.newlocation').removeAttr('required');
            } 
            if (selectVal === '2') {
                supervisorCont.style.display = 'none';
                locationCont.style.display = 'none';
                wallCont.style.display = 'block';
                projectsCont.style.display = 'block';
                $('.newsupervisor').removeAttr('required');
                $('.newlocation').removeAttr('required');
                $('.newprojects').removeAttr('required');
                $('.newwall').attr('required', 'required');
            }
            if(selectVal === '4') {
                $('.save').prop('disabled', true);
                fetch('ajax_fetch.php', {
                    method:'post'
                }).then(response => {
                    return response.json();
                }).then(res => {
                    if(res.supervisors) {
                        var html = '';
                        for (var i = 0; i < res.supervisors.length; i++) {
                            html += '<option value="'+res.supervisors[i].id+'">'+res.supervisors[i].name+'</option>'
                        }
                        $('#newsupervisor').html(html)
                        $('#newsupervisor').val(supervisor);                    
                        $('.save').prop('disabled', false);
                    }
                })
                supervisorCont.style.display = 'block';
                wallCont.style.display = 'none';
                locationCont.style.display = 'none';
                projectsCont.style.display = 'none';
                $('.newsupervisor').attr('required', 'required');
                $('.newprojects').removeAttr('required');
                $('.newwall').removeAttr('required');
                $('.newlocation').removeAttr('required');
            }
            if (selectVal === '5') {
                $('.save').prop('disabled', true);
                fetch('ajax_fetch.php', {
                    method:'post'
                }).then(response => {
                    return response.json();
                }).then(res => {
                    if(res.supervisors_assistant) {
                        var html = '';
                        for (var i = 0; i < res.supervisors_assistant.length; i++) {
                            html += '<option value="'+res.supervisors_assistant[i].id+'">'+res.supervisors_assistant[i].name+'</option>'
                        }
                        $('#newsupervisor').html(html)
                        $('#newsupervisor').val(supervisor);                    
                        $('.save').prop('disabled', false);
                    }
                })
                supervisorCont.style.display = 'block';
                wallCont.style.display = 'none';
                projectsCont.style.display = 'none';
                locationCont.style.display = 'block';
                $('.newsupervisor').attr('required', 'required');
                $('.newwall').removeAttr('required');
                $('.newprojects').removeAttr('required');
                $('.newlocation').attr('required', 'required');
            }
            if (selectVal === '6') {
                $('.save').prop('disabled', true);
                fetch('ajax_fetch.php', {
                    method:'post'
                }).then(response => {
                    return response.json();
                }).then(res => {
                    if(res.supervisors_call) {
                        var html = '';
                        for (var i = 0; i < res.supervisors_call.length; i++) {
                            html += '<option value="'+res.supervisors_call[i].id+'">'+res.supervisors_call[i].name+'</option>'
                        }
                        $('#newsupervisor').html(html)
                        $('#newsupervisor').val(supervisor)
                        $('.save').prop('disabled', false);
                    }
                })
                supervisorCont.style.display = 'block';
                wallCont.style.display = 'none';
                projectsCont.style.display = 'none';
                locationCont.style.display = 'none';
                $('.newsupervisor').attr('required', 'required');
                $('.newwall').removeAttr('required');
                $('.newprojects').removeAttr('required');
                $('.newlocation').removeAttr('required');
            }
        });
        formEdit.on('submit', (e) => {
            e.preventDefault();
            $('#editAdminModal').modal('hide');
            swal.fire({
              title: "Are you sure change this information?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willUpdate) => {
                var editId = $('.edit_id').val();
                var newname = $('.newname').val();
                var oldpassword = $('.oldpassword').val();
                var newpassword = $('.newpassword').val();
                var newpermission = $('.newpermission').val();
                var newsupervisor = $('.newsupervisor').val();
                var newwall = $('.newwall').val();
                var newprojects = $('.newprojects').val();
                var newlocation = $('.newlocation').val();
                
                const formData = new FormData();
                
                formData.append('id', editId);
                formData.append('name', newname);
                formData.append('password', newpassword);
                formData.append('oldpassword', oldpassword);
                formData.append('permission', newpermission);
                formData.append('supervisor', newsupervisor);
                formData.append('wall', newwall);
                formData.append('projects', newprojects);
                formData.append('location', newlocation);
                if (willUpdate.value === true) { 
                    fetch('edit_user.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if(response.text === true) {
                            formEdit[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("Updated Done!", '', "success");
                        } else {
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                } else {
                    swal.fire("Your lead not updated!!", '', "warning");
                } 
            })
        })
    }

    // Create Form
    const formCreate = $('#create_user');
    if(formCreate) {
        selectVal = $('#permission').val();
        supervisorCont = $('.supervisor-container')[0];
        wallCont = $('.wall-container')[0];
        projectsCont = $('.projects-container')[0];
        locationCont = $('.location-container')[0];
        if(selectVal === '0') {
            supervisorCont.style.display = 'none';
            wallCont.style.display = 'none';
            projectsCont.style.display = 'none';
            locationCont.style.display = 'none';
        } 
        $(document).on('change','#permission', function(e){ 
            selectVal   = $('#permission').val();
            supervisorCont = $('.supervisor-container')[0];
            wallCont = $('.wall-container')[0];
            projectsCont = $('.projects-container')[0];
            locationCont = $('.location-container')[0];
    
            if(selectVal === '0' || selectVal === '1' || selectVal === '3') {
                supervisorCont.style.display = 'none';
                wallCont.style.display = 'none';
                projectsCont.style.display = 'none';
                locationCont.style.display = 'none';
                $('.supervisor').removeAttr('required');
                $('.wall').removeAttr('required');
                $('.projects').removeAttr('required');
                $('.location').removeAttr('required');
            } 
            if (selectVal === '2') {
                supervisorCont.style.display = 'none';
                locationCont.style.display = 'none';
                wallCont.style.display = 'block';
                projectsCont.style.display = 'block';
                $('.supervisor').removeAttr('required');
                $('.location').removeAttr('required');
                $('.projects').removeAttr('required');
                $('.wall').attr('required', 'required');
            }
            if(selectVal === '4') {
                $('.create').prop('disabled', true);
                fetch('ajax_fetch.php', {
                    method:'post'
                }).then(response => {
                    return response.json();
                }).then(res => {
                    if(res.supervisors) {
                        var html = '';
                        for (var i = 0; i < res.supervisors.length; i++) {
                            html += '<option value="'+res.supervisors[i].id+'">'+res.supervisors[i].name+'</option>'
                        }
                        $('#supervisor').html(html)
                        $('.create').prop('disabled', false);
                    }
                })
                supervisorCont.style.display = 'block';
                wallCont.style.display = 'none';
                locationCont.style.display = 'none';
                projectsCont.style.display = 'none';
                $('.supervisor').attr('required', 'required');
                $('.projects').removeAttr('required');
                $('.wall').removeAttr('required');
                $('.location').removeAttr('required');
            }
            if (selectVal === '5') {
                $('.create').prop('disabled', true);
                fetch('ajax_fetch.php', {
                    method:'post'
                }).then(response => {
                    return response.json();
                }).then(res => {
                    if(res.supervisors_assistant) {
                        var html = '';
                        for (var i = 0; i < res.supervisors_assistant.length; i++) {
                            html += '<option value="'+res.supervisors_assistant[i].id+'">'+res.supervisors_assistant[i].name+'</option>'
                        }
                        $('#supervisor').html(html)
                        $('.create').prop('disabled', false);
                    }
                })
                supervisorCont.style.display = 'block';
                wallCont.style.display = 'none';
                projectsCont.style.display = 'none';
                locationCont.style.display = 'block';
                $('.supervisor').attr('required', 'required');
                $('.wall').removeAttr('required');
                $('.projects').removeAttr('required');
                $('.location').attr('required', 'required');
            }
            if (selectVal === '6') {
                $('.create').prop('disabled', true);
                fetch('ajax_fetch.php', {
                    method:'post'
                }).then(response => {
                    return response.json();
                }).then(res => {
                    if(res.supervisors_call) {
                        var html = '';
                        for (var i = 0; i < res.supervisors_call.length; i++) {
                            html += '<option value="'+res.supervisors_call[i].id+'">'+res.supervisors_call[i].name+'</option>'
                        }
                        $('#supervisor').html(html)
                        $('.create').prop('disabled', false);
                    }
                })
                supervisorCont.style.display = 'block';
                wallCont.style.display = 'none';
                projectsCont.style.display = 'none';
                locationCont.style.display = 'none';
                $('.supervisor').attr('required', 'required');
                $('.wall').removeAttr('required');
                $('.projects').removeAttr('required');
                $('.location').removeAttr('required');
            }
        });
        formCreate.on('submit', function(e){ 
            e.preventDefault();
            var name = $('#name').val(),
                password = $('#password').val(),
                permission = $('#permission').val(),
                supervisor = $('#supervisor').val(),
                wall = $('#wall').val(),
                projects = $('#projects').val(),                
                location = $('#location').val();                
            $('#newUserModal').modal('hide');
            swal.fire({
              title: "Are you sure to create this user?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willCreate) => {
                const formData = new FormData();
                formData.append('name', name);
                formData.append('password', password);
                formData.append('permission', permission);
                formData.append('supervisor', supervisor);
                formData.append('wall', wall);
                formData.append('projects', projects);
                formData.append('location', location);
                if (willCreate.value === true) { 
                    fetch('create_user.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if(response.text === true) {
                            supervisorCont.style.display = 'none';
                            wallCont.style.display = 'none';
                            projectsCont.style.display = 'none';
                            projects = '';            
                            formCreate[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("Created Done!", '', "success");
                        } else {
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                } else {
                    swal.fire("not create this user!", '', "warning");
                } 
            })
        });
    }
});