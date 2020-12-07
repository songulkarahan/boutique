<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique/core/init.php' ;
if (!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
$hashed = $user_data['password'];
$old_password= ((isset($_POST['old_password']))?sanitize($_POST['old_password']) : '');
$old_password =trim($old_password);
$password= ((isset($_POST['password']))?sanitize($_POST['password']) : '');
$password =trim($password);
$confirm= ((isset($_POST['confirm']))?sanitize($_POST['confirm']) : '');
$confirm =trim($confirm);
$new_hashed =  password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();


?>
    <style>
        body{
            background-image: url("../images/products/bricks-background.jpg") ;
            background-size: cover;
            background-attachment: fixed;
        }
    </style>

    <div id="login-form">
        <div>
            <?php
            if ($_POST){
                //form validation
                if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
                    $errors[] = "Boş alanları doldurunuz!";
                }

                //min 6 char
                if (strlen($password)<6 ){
                    $errors[]= "Şifreniz en az 6 karakter uzunluğunda olmalı!";
                }
                //if new password matches confirm
                if ($password != $confirm){
                    $errors[] = 'Şifreniz uyuşmuyor. Lütfen yeniden deneyiniz.';
                }

                if (!password_verify($old_password,$hashed)){
                    $errors[] = "Eski parolanız yanlış!";
                }

                if (!empty($errors)){
                    echo display_errors($errors);
                }else{
                    //change password
                    $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
                    $_SESSION['success_flash'] = 'Şifreniz Güncellendi!';
                    header('Location: index.php');
                }
            }
            ?>
        </div>
        <br><h2 class="text-center">Şifre Değiştir</h2><br><hr>
        <form action="change_password.php" method="post" >
            <div class="form-group">
                <label for="old_password" >Şifre: </label>
                <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password; ?>"   placeholder="Eski şifrenizi giriniz" required/>
            </div>
            <div class="form-group">
                <label for="password" >Yeni Şifre: </label>
                <input type="password" name="password" id="password" class="form-control" value="<?=$password; ?>"   placeholder="Yeni şifre giriniz" required/>
            </div>
            <div class="form-group">
                <label for="confirm" >Tekrar Yeni Şifre:  </label>
                <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm; ?>"   placeholder="Yeniden giriniz" required/>
            </div>
            <div class="form-group">
                <a href="index.php" class="btn btn-default">İptal</a>
                <input type="submit" value="Güncelle" class="btn btn-primary" />
            </div>
        </form>
        <p class="text-right"><a href="/Boutique/index.php" alt= "home">Siteye Git</a></p><br>
    </div>

<?php
include 'includes/footer.php'; ?>