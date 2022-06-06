<?php
    ob_start();
    session_start();
    include('../main/database.php');
    include('../main/header1.php');  
    // Employees Select Box
    $stmt = $con->prepare('SELECT * FROM admins WHERE permission = 2');
    $stmt->execute();
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- row -->
<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Products</h3>

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
            <div class="d-flex flex-row-reverse col-lg-3 col-md-12 col-sm-12 float-right">
                 <button type="button" data-toggle="modal" data-target="#createProjectModal" class="btn btn-outline-success btn-sm"><i class="fas fa-sm fa-plus"></i> New 
                </button>
            </div>
            <table id="table_id" class="table table-hover table-bordered table-responsive"  style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 150px;">Product</td>
                        <td style="min-width: 100px;">Leads</td>
                        <td style="min-width: 100px;">Created by</td>
                        <td style="min-width: 90px;" class="text-center">Actions</td>
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
            <h3 class="card-title">Dublicate</h3>

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
            <table id="table_dublicate" class="table table-hover table-bordered table-responsive" style="width:100%">
                <thead>
                    <tr>
                        <td style="min-width: 150px;">Product</td>
                        <td style="min-width: 100px;">Leads</td>
                        <td style="min-width: 100px;">Created by</td>
                        <td style="min-width: 50px;">Delete</td>
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
<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="create_project_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize" id="createProjectModalLabel">
                        New Product
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <input type="hidden" id="admin_name" value="<?php echo $_SESSION['id']; ?>" />
                        <input type="hidden" id="first_time" value="true" />
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="product_name">Product <span class="text-danger">*</span></label>
                            <input class="form-control col-12" id="product_name" type="text"  placeholder="Ex: Product name" required />
                        </div>
                        <div class="row col-12">
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="product_price">Price 
                                    <span class="text-danger">*</span></label>
                                <input placeholder="Ex: 200" class="form-control col-12" id="product_price" type="number" step="0.25" min="0" required />
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="currency">Currency 
                                <span class="text-danger">*</span></label>
                                <select class="form-control pb-0" id="currency">
                                    <option value="USD" selected>USD</option>
                                    <option value="EGP">EGP</option>
                                    <option value="SAR">SAR</option>
                                    <option value="KWD">KWD</option>
                                    <option value="AED">AED</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="employee">Employee <span class="text-danger">*</span></label>
                            <select class="form-control col-12 pb-0" id="employee">
                                <option value="null">None</option>
                                <?php foreach($employes as $emp) { ?>
                                    <option class="text-capitalize" value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="create_leads_excel">
                              <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
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
        <form name="edit_project">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title font-weight-bold text-capitalize" id="editModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <div class="form-group col-12">
                            <label class="font-weight-bold" for="product_name">Product <span class="text-danger">*</span></label>
                            <input class="form-control col-12" id="product_n" type="text" placeholder="Add Product Name" required />
                        </div>
                        <div class="row col-12">
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="product_price">Price <span class="text-danger">*</span></label>
                                <input class="form-control col-12" id="product_p" type="number" step="0.25" min="0" placeholder="Add Product Price" required />
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold" for="product_c">Currency <span class="text-danger">*</span></label>
                                <select class="form-control pb-0" id="product_c" required>
                                    <option value="USD" selected>USD</option>
                                    <option value="EGP">EGP</option>
                                    <option value="SAR">SAR</option>
                                    <option value="KWD">KWD</option>
                                    <option value="AED">AED</option>
                                </select>
                            </div>
                        </div>
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

<!-- Add More Lead Modal -->
<div class="modal fade" id="AddLeadModal" tabindex="-1" role="dialog" aria-labelledby="AddLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="add_leads_form">
            <div class="modal-content">
                <div class="modal-header text-danger" >
                    <h5 class="modal-title text-capitalize" id="AddLeadModalLabel">
                        Add Leads
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <input type="hidden" id="admin_name_1" value="<?php echo $_SESSION['id']; ?>" />
                        <input type="hidden" id="add_pr_lead" value="add_pr_lead" />
                        <input hidden="true" class="hidden" id="hidden_pn" />
                        <input hidden="true" class="hidden" id="hidden_prn" />
                        <input hidden="true" class="hidden" id="hidden_prp" />
                        <input hidden="true" class="hidden" id="hidden_prc" />
                        <div class="form-group col-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="add_leads_excel" />
                              <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label for="add_employee">Employee</label>
                            <select class="form-control col-12 pb-0" id="add_employee">
                                <option value="null">None</option>
                                <?php foreach($employes as $emp) { ?>
                                    <option value="<?php echo $emp['id'] ?>"><?php echo $emp['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success addLead_button">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
    <script src="../js/read-excel-file.min.js"></script>
    <?php include('../main/footer1.php'); ?>
    <script src="../js/projects.js"></script>    
</body>
</html>