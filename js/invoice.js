$(document).ready(function() {
    $(document).on('click', '#invoice_btn', function() {
        var ids = $(this).data('id');
        invoiceOrder(ids)
    })

    function invoiceOrder(ids) {
        var selected = [];
        
        const formData = new FormData();
        formData.append('ids', ids);
       
        swal.fire({
          title: "Are you sure?",
          text: 'To export this invoice',
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((Invoice) => {
            if (Invoice.value === true) {
                fetch('invoice_order.php', {
                    method: 'POST',
                    body: formData
                }).then(res => {
                    return res.json();
                }).then(response => {
                    $('#InvoiceModal').modal('show')
                    if(response.text === true) {
                        var one = response.one.length > 1 ? true : false;
                        var two = response.two.length > 1 ? true : false;
                        var three = response.three.length > 1 ? true : false;
                        
                        var container = document.getElementById('invoice_wrapper');
                        container.innerHTML = `
                        <div id="container_invoice">
                            <div class="d-flex">
                                <div class="border border-primary font-weight-bold p-2 col-4 d-flex flex-wrap">
                                    <span class="col-12">Company</span>
                                    <span class="col-12">HealthyCure</span>
                                </div>
                                <div class="border border-primary col-8 text-center p-2">
                                    <svg id="barcode_value"></svg>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="border border-primary font-weight-bold p-2 col-3 d-flex flex-column justify-content-center">
                                    <span>Destination</span>
                                    <span>${response.city}</span>
                                </div>
                                <div class="border border-primary font-weight-bold p-2 col-3 d-flex flex-column justify-content-center">
                                    <span>Origin</span>
                                    <span>${response.city}</span>
                                </div>
                                <div class="border border-primary font-weight-bold p-2 col-6 d-flex flex-column justify-content-center">
                                    <div>
                                        <span>Date: </span>
                                        <span>${response.date}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="d-flex justify-content-center align-items-center border border-primary font-weight-bold p-2 col-3">
                                    <span>DOM</span>
                                </div>
                                <div class="d-flex justify-content-center align-items-center border border-primary font-weight-bold p-2 col-3">
                                    <span>ONP</span>
                                </div>
                                <div class="d-flex flex-column border border-primary font-weight-bold p-2 col-6">
                                    <div>
                                        <span>Customer: </span>
                                        <span>${response.name}</span>
                                    </div>
                                    <div>
                                        <span>Order ID: </span>
                                        <span>${"'"+response.ids+"'"}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-primary font-weight-bold p-2">
                                <div class="d-flex flex-wrap">
                                    <div class="col-12 p-2">
                                        <span>Services: </span>
                                        <span>COD</span>
                                    </div>          
                                    <div class="col-12 p-2">
                                        <span>Chargeable: </span>
                                        <span>${response.wod}</span>
                                    </div>
                                </div>
                                <h4 class="p-2 bg-primary text-white col-12">Services Code</h4>
                                <div class="col-12">
                                    <span>Quantity: </span>
                                    <span>${(one?Number(response.one[0]):0)+(two?Number(response.two[0]):0)+(three?Number(response.three[0]):0)}</span>
                                </div>
                                <div class="col-12">
                                    <label class="col-12">Products:</label>
                                    <ul>
                                        ${(one?'<li>'+response.one[1]+'</li>':'')}
                                        ${(two?'<li>'+response.two[1]+'</li>':'')}
                                        ${(three?'<li>'+response.three[1]+'</li>':'')}
                                    </ul>
                                </div>             
                            </div>

                            <div class="font-weight-bold border border-primary p-2">
                                <div class="col-12">
                                    <div class="col-12">
                                        <span>Account: </span>
                                        <span>${(one?Number(response.one[2]):0)+(two?Number(response.two[2]):0)+(three?Number(response.three[2]):0)} L.E</span>
                                    </div>
                                    <div class="col-12">
                                        <span>Address: </span>
                                        <span>${response.address}</span>
                                    </div>
                                    <div class="col-12">
                                        <span>Customers Services: </span>
                                        <span>0123456789</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <span>EG</span>
                                    </div>
                                </div>
                            </div>

                            <div class="font-weight-bold border border-primary p-2">
                                <div class="col-12">
                                    <div class="col-12">
                                        <span>Customer Name: </span>
                                        <span>${response.name}</span>
                                    </div>
                                    <div class="col-12">
                                        <span>Customer Address: </span>
                                        <span>${response.address}</span>
                                    </div>
                                    <div class="col-12">
                                        <span>Customer Phone: </span>
                                        <span>${response.phone}</span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <span>EG</span>
                                    </div>          
                                </div>
                            </div>
                            
                            <div class="col-12 font-weight-bold border border-primary p-2">
                                <div class="col-12">
                                    <span>Shipper Ref: </span>
                                    <span>${response.wod}</span>
                                </div>
                                <div class="col-12">
                                    <span>Consignee Ref: </span>
                                    <span>${"'"+response.ids+"'"}</span>
                                </div>
                            </div>
                        </div>
                        `

                        JsBarcode('#barcode_value',response.ids, {
                            width: 1,
                            height: 40,
                            displayValue: false
                        });
                    } else {
                        swal.fire("Something wronge!", '', "error");
                    }
                })        
            } else {
                swal.fire('Cancel Invoice', '', 'warning');
            } 
        })
    }

    document.getElementById('invoice_print').addEventListener('click', function() {
        var pe = $('#InvoiceModal .modal-content .modal-body #container_invoice');
        domtoimage.toJpeg(pe[0], { bgcolor: "#fff", quality: 0.95, width:"800" })
        .then(function (dataUrl) {
            saveAs(dataUrl,'image.jpg');
        });
        function saveAs(url, filename) {
            var link = document.createElement('a');
     
            if (typeof link.download === 'string') {
                link.href = url;
                link.download = filename;
                document.body.appendChild(link);
                link.click();   
                document.body.removeChild(link);
            }
        }   
    })

    document.getElementById('invoice_pdf').addEventListener('click', function() {
        var pe = $('#InvoiceModal .modal-content .modal-body #container_invoice');
        domtoimage.toJpeg(pe[0], { bgcolor: "#fff", quality: 0.95, width:"800" })
        .then(function (dataUrl) {
            var doc = new jsPDF();
            doc.addImage(dataUrl, 'JPEG',0,0);
            doc.save('a4.pdf');
        });
    })
});