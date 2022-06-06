$(document).ready(function() {
    $("#filter_emp").select2();
    $("#filter_st").select2();
    $("#filter_ci").select2();
    $("#filter_wod").select2();
    $("#filter_db").select2();
});

function getRecords(
    url,
    emp = "",
    st = "",
    df = "",
    dl = "",
    ci = "",
    shi = "",
    table = "#table_id",
    network = ""
) {
    $.fn.dataTable.ext.classes.sPageButton = "paginate_custom_buttons";
    $(table).DataTable({
        dom: "lBfrtip",
        stateSave: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: "POST",
            data: {
                emp_id: emp,
                status: st,
                date_first: df,
                date_last: dl,
                city: ci,
                shipping: shi,
                network: network,
            },
        },
        language: {
            search: "",
            searchPlaceholder: "Search.....",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [10,15,20,25,30, 50, 100, 200,300],
            [10,15,20,25,30, 50, 100, 200,300],
        ],
        columnDefs: [{ orderable: false, targets: 0 }],
        buttons: [{
                extend: "colvis",
                text: "<i class='fas fa-eye'></i>",
                title: "",
                collectionLayout: "fixed two-column",
                className: "btn btn-sm btn-outline-dark",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "csv",
                text: "<i class='fas fa-file-csv'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-success",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "excel",
                text: "<i class='fas fa-file-excel'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-danger",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "print",
                text: "<i class='fas fa-file-pdf'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-primary",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "copy",
                text: "<i class='fas fa-copy'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-info",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
        ],
    });
}

function getTerraleads(
    url = "fetch_leads.php",
    na = "",
    cou = "",
    ph = "",
    add = "",
    add_date = "",
    table = "#terraleads"
) {
    $.fn.dataTable.ext.classes.sPageButton = "paginate_custom_buttons";
    $(table).DataTable({
        dom: "lBfrtip",
        stateSave: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: "POST",
            data: {
                name: na,
                country: cou,
                phone: ph,
                address: add,
                add_date: add_date,
            },
        },
        language: {
            search: "",
            searchPlaceholder: "Search.....",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [10,15,20,25,30, 50, 100, 200,300],
            [10,15,20,25,30, 50, 100, 200,300],
        ],
        columnDefs: [{ orderable: false, targets: 0 }],
        buttons: [{
                extend: "colvis",
                text: "<i class='fas fa-eye'></i>",
                title: "",
                collectionLayout: "fixed two-column",
                className: "btn btn-sm btn-outline-dark",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "csv",
                text: "<i class='fas fa-file-csv'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-success",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "excel",
                text: "<i class='fas fa-file-excel'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-danger",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "print",
                text: "<i class='fas fa-file-pdf'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-primary",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "copy",
                text: "<i class='fas fa-copy'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-info",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
        ],
    });
}

function getComboLeads(
    url = "fetch_leads.php",
    na = "",
    ph = "",
    created_at = "",
    table = "#combo"
) {
    $.fn.dataTable.ext.classes.sPageButton = "paginate_custom_buttons";
    $(table).DataTable({
        dom: "lBfrtip",
        stateSave: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: "POST",
            data: {
                name: na,
                phone: ph,
                created_at: created_at,
            },
        },
        language: {
            search: "",
            searchPlaceholder: "Search.....",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [10,15,20,25,30, 50, 100, 200,300],
            [10,15,20,25,30, 50, 100, 200,300],
        ],
        columnDefs: [{ orderable: false, targets: 0 }],
        buttons: [{
                extend: "colvis",
                text: "<i class='fas fa-eye'></i>",
                title: "",
                collectionLayout: "fixed two-column",
                className: "btn btn-sm btn-outline-dark",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "csv",
                text: "<i class='fas fa-file-csv'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-success",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "excel",
                text: "<i class='fas fa-file-excel'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-danger",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "print",
                text: "<i class='fas fa-file-pdf'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-primary",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "copy",
                text: "<i class='fas fa-copy'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-info",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
        ],
    });
}

