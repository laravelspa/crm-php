<?php 
ob_start();
session_start();
if($_SESSION['permission'] !== '0') {
    header('Location: /login.php');
}
include('../main/header1.php'); 
include('../main/database.php'); ?>
<?php 
    $stmt = $con->prepare("SELECT prname FROM pending GROUP BY prname");
    $stmt->execute();
    $prname = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="d-flex flex-row-reverse col-lg-12 bg-white p-2 mt-1 mb-1">
     <button type="button" data-toggle="modal" data-target="#createProductModal" class="btn btn-outline-success btn-sm"><i class="fas fa-sm fa-plus"></i> New 
    </button>
</div>	
<!-- row -->
<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Aramex</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="table_aramex" class="table table-hover table-bordered table-responsive"  style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 10px;">id</td>
                        <td style="min-width: 150px;">Product</td>
                        <td style="min-width: 50px;">Quantity</td>
                        <td style="min-width: 100px;">Actions</td>
                        <td style="min-width: 150px;">Created at</td>
                        <td style="min-width: 150px;">Updated at</td>
                    </tr>
                </thead>
                <tbody></tbody> 
            </table>        
          <!-- /.card-body -->
          </div>
        <!-- /.card -->
        </div>
    </div>
    <div class="col-lg-6 col-sm-12">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Alexandria</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="table_alex" class="table table-hover table-bordered table-responsive" style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 10px;">id</td>
                        <td style="min-width: 150px;">Product</td>
                        <td style="min-width: 50px;">Quantity</td>
                        <td style="min-width: 150px;">Actions</td>
                        <td style="min-width: 150px;">Created at</td>
                        <td style="min-width: 150px;">Updated at</td>
                    </tr>
                </thead>
                <tbody></tbody> 
            </table>
            <!-- /.card-body -->
          </div>
        <!-- /.card -->
        </div>
    </div>
       <div class="col-lg-6 col-sm-12">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Cairo</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="table_cairo" class="table table-hover table-bordered table-responsive" style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 10px;">id</td>
                        <td style="min-width: 150px;">Product</td>
                        <td style="min-width: 50px;">Quantity</td>
                        <td style="min-width: 150px;">Actions</td>
                        <td style="min-width: 150px;">Created at</td>
                        <td style="min-width: 150px;">Updated at</td>
                    </tr>
                </thead>
                <tbody></tbody> 
            </table>
            <!-- /.card-body -->
          </div>
        <!-- /.card -->
        </div>
    </div>
