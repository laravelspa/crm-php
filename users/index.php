<?php
ob_start();
session_start();
$sessionID = $_SESSION['id'];
include('../main/database.php');

include('../main/header.php');
?>
<!-- row -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-transparent">
        <h3 class="card-title">المستخدمين</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="mt-2 d-flex justify-content-end">
          <div class="d-flex justify-content-around col-lg-6 col-md-12 col-sm-12">
            <!-- Button trigger modal -->
            <button type="button" class="col-3 btn btn-outline-success btn-sm" data-toggle="modal" data-target="#newUserModal">
              <i class="fa fa-plus"></i> مستخدم جديد
            </button>
            <?php if (!$sessionID) { ?>
              <button type="button" id="deleteAllButton" class="col-3 btn btn-outline-danger btn-sm" onclick="deleteAll('../delete.php', 'users','بعد تأكيد الحذف سيتم إخفاء المستخدمين.','يجب إحتيار مستخدم واحد على الأقل للحذف.','لقد تم إالغاء عملية الحذف.')">
                <i class="fa fa-trash"></i> حذف
              </button>
            <?php } ?>
          </div>
        </div>
        <div class="table-responsive">
          <table id="table_id" class="table table-hover table-bordered table-responsive" style="width:100%">
            <thead>
              <tr>
                <th class="text-center" style="max-width:10px">
                  <input type="checkbox" class="checkedAll" />
                </th>
                <th style="min-width: 20%" class="text-center">الرمز التعريفى</th>
                <th style="min-width: 40%" class="text-center">إسم المستخدم</th>
                <th style="min-width: 20%" class="text-center">هل هو أدمن؟</th>
                <th style="min-width: 10%" class="text-center">تعديل</th>
                <th style="min-width: 10%" class="text-center">حذف</th>
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
<!-- Modal -->
<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="create_user">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="newUserModalLabel">إنشاء مستخدم جديد</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group col-12">
            <label for="name" class="font-weight-bold">الإسم</label>
            <input class="form-control col-12" id="name" type="text" placeholder="أكتب الإسم" required />
          </div>
          <div class="form-group col-12">
            <label for="password" class="font-weight-bold">كلمة المرور</label>
            <input class="form-control col-12 password" id="password" type="password" placeholder="أكتب كلمة المرور" required />
          </div>
          <div class="form-group col-12">
            <label for="is_admin" class="font-weight-bold">هل هو أدمن؟</label>
            <select class="form-control col-12 is_admin" id="is_admin" required>
              <option value="1">نعم</option>
              <option value="0">لا</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">إالغاء</button>
          <button name="submit" class="btn btn-sm btn-success create">حفظ</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editUserForm" method="post">
      <div class="modal-content">
        <div class="modal-header text-danger">
          <h5 class="modal-title text-capitalize text-danger" id="editUserModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-right:-1rem;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" class="edit_id" required />
          <input type="hidden" class="oldpassword" required />
          <div class="form-group p-2">
            <label for="newname" class="font-weight-bold">الإسم</label>
            <input type="text" placeholder="أكتب الإسم" class="newname form-control" id="newname" required />
          </div>
          <div class="form-group col-12">
            <label for="newpassword" class="font-weight-bold">كلمة المرور</label>
            <input class="form-control col-12 newpassword" id="newpassword" type="password" placeholder="أكتب كلمة المرور" />
          </div>
          <?php if (!$sessionID) { ?>
            <div class="form-group p-2">
              <label for="newisadmin" class="font-weight-bold">هل هو أدمن؟</label>
              <select id="newisadmin" class="newisadmin form-control" required>
                <option value="1">نعم</option>
                <option value="0">لا</option>
              </select>
            </div>
          <?php } ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">إالغاء</button>
          <button type="submit" class="btn btn-sm btn-success save">حفظ</button>
        </div>
    </form>
  </div>
</div>
</div>

<?php include('../main/footer.php'); ?>
<script src="../js/users.js"></script>
</body>

</html>