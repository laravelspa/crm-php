document.title = 'المستخدمين | الصفحة الرئيسية';

getRecords('fetch_users.php');
$(document).ready(function () {
    // Edit Modal
    $(document).on('click', '#edit_user', function () {
        const headerModal = document.querySelector('#editUserModalLabel');
        let id = $(this).data('id');
        let name = $(this).data('name');
        let password = $(this).data('pass');
        let is_admin = $(this).data('admin');

        headerModal.textContent = 'تعديل المستخدم ' + name;

        $('.edit_id').val(id);
        $('.newname').val(name);
        $('.oldpassword').val(password);
        $('.newpassword').val();
        $('.newisadmin').val(is_admin);
    })

    // Edit Form
    const formEdit = $('#editUserForm');
    if (formEdit) {
        formEdit.on('submit', (e) => {
            e.preventDefault();
            $('#editUserModal').modal('hide');
            swal.fire({
                title: "هل أنت متأكد من القيام بهذه التعديلات?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willUpdate) => {
                var editId = $('.edit_id').val();
                var newname = $('.newname').val();
                var oldpassword = $('.oldpassword').val();
                var newpassword = $('.newpassword').val();
                var newisadmin = $('.newisadmin').val();

                const formData = new FormData();

                formData.append('id', editId);
                formData.append('name', newname);
                formData.append('password', newpassword);
                formData.append('oldpassword', oldpassword);
                formData.append('is_admin', newisadmin);

                if (willUpdate.value === true) {
                    fetch('edit_user.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if (response.text === true) {
                            formEdit[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("تم التحديث بنجاح!", '', "success");
                        } else {
                            swal.fire("هناك خطأ ما!", '', "error");
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
    if (formCreate) {
        formCreate.on('submit', function (e) {
            e.preventDefault();
            var name = $('#name').val(),
                password = $('#password').val(),
                is_admin = $('#is_admin').val();

            $('#newUserModal').modal('hide');
            swal.fire({
                title: "هل أنت متأكد من إنشاء هذا المستخدم?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willCreate) => {
                const formData = new FormData();
                formData.append('name', name);
                formData.append('password', password);
                formData.append('is_admin', is_admin);

                if (willCreate.value === true) {
                    fetch('create_user.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if (response.text === true) {
                            formCreate[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("تم إنشاء المستخدم بنجاح!", '', "success");
                        } else {
                            swal.fire("هناك خطأ ما!", '', "error");
                        }
                    })
                } else {
                    swal.fire("لم يتم إنشاء هذا المستخدم بسبب خطأ ما!", '', "warning");
                }
            })
        });
    }
});