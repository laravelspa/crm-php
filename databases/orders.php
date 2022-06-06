<?php 
ob_start();
session_start();
include('../main/database.php');

if(isset($_GET['dbname'], $_GET['dbuser'], $_GET['dbpassword'])) {
    $dbname = $_GET['dbname'];
    $dbuser = $_GET['dbuser'];
    $dbpassword = $_GET['dbpassword'];
    $dsn1 = "mysql:host=localhost;dbname=$dbname";
    
    try {
        $con1 = new PDO($dsn1, $dbuser, $dbpassword);
        // echo 'Good connection';
    }
    catch(PDOException $e) {
        // echo 'failed ' . $e->getMassage(); 
    }
    
    $con1->query("SET NAMES utf8");
    $con1->query("SET CHARACTER SET utf8");
} else {
    header('location: index.php');
}
include('../main/header.php');
?>
    <div class="container mt-5 mb-5">
        <div class="d-flex flex-wrap justify-content-between">
        <?php
            $stmt = $con1->prepare('SHOW TABLES');
            $stmt->execute();
            $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($tables as $table) {
                echo '<div class="col-lg-5 col-sm-12 bg-white p-2 mb-1">'.$table["Tables_in_$dbname"].'</div>';
            }
        ?>
        </div>
         <!-- Modal Wall Off Or On -->
        <div class="modal fade" id="wallModal" tabindex="-1" role="dialog" aria-labelledby="wallModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header text-danger" >
                <h5 class="modal-title text-capitalize" id="addEmpToLeadModalLabel">Wall visibility</h5><br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:-1rem;">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <div class="form-group col-12">
                        <div class="form-group p-2">
                            <label for="wallUpdate">Wall</label>
                            <select id="wallUpdate" name="wall" class="wallUpdate form-control" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>    
                </div>
                <div class="modal-footer" style="direction:ltr">
                    <div class="form-group col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-default btn-sm close-btn" data-dismiss="modal">Close</button>
                        <button id="submit" type="button" class="btn btn-sm btn-success" onclick="updateAll('update_wall.php', 'admins','Once you delete this user all orders became null', 'Select any user to edit it!','Your user is save!')">Save</button>
                    </div>
                </div>
            </div>
          </div>
        </div>
   </div>
   <?php include('../main/footer.php'); ?>
   <script src="/js/databases.js"></script>
</body>
</html>