<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique/core/init.php' ;
include 'includes/head.php';
$email= ((isset($_POST['email']))?sanitize($_POST['email']) :'' );
$email = trim($email);
$password= ((isset($_POST['password']))?sanitize($_POST['password']) : '');
$password =trim($password);
$errors = array();

//
?>
<style>
    body{
        background-image: url("../images/products/bricks-background.jpg") ;
        background-size: cover;
        background-attachment: fixed;
    }
</style>sonkara

<div id="login-form">
    <div>
        <?php
        if ($_POST){
            //form validation
            if(empty($_POST['email']) || empty($_POST['password'])){
                $errors[] = "Email ya da password boş bırakılamaz!";
            }

            //validate email
            if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $errors[]= "Geçerli bir mail giriniz!";
            }
            //min 6 char
            if (strlen($password)<6 ){
                $errors[]= "Şifreniz en az 6 karakter uzunluğunda olmalı!";
            }

            //check if email exist in the database
            $query = $db->query("SELECT *FROM users WHERE email = '$email'");
            $user = mysqli_fetch_assoc($query);
            $userCount = mysqli_num_rows($query);

            if ($userCount < 1){
                $errors[] = "Mail adresi kayıtlı değil";
            }

            if (!password_verify($password,$user['password'])){
                $errors[] = "Parolanız eşleşmiyor!";
            }

            if (!empty($errors)){
                echo display_errors($errors);
            }else{
                $user_id = $user['id'];
                login($user_id);

            }
        }
        ?>
    </div>
    <br><h2 class="text-center">Giriş Yap</h2><br><hr>
    <form action="login.php" method="post" class="login">
        <div class="form-group">
            <label for="email" >Email: </label>
            <input type="email" name="email" id="email" class="form-control" value="<?=$email; ?>"    placeholder="Mailinizi giriniz" required/>
        </div>
        <div class="form-group">
            <label for="email" >Şifre: </label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password; ?>"    placeholder="Şifrenizi giriniz" required/>
        </div>
        <div class="form-group">
            <input type="submit" value="Giriş Yap" class="btn btn-primary" />
        </div>
    </form>
    <p class="text-right"><a href="/Boutique/index.php" alt= "home">Siteye Git</a></p><br>
</div>

<?php
include 'includes/footer.php'; ?>