function getCampaigns(
    url = "fetch_campaigns.php",
    name = "",
    c_id = "",
    table = "#table_id"
) {
    $.fn.dataTable.ext.classes.sPageButton = "paginate_custom_buttons";
    $(table).DataTable({
        dom: "lBfrtip",
        stateSave: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: "POST",
            data: {
                name: name,
                campaign_id: c_id,
            },
        },
        language: {
            search: "",
            searchPlaceholder: "Search.....",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [10,15,20,25,30, 50, 100, 200,300],
            [10,15,20,25,30, 50, 100, 200,300],
        ],
        columnDefs: [{ orderable: false, targets: 0 }],
        buttons: [{
                extend: "colvis",
                text: "<i class='fas fa-eye'></i>",
                title: "",
                collectionLayout: "fixed two-column",
                className: "btn btn-sm btn-outline-dark",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "csv",
                text: "<i class='fas fa-file-csv'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-success",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "excel",
                text: "<i class='fas fa-file-excel'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-danger",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "print",
                text: "<i class='fas fa-file-pdf'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-primary",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "copy",
                text: "<i class='fas fa-copy'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-info",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
        ],
    });
}


function getFingerprints(
    url = "fetch_fingerprints.php",
    name = "",
    value = "",
    table = "#table_id"
) {
    $.fn.dataTable.ext.classes.sPageButton = "paginate_custom_buttons";
    $(table).DataTable({
        dom: "lBfrtip",
        stateSave: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: "POST",
            data: {
                name: name,
                value: value,
            },
        },
        language: {
            search: "",
            searchPlaceholder: "Search.....",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [10,15,20,25,30, 50, 100, 200,300],
            [10,15,20,25,30, 50, 100, 200,300],
        ],
        columnDefs: [{ orderable: false, targets: 0 }],
        buttons: [{
                extend: "colvis",
                text: "<i class='fas fa-eye'></i>",
                title: "",
                collectionLayout: "fixed two-column",
                className: "btn btn-sm btn-outline-dark",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "csv",
                text: "<i class='fas fa-file-csv'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-success",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
            },
            {
                extend: "excel",
                text: "<i class='fas fa-file-excel'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-danger",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "print",
                text: "<i class='fas fa-file-pdf'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-primary",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
            {
                extend: "copy",
                text: "<i class='fas fa-copy'></i>",
                title: "",
                filename: "Report Name",
                className: "btn btn-sm btn-outline-info",
                charset: "utf-8",
                bom: "true",
                init: function(api, node, config) {
                    $(node).removeClass("dt-button");
                },
                exportOptions: {
                    columns: [":visible"],
                },
            },
        ],
    });
}

// Checked Or Not [delete - edit]
const checkedAll = document.querySelector(".checkedAll");

if (checkedAll) {
    checkedAll.addEventListener("change", () => {
        const checkedList = document.querySelectorAll(".checkedList");
        if (checkedAll.checked === true) {
            for (i = 0; i < checkedList.length; i++) {
                checkedList[i].checked = true;
            }
        } else {
            for (i = 0; i < checkedList.length; i++) {
                checkedList[i].checked = false;
            }
        }
    });
}

function deleteAll(url, table, msg, emptyMsg, cancelMsg) {
    var selected = [];
    const checked = document.querySelectorAll("input[name=checkedList]:checked");
    for (i = 0; i < checked.length; i++) {
        if (checked[i].checked) {
            selected.push(checked[i].value);
        }
    }
    const formData = new FormData();
    formData.append("ids", selected);
    formData.append("table", table);
    swal
        .fire({
            title: "Are you sure?",
            text: msg,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete.value === true) {
                if (checked.length === 0) {
                    swal.fire(emptyMsg, "", "warning");
                } else {
                    fetch(url, {
                            method: "POST",
                            body: formData,
                        })
                        .then((res) => {
                            return res.json();
                        })
                        .then((response) => {
                            if (response.text === true) {
                                checkedAll.checked = false;
                                $("#table_id").DataTable().ajax.reload(null, false);
                                $("#terraleads").DataTable().ajax.reload(null, false);
                                swal.fire("Deleted Done!", "", "success");
                            } else {
                                swal.fire("Something wronge!", "", "error");
                            }
                        });
                }
            } else {
                swal.fire(cancelMsg, "", "warning");
            }
        });
}

function deleteOne(id, table, msg, url, cancelMsg) {
    const formData = new FormData();
    formData.append("id", id);
    formData.append("table", table);

    swal
        .fire({
            title: "Are you sure?",
            text: msg,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete.value === true) {
                fetch(url, {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => {
                        return res.json();
                    })
                    .then((response) => {
                        if (response.text === true) {
                            swal.fire("Deleted Done!", "", "success");
                            $("#table_id").DataTable().ajax.reload(null, false);
                            $("#terraleads").DataTable().ajax.reload(null, false);
                        } else {
                            swal.fire("Something wronge!", "", "error");
                        }
                    });
            } else {
                swal.fire(cancelMsg, "", "warning");
            }
        });
}

