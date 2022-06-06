// var conn = new WebSocket('wss://crm.healthy-cure.online/wss2/employee/:8080');
//     conn.onopen = function(e) {
//         console.log("Connection established! 1");
//     };

//     conn.onmessage = function(e) {
//         var recived = JSON.parse(e.data);
//         if(recived.status === 'open') {
//             $("td:contains('"+recived.id+"')").parent().css({
//                 'background-color':'red',
//                 'color' : '#fff'
//             })
//             $(`button[data-id='${recived.id}']`).attr('disabled','disabled');
//         } else {
//             $("td:contains('"+recived.id+"')").parent().css({
//                 'background-color':'#fff',
//                 'color' : '#000'
//             })
//             $(`button[data-id='${recived.id}']`).removeAttr('disabled');
//         }
//     };
//     conn.onclose = function(e) {
//       conn.onopen = function(e) {
//         console.log("Connection established! 2");
//       };
//     };

// function sendSignalToOthers(id_value,model_id) {
//     var objectOpen = {
//             id:id_value,
//             status:'open'
//         }
//     var objectClose = {
//         id:id_value,
//         status:'close'
//     }
//     var loopSocket = setInterval(sendId,2000);
//     function sendId() {
//         conn.send(JSON.stringify(objectOpen))
//     }
//     $(model_id).on('shown.bs.modal', function () {
//         loopSocket;
//     })
//     $(model_id).on('hidden.bs.modal', function () {
//         clearInterval(loopSocket,2000)
//         conn.send(JSON.stringify(objectClose))
//     })
// }

