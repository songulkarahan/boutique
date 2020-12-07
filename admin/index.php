<?php
require_once '../core/init.php';
if(!is_logged_in()){
    header('Location:login.php');
}

include 'includes/head.php';
include 'includes/navigation.php';
//echo $_SESSION['sessionUser'];
//echo $_SESSION['success_flash'];
//session_destroy();
?>
Admin Panel
<?php
include 'includes/footer.php';
?>
