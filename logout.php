<?php
include('main/database.php');
session_start();

if (session_destroy()) {
    header("Location:login.php");
}

exit;
