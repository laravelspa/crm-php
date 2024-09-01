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
        <h3 class="card-title">شهادات</h3>

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
            <button type="button" class="col-3 btn btn-outline-success btn-sm" data-toggle="modal" data-target="#newCertificateModal">
              <i class="fa fa-plus"></i> شهادة جديدة
            </button>
            <?php if (!$sessionID) { ?>
              <button type="button" id="deleteAllButton" class="col-3 btn btn-outline-danger btn-sm" onclick="deleteAll('../delete.php', 'certificates','بعد تأكيد الحذف سيتم إخفاء الشهادات.','يجب إحتيار شهادة واحدة على الأقل للحذف.','لقد تم إالغاء عملية الحذف.')">
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
                <th style="min-width: 100px" class="text-center">الرمز التعريفى</th>
                <th style="min-width: 15%" class="text-center">التاريخ</th>
                <th style="min-width: 20%" class="text-center">إسم المنشأة</th>
                <th style="min-width: 30%" class="text-center">رقم الجوال</th>
                <th style="min-width: 160px" class="text-center">رقم التسجيل الضريبى</th>
                <th style="min-width: 100px" class="text-center">تاريخ الإنشاء</th>
                <th style="min-width: 100px" class="text-center">تم بواسطة</th>
                <th style="min-width: 10%" class="text-center">تعديل</th>
                <th style="min-width: 10%" class="text-center">حذف</th>
                <th style="min-width: 10%" class="text-center">طباعة</th>
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
<div class="modal fade" id="newCertificateModal" tabindex="-1" role="dialog" aria-labelledby="newCertificateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form method="post" id="create_certificate">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="newcertificateModalLabel">إنشاء شهادة جديدة</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row">
          <div class="form-group col-12">
            <label for="date">التاريح (mm/dd/yyy)</label>
            <input type="date" id="date" class="form-control" required />
          </div>

          <div class="form-group col-12">
            <label for="facility_name" class="font-weight-bold">إسم المنشأة</label>
            <input class="form-control col-12" id="facility_name" type="text" placeholder="أكتب إسم المنشأة" required />
          </div>

          <div class="form-group col-12">
            <label for="facility_activity" class="font-weight-bold">نشاط المنشأة</label>
            <input class="form-control col-12" id="facility_activity" type="text" placeholder="أكتب نشاط المنشأة" required />
          </div>

          <div class="form-group col-12">
            <label for="facility_address" class="font-weight-bold">عنوان المنشأة</label>
            <input class="form-control col-12" id="facility_address" type="text" placeholder="أكتب عنوان المنشأة" required />
          </div>

          <div class="form-group col-12">
            <label for="mobile" class="font-weight-bold">رقم الجوال</label>
            <input class="form-control col-12" id="mobile" type="text" placeholder="أكتب رقم الجوال" required />
          </div>

          <div class="form-group col-12">
            <label for="commercial_register" class="font-weight-bold">رقم السجل التجارى</label>
            <input class="form-control col-12" id="commercial_register" type="text" placeholder="أكتب رقم السجل التجارى" required />
          </div>

          <div class="form-group col-12">
            <label for="no_civil_registry" class="font-weight-bold">رقم السجل المدنى</label>
            <input class="form-control col-12" id="no_civil_registry" type="text" placeholder="أكتب رقم السجل المدنى" required />
          </div>

          <div class="form-group col-12">
            <label for="internal_cameras" class="font-weight-bold">الكاميرات الداخلية</label>
            <input class="form-control col-12" id="internal_cameras" type="text" placeholder="أكتب الكاميرات الداخلية" required />
          </div>

          <div class="form-group col-12">
            <label for="external_cameras" class="font-weight-bold">الكاميرات الخارجية</label>
            <input class="form-control col-12" id="external_cameras" type="text" placeholder="أكتب الكاميرات الخارجية" required />
          </div>

          <div class="form-group col-12">
            <label for="recording_device" class="font-weight-bold">جهاز التسجيل</label>
            <input class="form-control col-12" id="recording_device" type="text" placeholder="أكتب جهاز التسجيل" required />
          </div>

          <div class="form-group col-12">
            <label for="storage_disk" class="font-weight-bold">مدة التسجيل</label>
            <input class="form-control col-12" id="storage_disk" type="text" placeholder="أكتب مدة التسجيل" required />
          </div>

          <div class="form-group col-12">
            <label for="recording_duration" class="font-weight-bold">قرص التخزين</label>
            <input class="form-control col-12" id="recording_duration" type="text" placeholder="أكتب قرص التخزين" required />
          </div>

          <div class="form-group col-12">
            <label for="display" class="font-weight-bold">شاشة العرض</label>
            <input class="form-control col-12" id="display" type="text" placeholder="أكتب شاشة العرض" required />
          </div>

          <div class="form-group col-12">
            <label for="other_specifications" class="font-weight-bold">مواصفات أخرى</label>
            <input class="form-control col-12" id="other_specifications" type="text" placeholder="أكتب مواصفات أخرى" required />
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
<div class="modal fade" id="editCertificateModal" tabindex="-1" role="dialog" aria-labelledby="editCertificateModalLabel" aria-hidden="true">
  <div class="modal-dialog nodal-lg" role="document">
    <form id="editCertificateForm" method="post">
      <div class="modal-content">
        <div class="modal-header text-danger">
          <h5 class="modal-title text-capitalize text-danger" id="editCertificateModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-right:-1rem;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row">
          <input type="hidden" class="edit_id" required />
          <div class="form-group col-12">
            <label for="newdate">التاريح (mm/dd/yyy)</label>
            <input type="date" id="newdate" class="newdate form-control" required />
          </div>

          <div class="form-group col-6">
            <label for="newfacility_name" class="font-weight-bold">إسم المنشأة</label>
            <input class="newfacility_name form-control col-12" id="newfacility_name" type="text" placeholder="أكتب إسم المنشأة" required />
          </div>

          <div class="form-group col-6">
            <label for="newfacility_activity" class="font-weight-bold">نشاط المنشأة</label>
            <input class="newfacility_activity form-control col-12" id="newfacility_activity" type="text" placeholder="أكتب نشاط المنشأة" required />
          </div>

          <div class="form-group col-6">
            <label for="newfacility_address" class="font-weight-bold">عنوان المنشأة</label>
            <input class="newfacility_address form-control col-12" id="newfacility_address" type="text" placeholder="أكتب عنوان المنشأة" required />
          </div>

          <div class="form-group col-6">
            <label for="newmobile" class="font-weight-bold">رقم الجوال</label>
            <input class="newmobile form-control col-12" id="newmobile" type="text" placeholder="أكتب رقم الجوال" required />
          </div>

          <div class="form-group col-6">
            <label for="newcommercial_register" class="font-weight-bold">رقم السجل التجارى</label>
            <input class="newcommercial_register form-control col-12" id="newcommercial_register" type="text" placeholder="أكتب رقم السجل التجارى" required />
          </div>

          <div class="form-group col-6">
            <label for="newno_civil_registry" class="font-weight-bold">رقم السجل المدنى</label>
            <input class="newno_civil_registry form-control col-12" id="newno_civil_registry" type="text" placeholder="أكتب رقم السجل المدنى" required />
          </div>

          <div class="form-group col-12">
            <label for="newinternal_cameras" class="font-weight-bold">الكاميرات الداخلية</label>
            <input class="newinternal_cameras form-control col-12" id="newinternal_cameras" type="text" placeholder="أكتب الكاميرات الداخلية" required />
          </div>

          <div class="form-group col-12">
            <label for="newexternal_cameras" class="font-weight-bold">الكاميرات الخارجية</label>
            <input class="newexternal_cameras form-control col-12" id="newexternal_cameras" type="text" placeholder="أكتب الكاميرات الخارجية" required />
          </div>

          <div class="form-group col-6">
            <label for="newrecording_device" class="font-weight-bold">جهاز التسجيل</label>
            <input class="newrecording_device form-control col-12" id="newrecording_device" type="text" placeholder="أكتب جهاز التسجيل" required />
          </div>

          <div class="form-group col-6">
            <label for="newstorage_disk" class="font-weight-bold">مدة التسجيل</label>
            <input class="newstorage_disk form-control col-12" id="newstorage_disk" type="text" placeholder="أكتب مدة التسجيل" required />
          </div>

          <div class="form-group col-6">
            <label for="newrecording_duration" class="font-weight-bold">قرص التخزين</label>
            <input class="newrecording_duration form-control col-12" id="newrecording_duration" type="text" placeholder="أكتب قرص التخزين" required />
          </div>

          <div class="form-group col-6">
            <label for="newdisplay" class="font-weight-bold">شاشة العرض</label>
            <input class="newdisplay form-control col-12" id="newdisplay" type="text" placeholder="أكتب شاشة العرض" required />
          </div>

          <div class="form-group col-12">
            <label for="newother_specifications" class="font-weight-bold">مواصفات أخرى</label>
            <input class="newother_specifications form-control col-12" id="newother_specifications" type="text" placeholder="أكتب مواصفات أخرى" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">إالغاء</button>
          <button type="submit" class="btn btn-sm btn-success save">حفظ</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Print -->
<div class="modal fade" id="printCertificateModal" tabindex="-1" role="dialog" aria-labelledby="printCertificateModalLabel" aria-hidden="true">
  <div class="modal-dialog nodal-xl" role="document">
    <form id="printCertificateForm" method="post">
      <div class="modal-content">
        <div class="modal-header text-danger">
          <h5 class="modal-title text-capitalize text-danger" id="printCertificateModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-right:-1rem;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row">
          <input type="hidden" id="printserial_number" class="printserial_number" />
          <div class="form-group col-12">
            <label for="newdate">التاريح</label>
            <span id="printdate" class="printdate"></span>
          </div>

          <div class="form-group col-12">
            <label for="printfacility_name" class="font-weight-bold">إسم المنشأة</label>
            <span class="printfacility_name col-12" id="printfacility_name"></span>
          </div>

          <div class="form-group col-12">
            <label for="printfacility_activity" class="font-weight-bold">نشاط المنشأة</label>
            <span class="printfacility_activity col-12" id="printfacility_activity"></span>
          </div>

          <div class="form-group col-12">
            <label for="printfacility_address" class="font-weight-bold">عنوان المنشأة</label>
            <span class="printfacility_address col-12" id="printfacility_address"></span>
          </div>

          <div class="form-group col-12">
            <label for="printmobile" class="font-weight-bold">رقم الجوال</label>
            <span class="printmobile col-12" id="printmobile"></span>
          </div>

          <div class="form-group col-12">
            <label for="printcommercial_register" class="font-weight-bold">رقم السجل التجارى</label>
            <span class="printcommercial_register col-12" id="printcommercial_register"></span>
          </div>

          <div class="form-group col-12">
            <label for="printno_civil_registry" class="font-weight-bold">رقم السجل المدنى</label>
            <span class="printno_civil_registry col-12" id="printno_civil_registry"></span>
          </div>

          <div class="form-group col-12">
            <label for="printinternal_cameras" class="font-weight-bold">الكاميرات الداخلية</label>
            <span class="printinternal_cameras col-12" id="printinternal_cameras"></span>
          </div>

          <div class="form-group col-12">
            <label for="printexternal_cameras" class="font-weight-bold">الكاميرات الخارجية</label>
            <span class="printexternal_cameras col-12" id="printexternal_cameras"></span>
          </div>

          <div class="form-group col-12">
            <label for="printrecording_device" class="font-weight-bold">جهاز التسجيل</label>
            <span class="printrecording_device col-12" id="printrecording_device"></span>
          </div>

          <div class="form-group col-12">
            <label for="printstorage_disk" class="font-weight-bold">مدة التسجيل</label>
            <span class="printstorage_disk col-12" id="printstorage_disk"></span>
          </div>

          <div class="form-group col-12">
            <label for="printrecording_duration" class="font-weight-bold">قرص التخزين</label>
            <span class="printrecording_duration col-12" id="printrecording_duration"></span>
          </div>

          <div class="form-group col-12">
            <label for="printdisplay" class="font-weight-bold">شاشة العرض</label>
            <span class="printdisplay col-12" id="printdisplay"></span>
          </div>

          <div class="form-group col-12">
            <label for="printother_specifications" class="font-weight-bold">مواصفات أخرى</label>
            <span class="printother_specifications col-12" id="printother_specifications"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">إالغاء</button>
          <button type="submit" class="btn btn-sm btn-success save">حفظ</button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>

<?php include('../main/footer.php'); ?>
<script src="../js/certificates.js"></script>
</body>

</html>