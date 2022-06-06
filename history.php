<?php 
    include('/main/header.php');
    include('/main/database.php');   
    // All History From Sales History Table
    $stmt = $con->prepare("SELECT sales_history.*, admins.name as operator FROM sales_history INNER JOIN admins ON sales_history.emp_id = admins.id");
    $stmt->execute();
    $countSales = $stmt->rowCount();
    $Sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
?>
<div class="container text-center mt-4 bg-white" dir="ltr">
    <div class="mt-5 mb-5 p-2">
        <table id="table_id" class="table table-striped table-bordered table-responsive mt-5" dir="rtl" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" class="checkedAll" />  
                    </th>
                    <th>Employee</th>
                    <th>Action</th>
                    <th>Time at</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($Sales) > 0) { ?>
                <?php foreach($Sales as $sale) { ?>
                    <tr id="<?php echo 'row-' . $sale['id'] ?>">
                        <td style="min-width:10px;" class="text-center">
                            <input type="checkbox" class="checkedList" name="checkedList" value="<?php echo $sale['id'] ?>" />
                        </td>
                        <td style="min-width: 10px;"><?php echo $sale['operator']; ?></td>
                        <td style="min-width: 150px;"><?php echo $sale['action']; ?></td>
                        <td style="min-width: 150px;"><?php echo $sale['created_at']; ?></td>
                    </tr>
                <?php } ?>
                <?php } ?>
            </tbody> 
        </table>
    </div>
</div>
<?php include('/main/footer.php'); ?>

<script async>
$(document).ready(function() {
    document.title = 'Dashboard - Track';
});
</script>