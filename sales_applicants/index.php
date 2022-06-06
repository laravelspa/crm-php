<?php
ob_start();
session_start();
if ($_SESSION['permission'] !== '0') {
  header('Location: ../login.php');
}
include('../main/header1.php');
?>
<!-- row -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-transparent">
        <h3 class="card-title">Users</h3>

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
        <div class="table-responsive">
          <table id="sales_applicants" class="table table-hover table-bordered table-responsive" style="width:100%">
            <thead>
              <tr>
                <th style="min-width: 10px" class="text-center">id</th>
                <th style="min-width: 150px" class="text-center">Name</th>
                <th style="min-width: 100" class="text-center">Phone</th>
                <th style="min-width: 100%" class="text-center">Comment</th>
                <th style="min-width: 50%" class="text-center">Editor</th>
                <th style="min-width: 150px" class="text-center">Created at</th>
                <th style="min-width: 50px" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<!-- Modal Edit -->
<div class="modal fade" id="editApplicantModal" tabindex="-1" role="dialog" aria-labelledby="editApplicantModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="edit_applicant_from">
        <div class="modal-header text-danger">
          <h5 class="modal-title text-capitalize text-danger" id="editApplicantModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" class="edit_id" required />
          <input type="hidden" class="editor" value="<?php echo $_SESSION['username']; ?>" required />
          <div class="form-group p-2">
            <label for="newname" class="font-weight-bold">Name</label>
            <input type="text" placeholder="Add Name" class="newname form-control" id="newname" required />
          </div>
          <div class="form-group p-2">
            <label for="newphone" class="font-weight-bold">Name</label>
            <input type="text" placeholder="Add Phone" class="newphone form-control" id="newphone" required />
          </div>
          <div class="form-group p-2">
            <div class="form-group col-12">
              <label class="font-weight-bold" for="newcomment">Comment</label>
              <textarea type="text" id="newcomment" class="form-control col-12 newcomment" placeholder="Add your comment"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-sm btn-success save">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include('../main/footer1.php'); ?>
<script>
  $(document).ready(function() {
    // Edit Modal
    $(document).on('click', '#edit_applicant', function() {
      const headerModal = document.querySelector('#editApplicantModalLabel');
      var id = $(this).data('id');
      var name = $(this).data('name');
      var phone = $(this).data('phone');
      var comment = $(this).data('com');

      headerModal.textContent = 'Edit ' + name;

      $('.edit_id').val(id);
      $('.newname').val(name);
      $('.newphone').val(phone);
      $('.newcomment').val(comment);
    })

    // Edit Form
    const formEdit = $('#edit_applicant_from');
    if (formEdit) {
      formEdit.on('submit', (e) => {
        e.preventDefault();
        $('#editApplicantModal').modal('hide');
        swal.fire({
          title: "Are you sure change this information?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willUpdate) => {
          var editor = $('.editor').val();
          var editId = $('.edit_id').val();
          var newname = $('.newname').val();
          var newphone = $('.newphone').val();
          var newcomment = $('.newcomment').val();

          const formData = new FormData();

          formData.append('editor', editor);
          formData.append('id', editId);
          formData.append('name', newname);
          formData.append('phone', newphone);
          formData.append('comment', newcomment);

          if (willUpdate.value === true) {
            fetch('edit_applicant.php', {
              method: 'POST',
              body: formData
            }).then(res => {
              return res.json();
            }).then(response => {
              if (response.text === true) {
                formEdit[0].reset();
                $('#sales_applicants').DataTable().ajax.reload(null, false);
                swal.fire("Updated Done!", '', "success");
              } else {
                swal.fire("Something wronge!", '', "error");
              }
            })
          } else {
            swal.fire("Your applicant not updated!!", '', "warning");
          }
        })
      })
    }


    $.fn.dataTable.ext.classes.sPageButton = "paginate_custom_buttons";
    $('#sales_applicants').DataTable({
      dom: "lBfrtip",
      stateSave: true,
      processing: true,
      serverSide: true,
      order: [],
      ajax: {
        url: 'fetch_sales_applicants.php',
        method: "POST",
        data: {},
      },
      language: {
        search: "",
        searchPlaceholder: "Search.....",
        processing: '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>',
      },
      lengthMenu: [
        [10, 15, 20, 25, 30, 50, 100, 200, 300],
        [10, 15, 20, 25, 30, 50, 100, 200, 300],
      ],
      columnDefs: [{
        orderable: false,
        targets: 0
      }],
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
  })
</script>
</body>

</html>