<!-- /.row -->
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create_product_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize" id="createProductModalLabel">
                        New product stock
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-12">
                        <label for="product_name">Product</label>
                        <select id="product_name" class="form-control col-12" required>
                            <?php foreach ($prname as $value) { ?>
                                <option value="<?php echo $value['prname']; ?>">
                                    <?php echo $value['prname']; ?>
                                </option>
                            <?php } ?>
                            <option value="0">New Product</option>
                        </select>
                    </div>
                    <div class="form-group col-12 prname_container" style="display: none;">
                        <input placeholder="Add Product Name" class="form-control col-12" id="product_name1" type="text" />
                    </div>
                    <div class="form-group col-12">
                        <label for="product_quantity">Quantity</label>
                        <input placeholder="Ex: 200" class="form-control col-12" id="product_quantity" type="number" step="1" min="1" required />
                    </div>
                    <div class="form-group col-12">
                        <label for="inventory">Inventory</label>
                        <select class="form-control pb-0" id="inventory">
                            <option value="1" selected>Aramex</option>
                            <option value="2">Alexandria</option>
                            <option value="3">Cairo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button id="create_project" type="submit" class="btn btn-sm btn-success">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="edit_product">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize" id="editModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inventory_id">
                    <input type="hidden" id="product_id">
                    <div class="form-group col-12">
                        <label for="product">Product</label>
                        <select id="product" class="form-control col-12" required>
                            <?php foreach ($prname as $value) { ?>
                                <option value="<?php echo $value['prname']; ?>">
                                    <?php echo $value['prname']; ?>
                                </option>
                            <?php } ?>
                            <option value="0">New Product</option>
                        </select>
                    </div>
                    <div class="form-group col-12 prname_container_edit" style="display: none;">
                        <input placeholder="Add Product Name" class="form-control col-12" id="product_name2" type="text" />
                    </div>
                    <div class="form-group col-12">
                        <label for="quantity">Quantity</label>
                        <input class="form-control col-12" id="quantity" type="number" step="1" min="1" placeholder="Add Product Quantity" required />
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success edit_button">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Quantity Modal -->
<div class="modal fade" id="AddLeadModal" tabindex="-1" role="dialog" aria-labelledby="AddLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="add_quantity_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize" id="AddLeadModalLabel">
                        Add Quantity
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_pro" />
                    <div class="form-group col-12">
                        <input class="form-control col-12" id="quantity_number" type="number" step="1" min="1" placeholder="Add Product Quantity" required />
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include('../main/footer1.php'); ?>
<script type="text/javascript">
	document.title = 'HealthyCURE | Inventory'
	getRecords('fetch_aramex_products.php','','','','','','','#table_aramex','')
	getRecords('fetch_alex_products.php','','','','','','','#table_alex','')
	getRecords('fetch_cairo_products.php','','','','','','','#table_cairo','')
	$(document).ready(function() {
        $(document).on('change','#product_name', function(e){ 
            var selectVal   = $('#product_name').val();
            var product1Cont = $('.prname_container')[0];
            if(selectVal === '0') {
                product1Cont.style.display = 'block';
                $('#product_name1').attr('required', 'required');
            } else {
                product1Cont.style.display = 'none';
                $('#product_name1').removeAttr('required');
            }
        });

	    const formCreateProduct = $('#create_product_form');
	    if(formCreateProduct) {
	        formCreateProduct.on('submit', (e) => {
	            e.preventDefault();
	            var Product = $('#product_name').val();
                var Product1 = $('#product_name1').val();
	            var Quantity = $('#product_quantity').val();
	            var Inventory = $('#inventory').val();
	            
                if(Product === '0') {
                    Product = Product1
                } 

	            var formdata = new FormData();
                formdata.append('product', Product);
                formdata.append('quantity', Quantity);
                formdata.append('inventory', Inventory);
                $('#createProductModal').modal('hide');
              
                fetch('insert_product.php', {
                    method: 'POST',
                    body: formdata
                }).then(res => {
                    return res.json();
                }).then(r => { 
                    $('.prname_container')[0].style.display = 'none';
                    if(r.text === true) {
                        swal.fire("Created Done!", Product, "success");
                        $('#table_aramex').DataTable().ajax.reload();
                        $('#table_alex').DataTable().ajax.reload();
                        $('#table_cairo').DataTable().ajax.reload();
                        formCreateProduct[0].reset();                    
                    } else {
                        swal.fire("Something wronge!", '', "error");
                        formCreateProduct[0].reset();
                    }
                })
	        });
	    }

        // Edit Project Modal
        $(document).on('click','#edit_project', function(e){
            const headerEditModal = document.querySelector('#editModalLabel');
            let id       = $(this).data('id');
            let product      = $(this).data('prn');
            let quantity = $(this).data('qua');
            let inventory_id = $(this).data('invid');
            let product2Cont = $('.prname_container_edit')[0];
            let productsAll = <?php echo json_encode($prname); ?>;
            let getValues = productsAll.map(function(item){ return item.prname; })
            let result = getValues.includes(product);

            $(document).on('change','#product', function(e){
                let product2Cont = $('.prname_container_edit')[0]; 
                let product      = $('#product').val();
                if(product === '0') {
                    product2Cont.style.display = 'block';
                    $('#product_name2').attr('required', 'required');
                } else {
                    product2Cont.style.display = 'none';
                    $('#product_name2').removeAttr('required');
                }
            });

            if(result === false) {
                product2Cont.style.display = 'block';
                $('#product').val('0')
                $('#product_name2').val(product)
                $('#product_name2').attr('required', 'required');
            } else {
                product2Cont.style.display = 'none';
                $('#product').val(product)
                $('#product_name2').removeAttr('required');
            }

            headerEditModal.textContent = 'Edit ' + product ;
            
            $('#product_id').val(id);
            $('#inventory_id').val(inventory_id);
            $('#quantity').val(quantity);
            
            const formEditProject = document.querySelector('form[name=edit_product]');

            if(formEditProject) {
                formEditProject.addEventListener('submit', (e) => {
                    e.preventDefault();
                    var product_id          = $('#product_id').val();
                    var inventory           = $('#inventory_id').val();
                    var product_name        = $('#product').val();
                    var product_quantity    = $('#quantity').val();
                    var Product2            = $('#product_name2').val();
                    
                    if(product_name === '0') {
                        product_name = Product2
                    }
                    const formData = new FormData();
                    
                    formData.append('product_id', product_id);
                    formData.append('inventory', inventory);
                    formData.append('product_name', product_name);
                    formData.append('product_quantity', product_quantity);
                    swal.fire({
                      title: "Are you sure?",
                      text: "To Update This Product!",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((Update) => { 
                        $('#editModal').modal('hide');
                        if(Update.value === true) {
                            fetch('update_products.php', {
                                method: 'POST',
                                body: formData
                            }).then(res => {
                                return res.json();
                            }).then(response => {
                                if(response.text === true) {
                                    $('#table_aramex').DataTable().ajax.reload();
                                    $('#table_alex').DataTable().ajax.reload();
                                    $('#table_cairo').DataTable().ajax.reload();
                                    swal.fire("Edit Done!", '', "success");
                                } else {
                                    swal.fire("Something wronge!", '', "error");
                                }
                            })
                        } else {
                            swal.fire("Not changing any thing!", '', "warning");
                        }
                    });
                })
            }
        });
        
        $(document).on('click','#add_quantity', function(){ 
            var id = $(this).data('id');
            $('#id_pro').val(id)

            const formAddQuantity = $('#add_quantity_form');
            var count = 0;
            if(formAddQuantity) {
                formAddQuantity.on('submit', (e) => {
                    e.preventDefault();
                    count++;
                    if(count === 1) {
                        $('#AddLeadModal').modal('hide');
                        var addQuantity  = $('#quantity_number').val();     
                        var id_product = $('#id_pro').val();
                        const formData = new FormData();
                        formData.append('id', id_product);
                        formData.append('add_quantity', addQuantity);
                    
                        fetch('add_quantity.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => {
                            return res.json();
                        }).then(response => {
                            $('#quantity_number').val(' ');
                            if(response.text === true) {
                                $('#table_aramex').DataTable().ajax.reload();
                                $('#table_alex').DataTable().ajax.reload();
                                $('#table_cairo').DataTable().ajax.reload();
                                swal.fire("Add Quantity Done!", '', "success");
                            } else {
                                swal.fire("Something wronge!", '', "error");
                            }
                        })
                    }
                })
            }
        });
	});

function deleteProduct(id,url,table_html,table_db) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('table', table_db);
    swal.fire({
      title: "Are you sure?",
      text: "Once deleted, You will remove this product!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete.value === true) {
        fetch(url, {
            method: 'POST',
            body: formData
        }).then(res => {
            return res.json();
        }).then(response => {
            if(response.text === true) {
                $('#table_aramex').DataTable().ajax.reload();
                $('#table_alex').DataTable().ajax.reload();
                $('#table_cairo').DataTable().ajax.reload();
                swal.fire("Your product has been deleted!", '','success');
            } else {
                swal.fire("Something wronge!", '',"error");
            }
        })
      } else {
        swal.fire("Your product is safe!", '', "warning");
      }
    });
}
</script>
</body>
</html>