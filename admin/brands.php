<?php
require_once '../core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
$sql = "SELECT *FROM brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();
//Delete Brand
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id =(int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM brand WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location:brands.php');
    
}
//Edit Brand
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT *FROM brand WHERE id = '$edit_id' ";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);
  //echo var_dump($eBrand);
}

// If add form is submitted
if(isset($_POST['add_submit'])){
    //check if brand is blank
    $brand = sanitize($_POST['brand']);
    if($_POST['brand'] == ''){
        $errors[] .= "Marka ismi girmelisiniz! ";
    }

    //veritabanında mevcutsa olacak durumlar
    $sql = "SELECT *FROM brand WHERE brand ='$brand'";
    if (isset($_GET['edit'])){
        $sql = "SELECT *FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
        //marka varsa ve id si edit id değilse yani eklendiyse hata vermesin diye kullanılan sorgu
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if($count >0){
        $errors[].= $brand." zaten var.Lütfen başka bir marka ekleyiniz.";
    }

    //hataları göster
    if(!empty($errors)){
        echo display_errors($errors);
    }else{
        //hata yoksa ekle
        $sql = "INSERT INTO brand(brand) VALUES ('$brand')";
        if(isset($_GET['edit'])){
            $sql = "UPDATE brand SET brand ='$brand' WHERE id ='$edit_id'";
        }
        $db->query($sql);
        header('Location: brands.php');
    }
}
?>
<div class="panel">
    <h2 class="text-center">Markalar</h2><hr>
    <!-- Brand Form-->

        <form class="form-inline text-center" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id :''); ?>" method="post" >
            <div class="form-group">
                <?php
                $brand_value = '';
                if(isset($_GET['edit'])){
                    $brand_value = $eBrand['brand'];
                }else{
                    if(isset($_POST['brand'])){
                        $brand_value = sanitize($_POST['brand']); }
                }
                ?>
                <label for="brand">Marka <?=((isset($_GET['edit']))?'Düzenle' : 'Ekle' );?></label>
                <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value;?>">
                <?php if(isset($_GET['edit'])):?>
                    <a href="brands.php" class="btn btn-danger btn-sm">İptal</a>
                <?php endif; ?>
                <input type="submit" name="add_submit" value="<?=((isset($_GET['edit'])) ? 'Düzenle': 'Ekle') ?>" class="btn btn-sm btn-success">
            </div><br><br>
        </form>
</div>

<div class="panel-content">

    <table class="table table-bordered table-striped table-auto table-condensed">
        <thead>
        <th>Marka Adı</th><th style="width:15%" class="text-center">Eylem</th>
        </thead>
        <tbody>
        <?php while( $brand = mysqli_fetch_assoc($results)):?>
        <tr>
            <td><?= $brand['brand'];?></td>
            <td class="text-center">
                <a href="brands.php?edit=<?= $brand['id'];?>" class="btn btn-xs btn-default  "><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="brands.php?delete=<?= $brand['id'];?>" class="btn btn-xs btn-default "><span class="glyphicon glyphicon-trash"></span></a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include 'includes/footer.php';
?>
