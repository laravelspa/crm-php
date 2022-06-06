document.title = 'HealthyCURE | Connections';
getRecords('fetch_dbs.php');
$(document).ready(function() {

    // Create Form
    const formCreate = $('#create_database');
    if (formCreate) {
        formCreate.on('submit', function(e) {
            e.preventDefault();
            var dbname = $('#dbname').val(),
                dbtable = $('#dbtable').val(),
                dbuser = $('#dbuser').val(),
                dbuserpassword = $('#dbuserpassword').val(),
                network_ads = $('#network_ads').val(),
                prname = $('#prname').val(),
                prprice = $('#prprice').val(),
                prcurrency = $('#prcurrency').val(),
                comment = $('#comment').val(),
                landing_url = $('#landing_url').val();

            swal.fire({
                title: "Are you sure to create this Database?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willCreate) => {
                $('#createDatabase').modal('hide');
                const formData = new FormData();

                formData.append('dbname', dbname);
                formData.append('dbtable', dbtable);
                formData.append('dbuser', dbuser);
                formData.append('dbuserpassword', dbuserpassword);
                formData.append('network_ads', network_ads);
                formData.append('landing_url', landing_url);
                formData.append('prname', prname);
                formData.append('prprice', prprice);
                formData.append('prcurrency', prcurrency);
                formData.append('comment', comment);
                if (willCreate.value === true) {
                    fetch('create_database.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if (response.text === true) {
                            formCreate[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("Created Database Done!", '', "success");
                        } else {
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                } else {
                    swal.fire("Your database is not created!");
                }
            })
        });
    }

    // Edit Modal
    $(document).on('click', '#edit_db', function() {
        const headerModal = document.querySelector('#editDatabaseModalLabel');
        var id = $(this).data('id');
        var dbname = $(this).data('dbname');
        var dbtable = $(this).data('dbtable');
        var dbuser = $(this).data('dbuser');
        var dbpassword = $(this).data('dbpass');
        var network_ads = $(this).data('nads');
        var landing_url = $(this).data('lur');
        var prname = $(this).data('prn');
        var prprice = $(this).data('prp');
        var prcurrency = $(this).data('prc');
        var comment = $(this).data('com');
        headerModal.textContent = 'Edit ' + dbname;
        $('.edit_id').val(id);
        $('.newdbname').val(dbname);
        $('.newdbtable').val(dbtable);
        $('.newdbuser').val(dbuser);
        $('.newdbuserpassword').val(dbpassword);
        $('.newnetworkads').val(network_ads);
        $('.newlandingurl').val(landing_url);
        $('.newprname').val(prname);
        $('#newprname').select2({
            dropdownParent: $('#editDatabaseModal')
        }).trigger('change')
        $('.newprprice').val(prprice);
        $('.newprcurrency').val(prcurrency);
        $('.newcomment').val(comment);
    })

    // Edit Form
    const formEdit = $('#edit_database');
    if (formEdit) {
        formEdit.on('submit', (e) => {
            e.preventDefault();
            $('#editDatabaseModal').modal('hide');
            swal.fire({
                title: "Are you sure change this information?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willUpdate) => {
                var editId = $('.edit_id').val();
                var newdbname = $('.newdbname').val();
                var newdbtable = $('.newdbtable').val();
                var newdbuser = $('.newdbuser').val();
                var newdbuserpassword = $('.newdbuserpassword').val();
                var newnetworkads = $('.newnetworkads').val();
                var newlandingurl = $('.newlandingurl').val();
                var newprname = $('.newprname').val();
                var newprprice = $('.newprprice').val();
                var newprcurrency = $('.newprcurrency').val();
                var newcomment = $('.newcomment').val();

                const formData = new FormData();

                formData.append('id', editId);
                formData.append('dbname', newdbname);
                formData.append('dbtable', newdbtable);
                formData.append('dbuser', newdbuser);
                formData.append('dbpassword', newdbuserpassword);
                formData.append('networkads', newnetworkads);
                formData.append('landingurl', newlandingurl);
                formData.append('prname', newprname);
                formData.append('prprice', newprprice);
                formData.append('prcurrency', newprcurrency);
                formData.append('comment', newcomment);
                if (willUpdate.value === true) {
                    fetch('edit_database.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if (response.text === true) {
                            formEdit[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("Updated Done!", '', "success");
                        } else {
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                } else {
                    swal.fire("Your database is not updated!");
                }
            })
        })
    }

});