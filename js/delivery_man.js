$(document).ready(function () {
    // Tap active when page refresh
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        $('#myTab a[href="' + activeTab + '"]').tab('show');
    }
    $('#datetimepicker3').datetimepicker({
        format: 'YYYY-MM-DD',
    });
    $('#datetimepicker4').datetimepicker({
        format: 'LT'
    });
    var start = moment().subtract(29, 'days');
    var end = moment();
    var count = 0;
    var startDate = '';
    var endDate = '';
    function cb(start, end) {
        startDate = start.format('YYYY-MM-DD')
        endDate = end.format('YYYY-MM-DD')
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        "opens": "center",
        "alwaysShowCalendars": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);

    $(document).on('submit', '#filter_delivery', function (e) {
        e.preventDefault();
        count++;
        if (count === 1) {
            var destination = localStorage.getItem('activeTab');
            var date_first = startDate;
            var date_last = endDate;
            if (destination === '#new') {
                $('#new_orders').DataTable().destroy();
                getRecords('fetch_delivery_man/fetch_new_leads.php', '#new_orders', '', date_first, date_last, '');
            } else {
                $('#finish_orders').DataTable().destroy();
                getRecords('fetch_delivery_man/fetch_finish_leads.php', '#finish_orders', '', date_first, date_last, '');
            }
        }
        count = 0;
    })
})

getRecords('fetch_delivery_man/fetch_new_leads.php', '#new_orders');
getRecords('fetch_delivery_man/fetch_finish_leads.php', '#finish_orders');

function getRecords(url, table = '', emp = '', df = '', dl = '', ci = '') {
    $.fn.dataTable.ext.classes.sPageButton = 'paginate_custom_buttons';
    $(table).DataTable({
        dom: 'lBfrtip',
        stateSave: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: 'POST',
            "data": {
                emp_id: emp,
                date_first: df,
                date_last: dl,
                city: ci
            }
        },
        language: {
            search: '', searchPlaceholder: "Search.....",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>'
        },
        "lengthMenu": [[10,15,20,25,30, 50, 100, 200,300], [10,15,20,25,30, 50, 100, 200,300]],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        buttons: [
            {
                "extend": 'colvis',
                "text": "<i class='fas fa-eye'></i>",
                "title": '',
                "collectionLayout": 'fixed two-column',
                "className": "btn btn-sm btn-outline-dark",
                "bom": "true",
                init: function (api, node, config) {
                    $(node).removeClass("dt-button");
                }
            },
            {
                "extend": "csv",
                "text": "<i class='fas fa-file-csv'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-success",
                "charset": "utf-8",
                "bom": "true",
                init: function (api, node, config) {
                    $(node).removeClass("dt-button");
                }
            },
            {
                "extend": "excel",
                "text": "<i class='fas fa-file-excel'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-danger",
                "charset": "utf-8",
                "bom": "true",
                init: function (api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                "extend": "print",
                "text": "<i class='fas fa-file-pdf'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-primary",
                "charset": "utf-8",
                "bom": "true",
                init: function (api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [':visible']
                }
            },
            {
                "extend": "copy",
                "text": "<i class='fas fa-copy'></i>",
                "title": '',
                "filename": "Report Name",
                "className": "btn btn-sm btn-outline-info",
                "charset": "utf-8",
                "bom": "true",
                init: function (api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [':visible']
                }
            }
        ]
    });
}

