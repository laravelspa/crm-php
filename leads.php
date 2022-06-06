<?php
    include('database.php');
    if(isset($_POST['filter_submit'])) {
        if($_POST['filter_box'] !== 'all') {
            $explode = explode('_', $_POST['filter_box']);
            // All Sales 
            $sales = $con->prepare('SELECT * FROM sales WHERE '. $explode[0] .' = "'. $explode[1] .'"');
            $sales->execute();
            $salesCount = $sales->rowCount();
            $salesAll = $sales->fetchAll();
        } else {
           // All Sales 
            $sales = $con->prepare("SELECT * FROM `sales`");
            $sales->execute();
            $salesCount = $sales->rowCount();
            $salesAll = $sales->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        // All Sales 
        $sales = $con->prepare("SELECT * FROM `sales`");
        $sales->execute();
        $salesCount = $sales->rowCount();
        $salesAll = $sales->fetchAll(PDO::FETCH_ASSOC);
    }
    // Group By Country
    $country = $con->prepare("SELECT country, count(*) FROM sales WHERE country != 'غير مطلوب' GROUP BY country");
    $country->execute();
    $countCountry = $country->rowCount();
    $c = $country->fetchALL(PDO::FETCH_KEY_PAIR);
    // var_dump($c);
    
    // Group By Date
    $date = $con->prepare("SELECT date, count(*) FROM sales WHERE date != 'غير مطلوب' GROUP BY date");
    $date->execute();
    $countDate = $date->rowCount();
    $d = $date->fetchALL(PDO::FETCH_KEY_PAIR);
    // var_dump($d);
    
    // Group By Form
    $form = $con->prepare("SELECT form, count(*) FROM sales WHERE form != 'غير مطلوب' GROUP BY form");
    $form->execute();
    $countForm = $form->rowCount();
    $f = $form->fetchALL(PDO::FETCH_KEY_PAIR);
    // var_dump($f);
    
    // Group By Article
    $article = $con->prepare("SELECT article_title, count(*) FROM sales WHERE article_title != 'غير مطلوب' GROUP BY article_title");
    $article->execute();
    $countArticle = $article->rowCount();
    $a = $article->fetchALL(PDO::FETCH_KEY_PAIR);
    // var_dump($a);

    include('header.php');  
?>

<div class="container mt-5">
   <div class="text-right mb-2 d-flex" dir="rtl">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteAll('delete.php', 'sales')"> أحذف المحدد</button>
        <form method="post" class="d-flex">
            <select class="form-control pb-0" name="filter_box">
                <?php
                    echo '<option value="all">--- الكل ---</option>';
                    echo '<optgroup label="البلد">';
                        if($countCountry > 0) {
                            foreach($c as $key => $value) {
                                if($key !== 'غير مطلوب') {
                                    echo '<option value="country_'. $key .'">' . $key . ' - '. $value . ' فرد</option>';    
                                } 
                            }
                        } else {
                            echo '<option disabled>لا توجد نتائج</option>';
                        }
                    echo '</optgroup>';
                    
                    echo '<optgroup label="التاريخ">';
                        if($countDate > 0) {
                            foreach($d as $key => $value) {
                                if($key !== 'غير مطلوب') {
                                    echo '<option value="date_'. $key .'">'  . $key . ' - '. $value . ' فرد</option>';    
                                }
                            }
                        } else {
                            echo '<option disabled>لا توجد نتائج</option>';
                        }
                    echo '</optgroup>';
                    
                    echo '<optgroup label="الفورم">';
                        if($countForm > 0) {
                            foreach($f as $key => $value) {
                                $path = parse_url($key, PHP_URL_PATH);
                                $components = explode('/', $path);
                                $last_part = end($components);
                                if($key !== 'غير مطلوب') {
                                    echo '<option value="form_'. $key .'" dir="ltr" > فرد ' . $value . ' - '. $last_part . '</option>';
                                }
                            }
                        } else {
                            echo '<option disabled>لا توجد نتائج</option>';
                        }
                    echo '</optgroup>';
                    
                    echo '<optgroup label="المقال">';
                        if($countArticle > 0) {
                            foreach($a as $key => $value) {
                                if($key !== 'غير مطلوب') {
                                    echo '<option value="article_'. $key .'">' . $key . ' - '. $value . ' فرد</option>';    
                                }
                            }
                        } else {
                            echo '<option disabled>لا توجد نتائج</option>';
                        }
                    echo '</optgroup>';
                ?>               
            </select>
            <button type="submit" name="filter_submit" class="btn btn-outline-success btn-sm">أبدأ الفلترة</button>
        </form>
   </div>
   <table id="table_id" class="table table-striped table-bordered table-responsive" dir="rtl" style="width:100%">
        <thead>
            <tr>
                <th scope="col" class="text-center">
                    <input type="checkbox" class="checkedAll" />  
                </th>
                <th>id</th>
                <th style="min-width:100px;">الأسم</th>
                <th style="min-width:100px;">الأسم الأول</th>
                <th style="min-width:100px;">الأسم الأخير</th>
                <th style="min-width:100px;">العمر</th>
                <th style="min-width:100px;">رقم الهاتف</th>
                <th style="min-width:120px;">العنوان</th>
                <th style="min-width:120px;">البريد الإلكترونى</th>
                <th style="min-width:100px;">الجنسية</th>
                <th style="min-width:150px;">البلد</th>
                <th style="min-width:50px;">الفورم</th>
                <th style="min-width:150px;">المقال</th>
                <th style="min-width:150px;">التعليق</th>
                <th style="min-width:100px;" class="col-3">التاريخ</th>
                <th style="min-width:100px;" class="col-3">الوقت</th>
                <th>ملاحظة</th>
                <th>حذف</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($salesAll as $value) { ?>
            <tr id="<?php echo 'row-' . $value['id'] ?>">
                <td class="text-center">
                    <input type="checkbox" class="checkedList" name="checkedList" value="<?php echo $value['id'] ?>" <?php echo $salesCount == 0 ? 'disabled' : '' ?> />
                 </td>
                <td><?php echo $value['id']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['first_name']; ?></td>
                <td><?php echo $value['last_name']; ?></td>
                <td><?php echo $value['age']; ?></td>
                <td><?php echo $value['number']; ?></td>
                <td><?php echo $value['address']; ?></td>
                <td><?php echo $value['email']; ?></td>
                <td><?php echo $value['nation']; ?></td>
                <td><?php echo $value['country']; ?></td>
                <td class="text-right">
                <?php
                    if($value['form'] !== 'غير مطلوب') {
                        $path = parse_url($value['form'], PHP_URL_PATH);
                        $components = explode('/', $path);   
                        echo '<a href="' . $value['form'] . '">' . end($components). '</a>';
                    } else {
                        echo $value['form'];
                    }
                ?>
                </td>
                <td>
                    <?php
                        if($value['article_title'] !== 'غير مطلوب') {
                            $path = parse_url($value['article_title'], PHP_URL_PATH);
                            $components = explode('/', $path);
                            echo '<a href="' . $value['article_url'] . '">' . end($components). '</a>';
                        }else {
                            echo $value['article_title'];
                        }
                    ?> 
                </td>
                <td><?php echo $value['comment']; ?></td>
                <td><?php echo $value['date']; ?></td>
                <td><?php echo $value['time']; ?></td>
                <td>
                    <button type="button" id="add_comment" data-comment="<?php echo $value['comment'] ?>" data-name="<?php echo $value['name'] ?>" data-id="<?php echo $value['id'] ?>" data-toggle="modal" data-target="#exampleModal" class="btn btn-outline-success btn-sm">ملاحطات</button>
                </td>
                <td>
                    <button ype="button" class="btn btn-outline-danger btn-sm" onclick="deleteOne(<?php echo $value['id'] ?>, 'sales')">حذف</button>
                </td>
            </tr>
            <?php } ?>
        </tbody> 
    </table>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-danger" dir="rtl">
            <h5 class="modal-title text-capitalize" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-right">
              <form name="add_comment" method="post">
                <input type="hidden" class="edit_id" required/>
                <div class="form-group row p-2" dir="rtl">
                    <input type="text" placeholder="ضع ملاحظاتك التى ترغب بإضافتها" class="comment form-control" required/>
                </div>
          </div>
          <div class="update_done"></div>
          <div class="modal-footer" style="direction:ltr">
            <button type="button" class="btn btn-secondary btn-sm close-btn" data-dismiss="modal">غلق</button>
            <button type="submit" class="btn btn-sm btn-success">حفظ</button>
          </div>
          </form>
        </div>
      </div>
    </div>
</div>
<script>
    
            
        
    document.addEventListener('DOMContentLoaded', function(){
        document.title = 'لوحة التحكم - المسجلين';
    
            // Edit Modal
            $(document).on('click','#add_comment', function(e){
                const headerModal = document.querySelector('#exampleModalLabel');
                var id = $(this).data('id');
                var name = $(this).data('name');
                var comment = $(this).data('comment');
                
                headerModal.textContent = 'أضف ملاحظاتك على ' +( name );
                
                $('.edit_id').val(id);
                $('.comment').val(comment);
            })
            
            const formEdit = document.querySelector('form[name=add_comment]');

            if(formEdit) {
                formEdit.addEventListener('submit', (e) => {
                    e.preventDefault();
                    var editId = $('.edit_id').val();
                    var comment = $('.comment').val();
                    
                    const formData = new FormData();
                    formData.append('id', editId);
                    formData.append('comment', comment);
                    
                    fetch('add_comment.php', {
                       method: 'POST',
                        body: formData
                    }).then(res => {
                        return res.json();
                    }).then(res => {
                        $('.btn-close').click()
                        location.reload(true)
                    })
                })
            
            }
    });
</script>

<?php include('footer.php'); ?>