// Update All (ِAdd Employee To Leads)
function updateAll(url, table, msg, emptyMsg, cancelMsg, emp = null) {
    var selected = [];
    const checked = document.querySelectorAll("input[name=checkedList]:checked");
    const wall = $("#wallUpdate").val();
    var employee = null;
    if (emp !== null) {
        employee = $("#employee").val();
    }
    for (i = 0; i < checked.length; i++) {
        if (checked[i].checked) {
            selected.push(checked[i].value);
        }
    }

    const formData = new FormData();
    formData.append("ids", selected);
    formData.append("table", table);
    if (wall !== undefined) {
        formData.append("wall", wall);
    }
    if (emp !== null) {
        formData.append("employee_id", employee);
    }

    swal
        .fire({
            title: "Are you sure?",
            text: msg,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((Update) => {
            if (Update.value === true) {
                if (checked.length === 0) {
                    swal.fire(emptyMsg, "", "warning");
                } else {
                    $("#wallModal").modal("hide");
                    $("#addEmpToLeadModal").modal("hide");
                    fetch(url, {
                            method: "POST",
                            body: formData,
                        })
                        .then((res) => {
                            return res.json();
                        })
                        .then((response) => {
                            if (response.text === true) {
                                checkedAll.checked = false;
                                employee = null;
                                $("#table_id").DataTable().ajax.reload(null, false);
                                swal.fire("Edit Done!", "", "success");
                            } else {
                                swal.fire("Something wronge!", "", "error");
                            }
                        });
                }
            } else {
                swal.fire(cancelMsg, "", "warning");
            }
        });
}

// Update All (ِAdd Employee To Leads)
function updateOrders(url, table, msg, emptyMsg, cancelMsg) {
    var selected = [];
    const checked = document.querySelectorAll("input[name=checkedList]:checked");
    const employee = document.getElementById("employee").value;
    const status = document.getElementById("status").value;
    const admin = document.getElementById("admin_name").value;

    if (checked.length > 0 && employee !== "") {
        for (i = 0; i < checked.length; i++) {
            if (checked[i].checked) {
                selected.push(checked[i].value);
            }
        }
    }
    const formData = new FormData();
    formData.append("ids", selected);
    formData.append("status", status);
    formData.append("employee_id", employee);
    formData.append("admin_name", admin);
    formData.append("table", table);

    swal
        .fire({
            title: "Are you sure?",
            text: msg,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((Update) => {
            if (Update.value === true) {
                if (checked.length === 0) {
                    swal.fire(emptyMsg, "", "warning");
                } else {
                    $("#addCancelOrdersToEmpModal").modal("hide");
                    fetch(url, {
                            method: "POST",
                            body: formData,
                        })
                        .then((res) => {
                            return res.json();
                        })
                        .then((response) => {
                            if (response.text === true) {
                                checkedAll.checked = false;
                                $("#table_id").DataTable().ajax.reload(null, false);
                                swal.fire("Edit Done!", "", "success");
                            } else {
                                swal.fire("Something wronge!", "", "error");
                            }
                        });
                }
            } else {
                swal.fire(cancelMsg, "", "warning");
            }
        });
}

// History Of Order
$(document).on("click", "#history", function() {
    var order_id = $(this).data("id");
    var pending_id = $(this).data("pending");
    var history_body = $(".history_body");
    const formdata = new FormData();
    formdata.append("order_id", order_id);
    formdata.append("pending_id", pending_id);
    fetch("/history_info.php", {
            method: "POST",
            body: formdata,
        })
        .then((res) => {
            return res.json();
        })
        .then((r) => {
            $html = "";
            if (r.result.length > 0) {
                for (var i = 0; i < r.result.length; i++) {
                    $html +=
                        '<div class="p-2 mb-1 border border-danger"><span class="font-weight-bold">Sales: </span><span>' +
                        r.result[i]["operator"] +
                        '</span><br> <span class="font-weight-bold">Action: </span><span>' +
                        r.result[i]["action"] +
                        '</span><br> <span class="font-weight-bold">Comment: </span><span>' +
                        r.result[i]["comment"] +
                        '</span><br><span class="font-weight-bold"> Action at: </span> <span>' +
                        r.result[i]["created_at"] +
                        "</span></div>";
                }
            } else {
                $html =
                    '<div class="p-2 mb-1 border border-danger"><span>No History Available!</span></div>';
            }
            history_body.html($html);
        });
});