// Update All (ِAdd Employee To Leads)
function updateAll(url, table, msg, emptyMsg, cancelMsg) {
    var selected = [];
    const checked = document.querySelectorAll('input[name=checkedList]:checked');
    var employee = $('#employee').val();
    var d_status = $('#d_status').val();
    var activeTab = localStorage.getItem('activeTab');
    for (i = 0; i < checked.length; i++) {
        if (checked[i].checked) {
            selected.push(checked[i].value)
        }
    }

    const formData = new FormData();
    formData.append('ids', selected);
    formData.append('table', table);
    formData.append('employee_id', employee);
    formData.append('d_status', d_status);
    formData.append('activeTab', activeTab);

    swal.fire({
        title: "Are you sure?",
        text: msg,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((Update) => {
            if (Update.value === true) {
                if (checked.length === 0) {
                    $('#addEmpToLeadModal').modal('hide');
                    swal.fire(emptyMsg, '', "warning");
                } else {
                    $('#addEmpToLeadModal').modal('hide');
                    fetch(url, {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(response => {
                        if (response.text === true) {
                            $('#stage_orders').DataTable().ajax.reload(null, false);
                            $('#new_orders').DataTable().ajax.reload(null, false);
                            $('#waiting_orders').DataTable().ajax.reload(null, false);
                            $('#ready_orders').DataTable().ajax.reload(null, false);
                            $('#finish_orders').DataTable().ajax.reload(null, false);
                            swal.fire("Re assign Done!", '', "success");
                        } else {
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                }
            } else {
                swal.fire(cancelMsg, '', 'warning');
                $('#addEmpToLeadModal').modal('hide');
            }
        })
}

function approvedOrder(id, approved, msg, cancelMsg) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('approved', approved);

    swal.fire({
        title: "Are you sure?",
        text: msg,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((Update) => {
            if (Update.value === true) {
                fetch('approved_order.php', {
                    method: 'POST',
                    body: formData
                }).then(res => {
                    return res.json();
                }).then(response => {
                    if (response.text === true) {
                        $('#new_orders').DataTable().ajax.reload(null, false);
                        $('#finish_orders').DataTable().ajax.reload(null, false);
                        swal.fire("Approved order Done!", '', "success");
                    } else {
                        swal.fire("Something wronge!", '', "error");
                    }
                })
            } else {
                swal.fire(cancelMsg, '', 'warning');
            }
        })
}

$(document).on('click', '#approved_btn', function () {
    var id = $(this).data('id');
    var approved = $(this).data('app');
    var msg = $(this).data('msg');
    var cancelMsg = $(this).data('cmsg');
    approvedOrder(id, approved, msg, cancelMsg)
})

$(document).on('click', '#canceld_btn', function () {
    var id = $(this).data('id');
    var approved = $(this).data('app');
    var msg = $(this).data('msg');
    var cancelMsg = $(this).data('cmsg');
    approvedOrder(id, approved, msg, cancelMsg)
})


$(document).on('click', '#update_button', function () {
    var id = $(this).data('id');
    var oid = $(this).data('oid');
    // var add         = $(this).data('add');
    var cr = $(this).data('cr');
    var dd = $(this).data('dd');
    var dt = $(this).data('dt');
    var dcom = $(this).data('dcom');

    // $('#address').val(add);
    $('#delivery-date').val(dd);
    $('#delivery-time').val(dt);
    $('#delivery-comment').val(dcom);

    const formUpdateOrder = $('#delivery_form');

    if (formUpdateOrder) {
        formUpdateOrder.on('submit', function (e) {
            e.preventDefault();
            swal.fire({
                title: "Are you sure?",
                text: "Update This Info!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((waiting) => {
                    if (waiting.value === true) {
                        var d_id = id;
                        var orderd_id = oid;
                        // var address         =   $('#address').val();
                        var deliverydate = $('#delivery-date').val();
                        var deliverytime = $('#delivery-time').val();
                        var deliverycom = $('#delivery-comment').val();
                        var created_at = cr;

                        const formData = new FormData();

                        formData.append('d_id', d_id);
                        formData.append('orderd_id', orderd_id);
                        // formData.append('address', address);
                        formData.append('deliverydate', deliverydate);
                        formData.append('deliverytime', deliverytime);
                        formData.append('deliverycom', deliverycom);
                        formData.append('created_at', created_at);
                        $('#UpdateModal').modal('hide')
                        fetch('actions.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            if (response.text === true) {
                                $('#new_orders').DataTable().ajax.reload(null, false);
                                $('#finish_orders').DataTable().ajax.reload(null, false);
                                swal.fire("Update Done!", '', "success");
                            } else {
                                swal.fire("Something wronge!", '', "error");
                            }
                        })
                    } else {
                        swal.fire("Your order not change!", '', 'warning');
                    }
                });
        });
    }
})

$(document).on('click', '#ready_button', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var phone = $(this).data('phone');
    var add = $(this).data('add');
    var city = $(this).data('city');
    var prn = $(this).data('prn');
    var prp = $(this).data('prp');
    var prpi = $(this).data('prpi');
    var prc = $(this).data('prc');
    var emp_id = $(this).data('emp');
    var doo = $(this).data('doo');
    var dod = $(this).data('dod');
    var wod = $(this).data('wod');
    var com = $(this).data('com');
    var ost = $(this).data('ost');
    var status = $(this).data('st');
    var cr = $(this).data('cr');
    var dd = $(this).data('dd');
    var dt = $(this).data('dt');
    var dcom = $(this).data('dcom');

    const formData = new FormData();
    formData.append('order_id', id);

    let prnameText = '';

    fetch('order_info.php', {
        method: 'POST',
        body: formData
    })
        .then(res => {
            return res.json()
        })
        .then(response => {
            for (var i = 0; i < response.data.length; i++) {
                prnameText += '<li>' + ' ( ' + response.data[i]['prpieces'] + ' ) ' + response.data[i]['prname'] + ' - ' + response.data[i]['prprice'] + ' ' + response.data[i]['prcurrency'] + '</li>';
                $('#rprname').html(prnameText);
            }
        })

    $('#rname').text(name);
    $('#rphone').text(phone);
    $('#raddress').text(add);
    $('#rcity').text(city);
    $('#rdoo').text(doo);
    $('#rdod').text(dod);
    $('#rwod').text(wod);
    $('#sales-comment').text(com);
    $('#rdodd').text(dd);
    $('#rtod').text(dt);
    $('#ready-comment').val(dcom);

    const formWaitingOrder = $('#ready_form');

    if (formWaitingOrder) {
        formWaitingOrder.on('submit', function (e) {
            e.preventDefault();
            swal.fire({
                title: "Are you sure?",
                text: "Send To Ready Tab!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((ready) => {
                    if (ready.value === true) {
                        var orderd_id = id;
                        var waitingdate = dd;
                        var waitingtime = dt;
                        var waitingcom = $('#ready-comment').val();
                        var newstatus = status;
                        var oldstatus = ost;
                        var created_at = cr;

                        const formData = new FormData();

                        formData.append('orderd_id', orderd_id);
                        formData.append('waitingdate', waitingdate);
                        formData.append('waitingtime', waitingtime);
                        formData.append('waitingcom', waitingcom);
                        formData.append('status', newstatus);
                        formData.append('oldstatus', oldstatus);
                        formData.append('created_at', created_at);
                        $('#ReadyModal').modal('hide')
                        fetch('actions.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            if (response.text === true) {
                                $('#new_orders').DataTable().ajax.reload(null, false);
                                $('#waiting_orders').DataTable().ajax.reload(null, false);
                                $('#ready_orders').DataTable().ajax.reload(null, false);
                                $('#finish_orders').DataTable().ajax.reload(null, false);
                                swal.fire("Ready Done!", '', "success");
                            } else {
                                swal.fire("Something wronge!", '', "error");
                            }
                        })
                    } else {
                        swal.fire("Your order not change status!", '', 'warning');
                    }
                });
        });
    }
})

$(document).on('click', '#code_button', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var phone = $(this).data('phone');
    var add = $(this).data('add');
    var city = $(this).data('city');
    var prn = $(this).data('prn');
    var prp = $(this).data('prp');
    var prpi = $(this).data('prpi');
    var prc = $(this).data('prc');
    var emp_id = $(this).data('emp');
    var doo = $(this).data('doo');
    var dod = $(this).data('dod');
    var wod = $(this).data('wod');
    var com = $(this).data('com');
    var ost = $(this).data('ost');
    var status = $(this).data('st');
    var cr = $(this).data('cr');
    var dd = $(this).data('dd');
    var dt = $(this).data('dt');
    var dcom = $(this).data('dcom');

    $('#cname').text(name);
    $('#cphone').text(phone);
    $('#caddress').text(add);
    $('#ccity').text(city);
    $('#cprname').text(prn);
    $('#cprpieces').text(prpi);
    $('#cprprice').text(prp);
    $('#cprcurrency').text(prc);
    $('#cdoo').text(doo);
    $('#cdod').text(dod);
    $('#cwod').text(wod);
    $('#csales-comment').text(com);
    $('#cdodd').text(dd);
    $('#ctod').text(dt);
    $('#cdelivery-comment').text(dcom);

    const formCodeOrder = $('#code_form');

    if (formCodeOrder) {
        formCodeOrder.on('submit', function (e) {
            e.preventDefault();
            swal.fire({
                title: "Are you sure?",
                text: "Send To Finish Tab!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((ready) => {
                    if (ready.value === true) {
                        var code = $('#add-code').val();
                        var status = 3;
                        var waitingdate = dd;
                        var waitingtime = dt;

                        const formData = new FormData();

                        formData.append('orderd_id', id);
                        formData.append('waitingdate', waitingdate);
                        formData.append('waitingtime', waitingtime);
                        formData.append('waitingcom', dcom);
                        formData.append('code', code);
                        formData.append('oldstatus', ost);
                        formData.append('status', status);
                        formData.append('created_at', cr);
                        $('#CodeModal').modal('hide')
                        fetch('actions.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            if (response.text === true) {
                                $('#add-code').val(' ');
                                $('#new_orders').DataTable().ajax.reload(null, false);
                                $('#waiting_orders').DataTable().ajax.reload(null, false);
                                $('#ready_orders').DataTable().ajax.reload(null, false);
                                $('#finish_orders').DataTable().ajax.reload(null, false);
                                swal.fire("Insert Code Done!", '', "success");
                            } else {
                                swal.fire("Something wronge!", '', "error");
                            }
                        })
                    } else {
                        swal.fire("Your order not change status!", '', 'warning');
                    }
                });
        });
    }
})