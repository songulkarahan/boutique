<?php
$db = mysqli_connect('localhost','root','','boutique');
mysqli_set_charset($db,"utf8");

if(mysqli_connect_errno()){
    echo 'Veritabanına bağlanılamadı:'. mysqli_connect_error();
    die();
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique/config.php';
require_once BASEURL.'helpers/helpers.php';

$cart_id = '';
if (isset($_COOKIE['CART_COOKIE'])){
    $cart_id = sanitize($_COOKIE['CART_COOKIE']);

}
if(isset($_SESSION['sessionUser'])){
    $user_id = $_SESSION['sessionUser'];
    $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn = explode(" ", $user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last'] = $fn[1];

}

if(isset($_SESSION['success_flash'])){
    echo '<br><br><br><br><div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
    unset($_SESSION['success_flash']);
}

if(isset($_SESSION['error_flash'])){
    echo '<br><br><div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
    unset($_SESSION['error_flash']);
}


//session_destroy();
