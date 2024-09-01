document.title = 'الشهادات | الصفحة الرئيسية';

getRecords('fetch_certificates.php');

$(document).ready(function () {
    // Print Modal
    $(document).on('click', '#print_certificate', function () {
        const headerModal = document.querySelector('#printCertificateModalLabel');

        let serial_number = $(this).data('serial_number')
        let date = $(this).data('date')
        let facility_name = $(this).data('facility_name')
        let facility_activity = $(this).data('facility_activity')
        let facility_address = $(this).data('facility_address')
        let mobile = $(this).data('mobile')
        let commercial_register = $(this).data('commercial_register')
        let no_civil_registry = $(this).data('no_civil_registry')
        let internal_cameras = $(this).data('internal_cameras')
        let external_cameras = $(this).data('external_cameras')
        let recording_device = $(this).data('recording_device')
        let recording_duration = $(this).data('recording_duration')
        let storage_disk = $(this).data('storage_disk')
        let display = $(this).data('display')
        let other_specifications = $(this).data('other_specifications')
        
        headerModal.textContent = 'طباعة الشهادة رقم ' + serial_number;
        
        $('.printserial_number').val(serial_number);
        $('.printdate').html(date);
        $('.printfacility_name').html(facility_name);
        $('.printfacility_activity').html(facility_activity);
        $('.printfacility_address').html(facility_address);
        $('.printmobile').html(mobile);
        $('.printcommercial_register').html(commercial_register);
        $('.printno_civil_registry').html(no_civil_registry);
        $('.printinternal_cameras').html(internal_cameras);
        $('.printexternal_cameras').html(external_cameras);
        $('.printrecording_device').html(recording_device);
        $('.printrecording_duration').html(recording_duration);
        $('.printstorage_disk').html(storage_disk);
        $('.printdisplay').html(display);
        $('.printother_specifications').html(other_specifications);
    })

    // Print Certificate
    var formPrint = $('#printCertificateForm')

    if (formPrint) {
        formPrint.on('submit', async (e) => {
            e.preventDefault();

            swal.fire({
                title: "هل تريد طباعة تلك الشهادة?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willUpdate) => {
                const formData = new FormData();
                
                formData.append('serial_number', $('.printserial_number').val());
                formData.append('date', $('.printdate').html());
                formData.append('facility_name', $('.printfacility_name').html());
                formData.append('facility_activity', $('.printfacility_activity').html());
                formData.append('facility_address', $('.printfacility_address').html());
                formData.append('mobile', $('.printmobile').html());
                formData.append('commercial_register', $('.printcommercial_register').html());
                formData.append('no_civil_registry', $('.printno_civil_registry').html());
                formData.append('internal_cameras', $('.printinternal_cameras').html());
                formData.append('external_cameras', $('.printexternal_cameras').html());
                formData.append('recording_device', $('.printrecording_device').html());
                formData.append('recording_duration', $('.printrecording_duration').html());
                formData.append('storage_disk', $('.printstorage_disk').html());
                formData.append('display', $('.printdisplay').html());
                formData.append('other_specifications', $('.printother_specifications').html());

                if (willUpdate.value === true) {
                    fetch('print_certificate.php', {
                        method: 'POST',
                        body: formData,
                    }).then(res => {
                        return res.json()
                    }).then(response => {
                        const link = document.createElement("a");
                        link.href = window.location.origin + window.location.pathname.slice(0, window.location.pathname.lastIndexOf('/')) + '/' + response.filepath
                        link.setAttribute("download", `${response.filename}.pdf`);
                        document.body.appendChild(link);
                        link.click();

                        swal.fire("تمت الطباعة بنجاح!", '', "success");

                    })
                } else {
                    swal.fire("لم تتم عملية الطباعة!", '', "warning");
                }
            })

            $('#printCertificateModal').modal('hide');

        })

    }

    // Edit Modal
    $(document).on('click', '#edit_certificate', function () {
        const headerModal = document.querySelector('#editCertificateModalLabel');
        let id = $(this).data('id');
        let serial_number = $(this).data('serial_number')
        let date = $(this).data('date')
        let facility_name = $(this).data('facility_name')
        let facility_activity = $(this).data('facility_activity')
        let facility_address = $(this).data('facility_address')
        let mobile = $(this).data('mobile')
        let commercial_register = $(this).data('commercial_register')
        let no_civil_registry = $(this).data('no_civil_registry')
        let internal_cameras = $(this).data('internal_cameras')
        let external_cameras = $(this).data('external_cameras')
        let recording_device = $(this).data('recording_device')
        let recording_duration = $(this).data('recording_duration')
        let storage_disk = $(this).data('storage_disk')
        let display = $(this).data('display')
        let other_specifications = $(this).data('other_specifications')

        headerModal.textContent = 'تعديل الشهادة رقم ' + serial_number;

        $('.edit_id').val(id);
        $('.newdate').val(date);
        $('.newfacility_name').val(facility_name);
        $('.newfacility_activity').val(facility_activity);
        $('.newfacility_address').val(facility_address);
        $('.newmobile').val(mobile);
        $('.newcommercial_register').val(commercial_register);
        $('.newno_civil_registry').val(no_civil_registry);
        $('.newinternal_cameras').val(internal_cameras);
        $('.newexternal_cameras').val(external_cameras);
        $('.newrecording_device').val(recording_device);
        $('.newrecording_duration').val(recording_duration);
        $('.newstorage_disk').val(storage_disk);
        $('.newdisplay').val(display);
        $('.newother_specifications').val(other_specifications);
    })

    // Edit Form
    const formEdit = $('#editCertificateForm');
    if (formEdit) {
        formEdit.on('submit', (e) => {
            e.preventDefault();
            $('#editCertificateModal').modal('hide');
            swal.fire({
                title: "هل أنت متأكد من القيام بهذه التعديلات?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willUpdate) => {
                var editId = $('.edit_id').val();
                var newdate = $('.newdate').val();
                var newfacility_name = $('.newfacility_name').val();
                var newfacility_activity = $('.newfacility_activity').val();
                var newfacility_address = $('.newfacility_address').val();
                var newmobile = $('.newmobile').val();
                var newcommercial_register = $('.newcommercial_register').val();
                var newno_civil_registry = $('.newno_civil_registry').val();
                var newinternal_cameras = $('.newinternal_cameras').val();
                var newexternal_cameras = $('.newexternal_cameras').val();
                var newrecording_device = $('.newrecording_device').val();
                var newrecording_duration = $('.newrecording_duration').val();
                var newstorage_disk = $('.newstorage_disk').val();
                var newdisplay = $('.newdisplay').val();
                var newother_specifications = $('.newother_specifications').val();

                const formData = new FormData();

                formData.append('id', editId);
                formData.append('date', newdate);
                formData.append('facility_name', newfacility_name);
                formData.append('facility_activity', newfacility_activity);
                formData.append('facility_address', newfacility_address);
                formData.append('mobile', newmobile);
                formData.append('commercial_register', newcommercial_register);
                formData.append('no_civil_registry', newno_civil_registry);
                formData.append('internal_cameras', newinternal_cameras);
                formData.append('external_cameras', newexternal_cameras);
                formData.append('recording_device', newrecording_device);
                formData.append('recording_duration', newrecording_duration);
                formData.append('storage_disk', newstorage_disk);
                formData.append('display', newdisplay);
                formData.append('other_specifications', newother_specifications);

                if (willUpdate.value === true) {
                    fetch('edit_certificate.php', {
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
                    swal.fire("لم يتم التحديث بنجاح!!", '', "warning");
                }
            })
        })
    }

    // Create Form
    const formCreate = $('#create_certificate');
    if (formCreate) {
        formCreate.on('submit', function (e) {
            e.preventDefault();
            var date = $('#date').val(),
                facility_name = $('#facility_name').val(),
                facility_activity = $('#facility_activity').val(),
                facility_address = $('#facility_address').val(),
                mobile = $('#mobile').val(),
                commercial_register = $('#commercial_register').val(),
                no_civil_registry = $('#no_civil_registry').val(),
                internal_cameras = $('#internal_cameras').val(),
                external_cameras = $('#external_cameras').val(),
                recording_device = $('#recording_device').val(),
                recording_duration = $('#recording_duration').val(),
                storage_disk = $('#storage_disk').val(),
                display = $('#display').val(),
                other_specifications = $('#other_specifications').val();

            $('#newCertificateModal').modal('hide');
            swal.fire({
                title: "هل أنت متأكد من إنشاء هذه الشهادة?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willCreate) => {
                const formData = new FormData();
                formData.append('date', date);
                formData.append('facility_name', facility_name);
                formData.append('facility_activity', facility_activity);
                formData.append('facility_address', facility_address);
                formData.append('mobile', mobile);
                formData.append('commercial_register', commercial_register);
                formData.append('no_civil_registry', no_civil_registry);
                formData.append('internal_cameras', internal_cameras);
                formData.append('external_cameras', external_cameras);
                formData.append('recording_device', recording_device);
                formData.append('recording_duration', recording_duration);
                formData.append('storage_disk', storage_disk);
                formData.append('display', display);
                formData.append('other_specifications', other_specifications);


                if (willCreate.value === true) {
                    fetch('create_certificate.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if (response.text === true) {
                            formCreate[0].reset();
                            $('#table_id').DataTable().ajax.reload(null, false);
                            swal.fire("تم إنشاء الشهادة بنجاح بنجاح!", '', "success");
                        } else {
                            swal.fire("هناك خطأ ما!", '', "error");
                        }
                    })
                } else {
                    swal.fire("لم يتم إنشاء هذه الشهادة بسبب خطأ ما!", '', "warning");
                }
            })
        });
    }
});