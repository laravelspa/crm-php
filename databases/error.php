<?php 
ob_start();
session_start();
include('../main/header.php');
?>
    <div class="container mt-5 mb-5 justify-content-center">
        <div>
            <h4 class="bg-danger text-white p-2">DB Name or DB USER or User Password is not correct! Check this again <a href="index.php">back</a></h4>
        </div>
    </div>
   <?php include('../main/footer.php'); ?>
</body>
</html>