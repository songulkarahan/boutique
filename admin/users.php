<?php
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
if(!has_permission('admin')){
    permission_error_redirect();
    $url = 'index.php';
}
include 'includes/head.php';
include 'includes/navigation.php';
//echo $_SESSION['sessionUser'];
//echo $_SESSION['success_flash'];

$userQuery =$db->query("SELECT *FROM users ORDER BY full_name") ;

if(isset($_GET['delete'])){
    $delete_id = sanitize($_GET['delete']);
    $db->query("DELETE FROM users WHERE id = '$delete_id'");
    $_SESSION['success_flash'] = "Kullanıcı silindi!";
    header('Location:users.php');
}

if (isset($_GET['add'])){
    $name     = ((isset($_POST['name']))?sanitize($_POST['name']) :'' );
    $email    = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
    $errors= array();

    if ($_POST){
        $emailQuery = $db->query("SELECT *FROM users WHERE email = '$email'");
        $emailCount = mysqli_num_rows($emailQuery);

        if ($emailCount != 0){
            $errors[] = "Bu mail veritabanında kayıtlı";
        }

        $required = array('name', 'email', 'password', 'confirm', 'permissions');
        foreach ($required as $r){
            if (empty($_POST[$r])){
                $errors[] = "Boş alanları doldurunuz. ";
                break;
            }
        }

        if (strlen($password < 6)){
            $errors[] = "Şifreniz en az 6 karakter içermeli!";
        }

        if ($password != $confirm){
            $errors[] = "Şifreniz eşleşmiyor!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Emailiniz geçerli bir email değil!";
        }

       if (!empty($errors)){
           echo display_errors($errors);
       } else{
           //add user to database
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $db->query("INSERT INTO users(full_name, email, password, permissions ) VALUES ('$name', '$email', '$hashed', '$permissions')");
            $_SESSION['success_flash'] = "Kullanıcı eklendi.";
            header('Location:users.php');
       }
    }
?>
<h2 class="text-center">Kullanıcılar</h2><hr><br>
<form action="users.php?add=1" method="post">
    <div class="form-group col-md-6">
        <label for="name">Ad-Soyad: </label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
    </div>
    <div class="form-group col-md-6">
        <label for="email">E-mail: </label>
        <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>
    <div class="form-group col-md-6">
        <label for="password">Şifre: </label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group col-md-6">
        <label for="name">Şifre Doğrula: </label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
    </div>
    <div class="form-group col-md-6">
        <label for="permissions">İzinler: </label>
        <select class="form-control" name="permissions" id="permissions">
            <option value=""<?=(($permissions == '')? ' selected': ''); ?>></option>
            <option value="editor"<?=(($permissions == 'editor')?' selected' :'' );?>>Editor</option>
            <option value="admin"<?=(($permissions == 'admin')? ' selected': '');?>>Admin</option>
        </select>
    </div>
    <div class="form-group col-md-6 text-right">
        <a href="users.php" class="btn btn-default">İptal</a>
        <input type="submit" value="Ekle" class="btn btn-success">
    </div>
</form>

<?php
}else{

?>
<h2 class="text-center">Kullanıcılar</h2><hr><br>
    <a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Kullanıcı Ekle</a><br><br>
<table class="table table-bordered table-striped table-condensed">
    <thead>
    <th>İsim </th>
    <th>Email </th>
    <th>Katılma Tarihi</th>
    <th>Son Giriş</th>
    <th>İzinler</th>
    <th></th>
    </thead>
    <tbody>
    <?php while($user = mysqli_fetch_assoc($userQuery)): ?>
    <tr>
        <td><?=$user['full_name'];?></td>
        <td><?=$user['email']; ?></td>
        <td><?=pretty_date($user['join_date']); ?></td>
        <td><?=pretty_date($user['last_login']); ?></td>
        <td><?=$user['permissions']; ?></td>
        <td>
            <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr>
<?php endwhile; ?>
    </tbody>
</table>

<?php }
include 'includes/footer.php';
?>