function getRecordsForSales(table_id, url) {
    $.fn.dataTable.ext.classes.sPageButton = 'paginate_custom_buttons'
    $(table_id).DataTable({
        stateSave: true,
        dom: 'lfrtip',
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: url,
            method: 'POST',
        },
        language: {
            search: '',
            searchPlaceholder: 'Search...',
            processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
        },
        lengthMenu: [
            [20],
            [20]
        ],
    })
}
$(document).ready(function () {
    $('#city').select2({
        dropdownParent: $('#orderdModal'),
    })
    $('#cancel-reason').select2({
        dropdownParent: $('#canceldModal'),
    })
    // History Of Order
    $(document).on('click', '#history', function () {
        var order_id = $(this).data('id')
        var history_body = $('.history_body')
        const overlay = $('.overlay')
        var html = ''
        overlay.attr('style', 'display: flex !important')

        $('#HistoryModal').on('hidden.bs.modal', function (e) {
            history_body.html(' ')
        })
        const formdata = new FormData()
        formdata.append('order_id', order_id)
        fetch('/history_emp.php', {
            method: 'POST',
            body: formdata,
        })
            .then((res) => {
                return res.json()
            })
            .then((r) => {
                if (r.text === true) {
                    if (r.result.length > 0) {
                        for (var i = 0; i < r.result.length; i++) {
                            html +=
                                '<div class="p-2 mb-1 border border-danger"><span class="font-weight-bold">Sales: </span><span>' +
                                r.result[i]['operator'] +
                                '</span><br> <span class="font-weight-bold">Action: </span><span>' +
                                r.result[i]['action'] +
                                '</span><br> <span class="font-weight-bold">Comment: </span><span>' +
                                r.result[i]['comment'] +
                                '</span><br><span class="font-weight-bold"> Action at: </span> <span>' +
                                r.result[i]['created_at'] +
                                '</span></div>'
                        }
                    } else {
                        html =
                            '<div class="p-2 mb-1 border border-danger"><span>No History Available!</span></div>'
                    }
                    history_body.html(html)
                } else {
                    html =
                        '<div class="p-2 mb-1 border border-danger"><span>ERROR: Server Connection!</span></div>'
                }
                overlay.attr('style', 'display: none !important')
            })
    })

    // Tap active when page refresh
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'))
    })
    var activeTab = localStorage.getItem('activeTab')
    if (activeTab) {
        $('#myTab a[href="' + activeTab + '"]').tab('show')
    }
    // Orderd Modal
    $(document).on('click', '#orderd', function (e) {
        const orderdheaderModal = document.querySelector('#orderdModalLabel')

        var id = $(this).data('id')
        var dbid = $(this).data('dbid')
        var name = $(this).data('name')
        var phone = $(this).data('phone')
        var add = $(this).data('add')
        var city = $(this).data('city')
        var pn = $(this).data('pn')
        var prn = $(this).data('prn')
        var prp = $(this).data('prp')
        var prpi = $(this).data('prpi')
        var prc = $(this).data('prc')
        var emp_id = $(this).data('emp')
        var doo = $(this).data('doo')
        var dod = $(this).data('dod')
        var tod = $(this).data('tod')
        var com = $(this).data('com')
        var lf = $(this).data('lf')
        var ad = $(this).data('ad')
        var wid = $(this).data('wid')
        // sendSignalToOthers(id,'#orderdModal')

        orderdheaderModal.textContent = 'Fill Out Order ' + name

        $('.id').val(id)
        $('.db_id').val(dbid)
        $('.name').val(name)
        $('.phone').val(phone)
        $('.address').val(add)
        $('.city').val(city)
        $('.pname').val(pn)
        $('.prname').val(prn)
        $('.prpieces').val(prpi)
        $('.prprice').val(prp)
        $('.prcurrency').val(prc)
        $('.emp_id').val(emp_id)
        $('.doo').val(doo)
        $('.dod').val(dod)
        $('.order-time').val(tod)
        $('.order-comment').val(com)
        $('.lead_from').val(lf)
        $('.web_id').val(wid)
        $('.added_at').val(ad)

        $('.add-another-1').click(function () {
            $('#add-another-1').toggle()
            if ($('#add-another-1').is(':visible')) {
                $('.prname_1').attr('required', 'required')
                $('.prprice_1').attr('required', 'required')
                $('.prpieces_1').attr('required', 'required')
                $('.prcurrency_1').attr('required', 'required')
            }

            // The same works with hidden
            if ($('#add-another-1').is(':hidden')) {
                $('.prname_1').removeAttr('required')
                $('.prprice_1').removeAttr('required')
                $('.prpieces_1').removeAttr('required')
                $('.prcurrency_1').removeAttr('required')
            }
        })
        $('.add-another-2').click(function () {
            $('#add-another-2').toggle()
            if ($('#add-another-2').is(':visible')) {
                $('.prname_2').attr('required', 'required')
                $('.prprice_2').attr('required', 'required')
                $('.prpieces_2').attr('required', 'required')
                $('.prcurrency_2').attr('required', 'required')
            }

            // The same works with hidden
            if ($('#add-another-2').is(':hidden')) {
                $('.prname_2').removeAttr('required')
                $('.prprice_2').removeAttr('required')
                $('.prpieces_2').removeAttr('required')
                $('.prcurrency_2').removeAttr('required')
            }
        })

        const formEdit = document.querySelector('form[name=orderd]')
        var count = 0
        var dodCount = 0
        if (formEdit) {
            formEdit.addEventListener('submit', (e) => {
                e.preventDefault()
                swal
                    .fire({
                        title: 'Are you sure?',
                        text: 'Once approved, This lead disappearing!',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        var id = $('.id').val()
                        var dbid = $('.db_id').val()
                        var name = $('.name').val()
                        var phone = $('.phone').val()
                        var address = $('.address').val()
                        var city = $('.city').val()
                        var pname = $('.pname').val()
                        var prname = $('.prname').val()
                        var prpieces = $('.prpieces').val()
                        var prprice = $('.prprice').val()
                        var prcurrency = $('.prcurrency').val()
                        var lead_from = $('.lead_from').val()
                        var web_id = $('.web_id').val()
                        var added_at = $('.added_at').val()
                        if ($('#add-another-1').is(':visible')) {
                            var prn_1 = $('.prname_1').val()
                            var pn_1 = prn_1
                            var prp_1 = $('.prprice_1').val()
                            var prpi_1 = $('.prpieces_1').val()
                            var prc_1 = $('.prcurrency_1').val()
                        }
                        if ($('#add-another-2').is(':visible')) {
                            var prn_2 = $('.prname_2').val()
                            var pn_2 = prn_2
                            var prp_2 = $('.prprice_2').val()
                            var prpi_2 = $('.prpieces_2').val()
                            var prc_2 = $('.prcurrency_2').val()
                        }

                        var emp_id = $('.emp_id').val()
                        var doo = $('.doo').val()
                        var dod = $('.dod').val()
                        var tod = $('.order-time').val()
                        var wod = $('.waydelivery').val()
                        var com = $('.order-comment').val()

                        const formData = new FormData()

                        formData.append('id', id)
                        formData.append('dbid', dbid)
                        formData.append('name', name)
                        formData.append('phone', phone)
                        formData.append('address', address)
                        formData.append('city', city)
                        formData.append('pname', pname)
                        formData.append('prname', prname)
                        formData.append('prprice', prprice)
                        formData.append('prpieces', prpieces)
                        formData.append('prcurrency', prcurrency)
                        formData.append('lead_from', lead_from)
                        formData.append('web_id', web_id)
                        formData.append('added_at', added_at)
                        
                        if ($('#add-another-1').is(':visible')) {
                            formData.append('pname_1', pn_1)
                            formData.append('prname_1', prn_1)
                            formData.append('prprice_1', prp_1)
                            formData.append('prpieces_1', prpi_1)
                            formData.append('prcurrency_1', prc_1)
                        }
                        if ($('#add-another-2').is(':visible')) {
                            formData.append('pname_2', pn_2)
                            formData.append('prname_2', prn_2)
                            formData.append('prprice_2', prp_2)
                            formData.append('prpieces_2', prpi_2)
                            formData.append('prcurrency_2', prc_2)
                        }
                        formData.append('emp_id', emp_id)
                        formData.append('doo', doo)
                        formData.append('dod', dod)
                        formData.append('tod', tod)
                        formData.append('wod', wod)
                        formData.append('com', com)

                        if (willDelete.value === true) {
                            count++
                            // console.log(count)
                            if (count === 1) {
                                // $('.approve').attr('disabled','disabled');
                                $('#orderdModal').modal('hide')
                                fetch('../employee/orderd.php', {
                                    method: 'POST',
                                    body: formData,
                                })
                                    .then((res) => {
                                        return res.json()
                                    })
                                    .then((response) => {
                                        // $('.approve').removeAttr('disabled');
                                        id = ''
                                        dbid = ''
                                        name = ''
                                        phone = ''
                                        address = ''
                                        city = ''
                                        pname = ''
                                        prname = ''
                                        prpieces = ''
                                        prprice = ''
                                        prcurrency = ''
                                        emp_id = ''
                                        doo = ''
                                        dod = ''
                                        wod = ''
                                        com = ''
                                        lead_from = ''
                                        if (response.text === true) {
                                            $('#my_leads').DataTable().ajax.reload()
                                            $('#my_leads_dod').DataTable().ajax.reload()
                                            $('#wall_leads').DataTable().ajax.reload()
                                            $('#wall_leads_dod').DataTable().ajax.reload(null, false)
                                            swal.fire('Approved Done!', '', 'success')
                                        } else {
                                            swal.fire('Something wronge!', '', 'error')
                                        }
                                    })
                            }
                        } else {
                            swal.fire('Your lead is not approved!')
                            // $('.approve').removeAttr('disabled');
                        }
                    })
            })
        }

        $(document).on('click', '.dod_button', function () {
            var id = $('.id').val()
            var dbid = $('.db_id').val()
            var name = $('.name').val()
            var phone = $('.phone').val()
            var address = $('.address').val()
            var city = $('.city').val()
            var pname = $('.pname').val()
            var prname = $('.prname').val()
            var prpieces = $('.prpieces').val()
            var prprice = $('.prprice').val()
            var prcurrency = $('.prcurrency').val()
            var emp_id = $('.emp_id').val()
            var doo = $('.doo').val()
            var dod = $('.dod').val()
            var tod = $('.order-time').val()
            var wod = $('.waydelivery').val()
            var com = $('.order-comment').val()
            var lead_from = $('.lead_from').val()
            var web_id = $('.web_id').val()
            var added_at = $('.added_at').val()
            var errors = []
            if (name === '') {
                errors.push('Name is required')
            } else if (phone === '') {
                errors.push('Phone is required')
            } else if (address === '') {
                errors.push('Address is required')
            } else if (city === null) {
                errors.push('City is required')
            } else if (prname === '') {
                errors.push('Product Name is required')
            } else if (prpieces === '') {
                errors.push('Quantity is required')
            } else if (prprice === '') {
                errors.push('Product Price is required')
            } else if (prcurrency === '') {
                errors.push('Currency is required')
            } else if (doo === '') {
                errors.push('Date of order is required')
            } else if (dod === '') {
                errors.push('Date of delivery is required')
            } else if (tod === '') {
                errors.push('Time of delivery is required')
            } else if (wod === '') {
                errors.push('Shipping is required')
            } else {
                swal
                    .fire({
                        title: 'Are you sure?',
                        text: 'Delay Approved, This lead will go to DOD tab!',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        const formData = new FormData()

                        formData.append('id', id)
                        formData.append('dbid', dbid)
                        formData.append('name', name)
                        formData.append('phone', phone)
                        formData.append('address', address)
                        formData.append('city', city)
                        formData.append('pname', pname)
                        formData.append('prname', prname)
                        formData.append('prprice', prprice)
                        formData.append('prpieces', prpieces)
                        formData.append('prcurrency', prcurrency)
                        formData.append('emp_id', emp_id)
                        formData.append('doo', doo)
                        formData.append('dod', dod)
                        formData.append('tod', tod)
                        formData.append('wod', wod)
                        formData.append('com', com)
                        formData.append('lead_from', lead_from)
                        formData.append('web_id', web_id)
                        formData.append('added_at', added_at)

                        if (willDelete.value === true) {
                            dodCount++
                            // console.log(count,dodCount)
                            if (dodCount === 1) {
                                // $('.dod_button').attr('disabled','disabled');
                                $('#orderdModal').modal('hide')
                                fetch('../employee/order_dod.php', {
                                    method: 'POST',
                                    body: formData,
                                })
                                    .then((res) => {
                                        return res.json()
                                    })
                                    .then((response) => {
                                        // $('.dod_button').removeAttr('disabled');
                                        id = ''
                                        dbid = ''
                                        name = ''
                                        phone = ''
                                        address = ''
                                        city = ''
                                        pname = ''
                                        prname = ''
                                        prpieces = ''
                                        prprice = ''
                                        prcurrency = ''
                                        emp_id = ''
                                        doo = ''
                                        dod = ''
                                        wod = ''
                                        com = ''
                                        lead_from = ''
                                        if (response.text === true) {
                                            $('#my_leads').DataTable().ajax.reload(null, false)
                                            $('#my_leads_dod').DataTable().ajax.reload(null, false)
                                            $('#wall_leads').DataTable().ajax.reload(null, false)
                                            $('#wall_leads_dod').DataTable().ajax.reload(null, false)
                                            swal.fire('DOD Done!', '', 'success')
                                        } else {
                                            swal.fire('Something wronge!', '', 'error')
                                            // $('.dod_button').removeAttr('disabled');
                                        }
                                    })
                            }
                        } else {
                            swal.fire('Your status lead is not changed!')
                        }
                    })
            }
            if (errors.length > 0) {
                for (var i = 0; i < errors.length; i++) {
                    document.querySelector('.error-group ul').innerHTML =
                        '<li class="alert alert-danger col-12">' + errors[i] + '</li>'
                }
            }
        })
    })

    // Canceld Modal
    $(document).on('click', '#canceld', function (e) {
        const canceldheaderModal = document.querySelector('#canceldModalLabel')
        $(document).on('change', '.cancel-reason', function (e) {
            var selectVal = $('.cancel-reason').val()
            var commentCont = $('.comment-container')[0]

            if (selectVal === 'comment') {
                commentCont.style.display = 'block'
                $('.comment').attr('required', 'required')
            } else {
                commentCont.style.display = 'none'
                $('.comment').removeAttr('required')
            }
        })

        var id = $(this).data('id')
        var dbid = $(this).data('dbid')
        var name = $(this).data('name')
        var phone = $(this).data('phone')
        var pn = $(this).data('pn')
        var prn = $(this).data('prn')
        var prp = $(this).data('prp')
        var prpi = $(this).data('prpi')
        var prc = $(this).data('prc')
        var emp_id = $(this).data('emp')
        var lf = $(this).data('lf')
        var ad = $(this).data('ad')
        var wid = $(this).data('wid')

        // sendSignalToOthers(id,'#canceldModal')
        canceldheaderModal.textContent = 'Reason for Cancellation Order ' + name

        $('.id').val(id)
        $('.db_id').val(dbid)
        $('.name').val(name)
        $('.phone').val(phone)
        $('.pname').val(pn)
        $('.prname').val(prn)
        $('.prprice').val(prp)
        $('.prpieces').val(prpi)
        $('.emp_id').val(emp_id)
        $('.lead_from').val(lf)
        $('.added_at').val(ad)
        $('.web_id').val(wid)

        const formCancel = document.querySelector('form[name=canceld]')
        var count = 0
        if (formCancel) {
            formCancel.addEventListener('submit', (e) => {
                e.preventDefault()
                count++
                if (count === 1) {
                    var id = $('.id').val()
                    var dbid = $('.db_id').val()
                    var name = $('.name').val()
                    var phone = $('.phone').val()
                    var pname = $('.pname').val()
                    var prname = $('.prname').val()
                    var prprice = $('.prprice').val()
                    var prpieces = $('.prpieces').val()
                    var prcurrency = $('.prcurrency').val()
                    var emp_id = $('.emp_id').val()
                    var lead_from = $('.lead_from').val()
                    var added_at = $('.added_at').val()
                    var web_id = $('.web_id').val()
                    var cr =
                        $('.cancel-reason').val() !== 'comment' ?
                            $('.cancel-reason').val() :
                            $('.comment').val()
                    const formData = new FormData()

                    formData.append('id', id)
                    formData.append('dbid', dbid)
                    formData.append('name', name)
                    formData.append('phone', phone)
                    formData.append('pname', pname)
                    formData.append('prname', prname)
                    formData.append('prpieces', prpieces)
                    formData.append('prprice', prprice)
                    formData.append('prcurrency', prcurrency)
                    formData.append('emp_id', emp_id)
                    formData.append('lead_from', lead_from)
                    formData.append('cr', cr)
                    formData.append('added_at', added_at)
                    formData.append('web_id', web_id)

                    swal
                        .fire({
                            title: 'Are you sure?',
                            text: 'Once Canceld, This will disappearing!',
                            icon: 'warning',
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete.value === true) {
                                // $('.cancel').attr('disabled','disabled')
                                $('#canceldModal').modal('hide')
                                fetch('../employee/canceld.php', {
                                    method: 'POST',
                                    body: formData,
                                })
                                    .then((res) => {
                                        return res.json()
                                    })
                                    .then((response) => {
                                        // $('.cancel').removeAttr('disabled')
                                        id = ''
                                        dbid = ''
                                        name = ''
                                        phone = ''
                                        pname = ''
                                        prname = ''
                                        prprice = ''
                                        prpieces = ''
                                        prcurrency = ''
                                        emp_id = ''
                                        cr = ''
                                        lead_from = ''
                                        if (response.text === true) {
                                            $('#my_leads').DataTable().ajax.reload(null, false)
                                            $('#my_leads_dod').DataTable().ajax.reload(null, false)
                                            $('#wall_leads').DataTable().ajax.reload(null, false)
                                            $('#wall_leads_dod').DataTable().ajax.reload(null, false)
                                            swal.fire('Canceld Done!', '', 'success')
                                        } else {
                                            // $('.cancel').removeAttr('disabled')
                                            swal.fire('Something wronge!', '', 'error')
                                        }
                                    })
                            } else {
                                swal.fire('Your order is safe!')
                            }
                        })
                }
            })
        }
    })

    // Pending Modal
    $(document).on('click', '#pending', function (e) {
        const pendingheaderModal = document.querySelector('#pendingModalLabel')
        var status = $(this).data('status')
        if (status == 'None' || status === '' || status === 'dod') {
            status = 'not answer'
        }

        var selectVal = $('.pending-reason').val(status)
        var commentCont = $('.pending-comment-container')[0]
        var id = $(this).data('id')
        var time = $(this).data('time')
        var date = $(this).data('date')
        var com = $(this).data('com')
        var name = $(this).data('name')
        var emp_id = $(this).data('emp')
        var lf = $(this).data('lf')
        // sendSignalToOthers(id,'#pendingModal')
        pendingheaderModal.textContent = 'Pending Reason For ' + name
        $(document).on('change', '.pending-reason', function (e) {
            selectVal = $('.pending-reason').val()
            commentCont = $('.pending-comment-container')[0]

            if (selectVal === 'call again') {
                commentCont.style.display = 'block'
                $('.pending-date').attr('required', 'required')
                $('.pending-time').attr('required', 'required')
                $('.pending-comment').attr('required', 'required')
                $('.pending-date').val(date)
                $('.pending-time').val(time)
                $('.pending-comment').val(com)
            } else {
                commentCont.style.display = 'none'
                $('.pending-date').removeAttr('required')
                $('.pending-time').removeAttr('required')
                $('.pending-comment').removeAttr('required')
                $('.pending-date').val('')
                $('.pending-time').val('')
                $('.pending-comment').val('')
            }
        })

        if (status === 'call again') {
            commentCont.style.display = 'block'
            $('.pending-date').attr('required', 'required')
            $('.pending-time').attr('required', 'required')
            $('.pending-comment').attr('required', 'required')
        } else {
            commentCont.style.display = 'none'
            $('.pending-date').removeAttr('required')
            $('.pending-time').removeAttr('required')
            $('.pending-comment').removeAttr('required')
        }

        $('.id').val(id)
        $('.pending-time').val(time)
        $('.pending-date').val(date)
        $('.pending-comment').val(com)

        var count = 0
        $(document).on('submit', '#pending_form', (e) => {
            e.preventDefault()
            // $('.pending').attr('disabled','disabled')
            count++
            if (count === 1) {
                var id = $('.id').val()
                var pending_reason = $('.pending-reason').val()
                var pending_comment = $('.pending-comment').val()
                var pending_time = $('.pending-time').val()
                var pending_date = $('.pending-date').val()

                const formData = new FormData()

                formData.append('id', id)
                formData.append('pending_reason', pending_reason)
                formData.append('pending_comment', pending_comment)
                formData.append('pending_time', pending_time)
                formData.append('pending_date', pending_date)
                formData.append('emp_id', emp_id)
                formData.append('lead_from', lf)

                $('#pendingModal').modal('hide')
                fetch('../employee/pending.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then((res) => {
                        return res.json()
                    })
                    .then((response) => {
                        // $('.pending').removeAttr('disabled')
                        if (response.text === true) {
                            $('#my_leads').DataTable().ajax.reload(null, false)
                            $('#my_leads_dod').DataTable().ajax.reload(null, false)
                            swal.fire('Edit Status Done!', '', 'success')
                        } else {
                            swal.fire('Something wronge!', '', 'error')
                        }
                        $('.id').val(' ')
                        $('.pending-reason').val(' ')
                        $('.pending-comment').val(' ')
                        $('.pending-time').val(' ')
                        $('.pending-date').val(' ')
                        $('#pending_form')[0].reset()
                    })
            }
        })

        // Pending IN Wall
        var countWall = 0
        $(document).on('submit', '#pending_index', (e) => {
            e.preventDefault()
            countWall++
            if (countWall === 1) {
                var call_again_attention = 'call again'
                var id = $('.id').val()
                var pending_reason = $('.pending-reason').val()
                var pending_comment = $('.pending-comment').val()
                var pending_time = $('.pending-time').val()
                var pending_date = $('.pending-date').val()
                $('#pendingModal').modal('hide')
                const formData = new FormData()
                formData.append('id', id)
                formData.append('pending_reason', pending_reason)
                formData.append('pending_comment', pending_comment)
                formData.append('pending_time', pending_time)
                formData.append('pending_date', pending_date)
                formData.append('emp_id', emp_id)
                formData.append('call_again_attention', call_again_attention)

                fetch('../employee/pending.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then((res) => {
                        return res.json()
                    })
                    .then((response) => {
                        if (response.text === true) {
                            $('#wall_leads').DataTable().ajax.reload(null, false)
                            $('#wall_leads_dod').DataTable().ajax.reload(null, false)
                            swal.fire('Edit Status Done!', '', 'success')
                        } else {
                            swal.fire('Something wronge!', '', 'error')
                        }
                        $('.id').val(' ')
                        $('.pending-reason').val(' ')
                        $('.pending-comment').val(' ')
                        $('.pending-time').val(' ')
                        $('.pending-date').val(' ')
                        $('#pending_index')[0].reset()
                    })
            }
        })
    })
})