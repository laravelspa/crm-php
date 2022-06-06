$(document).ready(function () {
    $('#filter_emp').select2();
    $('#filter_city').select2();
    // Checked Or Not [delete - edit]
    const checkedAll = document.querySelectorAll('.checkedAll');
    // console.log(localStorage.getItem('activeTab'))
    for (var a = 0; a < checkedAll.length; a++) {
        if (checkedAll[a]) {
            checkedAll[a].addEventListener('change', (e) => {
                const checkedList = document.querySelectorAll('.checkedList' + e.path[6].id);
                if (e.target.checked === true) {
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
    }

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

    $(document).on('submit', '#filter_cairo', function (e) {
        e.preventDefault();
        count++;
        if (count === 1) {
            var destination = localStorage.getItem('activeTab');
            var emp_id = $('#filter_emp').val();
            var city = $('#filter_city').val();
            var date_first = startDate;
            var date_last = endDate;
            console.log(destination)
            if (destination === '#stage') {
                $('#stage_orders').DataTable().destroy();
                getRecords('fetch_delivery_alex/fetch_stage_leads.php', '#stage_orders', emp_id, date_first, date_last, city);
            } else if (destination === '#processing') {
                $('#processing_orders').DataTable().destroy();
                getRecords('fetch_delivery_alex/fetch_processing_leads.php', '#processing_orders', emp_id, date_first, date_last, city);
            } else if (destination === '#approved') {
                $('#approved_orders').DataTable().destroy();
                getRecords('fetch_delivery_alex/fetch_approved_leads.php', '#approved_orders', emp_id, date_first, date_last, city);
            } else {
                $('#finish_orders').DataTable().destroy();
                getRecords('fetch_delivery_alex/fetch_finish_leads.php', '#finish_orders', emp_id, date_first, date_last, city);
            }
        }
        count = 0;
    })
})

getRecords('fetch_delivery_alex/fetch_stage_leads.php', '#stage_orders');
getRecords('fetch_delivery_alex/fetch_processing_leads.php', '#processing_orders');
getRecords('fetch_delivery_alex/fetch_approved_leads.php', '#approved_orders');
getRecords('fetch_delivery_alex/fetch_finish_leads.php', '#finish_orders');

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
    var assistant = $('#assistant').val();
    var activeTab = localStorage.getItem('activeTab');
    for (i = 0; i < checked.length; i++) {
        if (checked[i].checked) {
            selected.push(checked[i].value)
        }
    }

    const formData = new FormData();
    formData.append('ids', selected);
    formData.append('table', table);
    formData.append('assistant', assistant);
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
                            $('#processing_orders').DataTable().ajax.reload(null, false);
                            $('#approved_orders').DataTable().ajax.reload(null, false);
                            $('#finish_orders').DataTable().ajax.reload(null, false);
                            swal.fire("Re assign Done!", '', "success");
                        } else {
                            swal.fire("Something wronge!", '', "error");
                        }
                    })
                }
            } else {
                $('#addEmpToLeadModal').modal('hide');
                swal.fire(cancelMsg, '', 'warning');
            }
        })
}

function approvedOrder(id, approved, msg, cancelMsg, prname = '', quantity = '', inventory = '') {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', approved);
    formData.append('prname', prname);
    formData.append('quantity', quantity);
    formData.append('inventory', inventory);

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
                        $('#approved_orders').DataTable().ajax.reload(null, false);
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

$(document).on('click', '#sendto_btn', function () {
    var id = $(this).data('id');
    var status = $(this).data('st');
    var prname = $(this).data('prn');
    var prpieces = $(this).data('prpi');
    var inventory = $(this).data('inv');
    var msg = $(this).data('msg');
    var cancelMsg = $(this).data('cmsg');
    approvedOrder(id, status, msg, cancelMsg, prname, prpieces, inventory)
})
// History Of Order
$(document).on('click', '#history', function () {
    var order_id = $(this).data('id');
    var pending_id = $(this).data('pending');
    var history_body = $('.history_body');
    const formdata = new FormData();
    formdata.append('order_id', order_id);
    formdata.append('pending_id', pending_id);
    fetch('/history_info.php', {
        method: 'POST',
        body: formdata
    }).then(res => {
        return res.json();
    }).then(r => {
        $html = '';
        if (r.result.length > 0) {
            for (var i = 0; i < r.result.length; i++) {
                $html += '<div class="p-2 mb-1 border border-danger"><span class="font-weight-bold">Sales: </span><span>' + r.result[i]['operator'] + '</span><br> <span class="font-weight-bold">Action: </span><span>' + r.result[i]['action'] + '</span><br> <span class="font-weight-bold">Comment: </span><span>' + r.result[i]['comment'] + '</span><br><span class="font-weight-bold"> Action at: </span> <span>' + r.result[i]['created_at'] + '</span></div>';
            }
        } else {
            $html = '<div class="p-2 mb-1 border border-danger"><span>No History Available!</span></div>';
        }
        history_body.html($html)
    })
})