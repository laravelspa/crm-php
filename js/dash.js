$(document).ready(function () {
    $("#filter_usr").select2();
});

function getRecords(
    url,
    table = "#table_id",
    data = {}
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
            data: data,
        },
        language: {
            search: "",
            searchPlaceholder: "اكتب ما تبحث عنه",
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [10, 15, 20, 25, 30, 50, 100, 200, 300],
            [10, 15, 20, 25, 30, 50, 100, 200, 300],
        ],
        columnDefs: [{ orderable: false, targets: 0 }],
        buttons: [{
            extend: "colvis",
            text: "<i class='fas fa-eye'></i>",
            title: "",
            collectionLayout: "fixed two-column",
            className: "btn btn-sm btn-outline-dark",
            bom: "true",
            init: function (api, node, config) {
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
            init: function (api, node, config) {
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
            init: function (api, node, config) {
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
            init: function (api, node, config) {
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
            init: function (api, node, config) {
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
            title: "هل أنت متأكد?",
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

                                swal.fire("تم الحذف بنجاح!", "", "success");
                            } else {
                                swal.fire("هناك خطأ ما!", "", "error");
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
            title: "هل أنت متأكد?",
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
                            swal.fire("تم الحذف بنجاح!", "", "success");
                            $("#table_id").DataTable().ajax.reload(null, false);

                        } else {
                            swal.fire("هناك خطأ ما!", "", "error");
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
            title: "هل أنت متأكد?",
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
                                swal.fire("تم التحديث بنجاح!", "", "success");
                            } else {
                                swal.fire("هناك خطأ ما!", "", "error");
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
            title: "هل أنت متأكد?",
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
                                swal.fire("تم التحديث بنجاح!", "", "success");
                            } else {
                                swal.fire("هناك خطأ ما!", "", "error");
                            }
                        });
                }
            } else {
                swal.fire(cancelMsg, "", "warning");
            }
        });
}