<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/boutique/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';


if (isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE product SET deleted = 1, featured= 0 WHERE id= '$id'");

    // burada delete = 1 yapmamızın sebebi sold = 0 yada bir durumuna göre ürünü rapor edebilmek için
    header('Location:products.php');
}

$dbPath = '';
if(isset($_GET['add']) || isset($_GET['edit'])){
$brandQuery = $db->query("SELECT *FROM brand ORDER BY brand");
$parentQuery = $db->query("SELECT *FROM categories WHERE parent = 0 ORDER BY category");
$title           = ((isset($_POST['title']) && !empty($_POST['title']))? sanitize($_POST['title']) : '');//add kısmı için
$brand           = ((isset($_POST['brand']) && !empty($_POST['brand']))? sanitize($_POST['brand']) : '');
$parent          = ((isset($_POST['parent']) && !empty($_POST['parent']))? sanitize($_POST['parent']) : '');
$category        = ((isset($_POST['child']) && !empty($_POST['child']))? sanitize($_POST['child']) : '');
$price           = ((isset($_POST['price']) && !empty($_POST['price']))? sanitize($_POST['price']) : '');
$list_price      = ((isset($_POST['list_price']) && !empty($_POST['list_price']))? sanitize($_POST['list_price']) : '');
$description     = ((isset($_POST['description']) && !empty($_POST['description']))? sanitize($_POST['description']) : '');
$sizes           = ((isset($_POST['sizes']) && !empty($_POST['sizes']))? sanitize($_POST['sizes']) : '');
$sizes           =rtrim($sizes,',');
$saved_image     = '';

    if(isset($_GET['edit'])){
        $edit_id        =(int)$_GET['edit'];
        $productResults = $db->query("SELECT *FROM product WHERE id = '$edit_id'");
        $product        = mysqli_fetch_assoc($productResults);

        if($_GET['delete_image']){
            $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image']; echo $image_url;
            unset($image_url);
            $db->query("UPDATE product SET image = '' WHERE id = '$edit_id'");
            header('Location:products.php?edit='.$edit_id);
        }
        $category       = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']) : $product['categories']);
        //var_dump($product);
        $title          = ((isset($_POST['title']) && !empty($_POST['title']) )? sanitize($_POST['title']) : $product['title'] );//edit kısmı için producttan gelen veriyi alıyor
        $brand          = ((isset($_POST['brand']) && !empty( $_POST['brand']))? sanitize($_POST['brand']) : $product['brand'] );
        $parentQuery2   = $db->query("SELECT *FROM categories WHERE id= '$category'");
        $parentResult   = mysqli_fetch_assoc($parentQuery2);
        $parent         = ((isset($_POST['parent']) && !empty($_POST['parent']) )? sanitize($_POST['parent']) : $parentResult['parent'] );
        $price          = ((isset($_POST['price']) && !empty($_POST['price']))? sanitize($_POST['price']) : $product['price'] );
        $list_price     = ((isset($_POST['list_price']) && !empty($_POST['list_price']))? sanitize($_POST['list_price']) : $product['list_price'] );
        $description    = ((isset($_POST['description']) && !empty($_POST['description']))? sanitize($_POST['description']) : $product['description'] );
        $sizes          = ((isset($_POST['sizes']) && !empty($_POST['sizes']))? sanitize($_POST['sizes']) : $product['sizes'] );
        $sizes          =rtrim($sizes,',');
        $saved_image    =(isset($product['image'])? $product['image'] : '');
        $dbPath         = $saved_image;
       // var_dump($dbPath);
        //var_dump($product);


    }

    if(!empty($sizes)){
        $sizeString  = sanitize($sizes);
        $sizeString  = rtrim($sizeString,',');
        $sizesArray  = explode(',',$sizeString);
        $sArray      = array();
        $qArray      = array();
        foreach ($sizesArray as $sa){
            $s        = explode(':', $sa);
            $sArray[] = $s[0];//size
            $qArray[] = $s[1];//quantity

        }
      //  var_dump($sizeString);
        //var_dump($sizesArray);
    }else {
        $sizesArray =array();
    }

    if ($_POST) {
        //$dbPath = '';
        $errors = array();
        $required = array('title','brand','price','parent','child', 'sizes');
        foreach ($required as $field){
            if($_POST[$field] == ''){
                $errors[] = 'Alanları eksiksiz doldurunuz.';
                break;

            }
        }
        if(!empty($_FILES)){
            $photo       = $_FILES['photo'];
            $target_dir = "images/products/";
            $target_file = $target_dir.basename($photo['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));//döndürdüğü değer uzantısı jpg,png vs.
            $allowed     = array('png', 'jpg', 'jpeg', 'gif');
            $fileSize    = $photo['size'];
            $mime        = explode('/', $photo['type']);//photo['type']= image/jpeg
            //$check_image = getimagesize($photo['tmp_name']);
            //var_dump($_FILES);

            $name        = $photo['name'];//vesika.jpg
            $nameArray   = explode('.',$name);
            $fileName    = $nameArray[0];//vesika
            $fileExt     = $nameArray[1];//jpg şeklinde ayrıştırıyo
            $mimeType    =$mime[0];
            $mimeExt     = $mime[1];//jpeg
            $tmpLoc      = $photo['tmp_name'];
            $uploadName  = $photo['name'];
            $uploadPath  = BASEURL.'images/products/'.$uploadName;
            $dbPath      = '/boutique/images/products/'.$uploadName;


         //   var_dump($dbPath);
           // var_dump($target_file);
            //var_dump($uploadPath);
           // var_dump($uploadName);
           // var_dump($tmpLoc);

             if($mimeType !='image'){
                $errors[] = 'Dosyanız resim olmalı';
            }
            if(!in_array($imageFileType,$allowed)){
                $errors[]= 'Foto uzantısı png,jpg,jpeg ya da gif olmalı';
            }
            if($fileSize>15000000){
                $errors[]='Fotonuzun boyutu 15mb aşıyor';
            }
            if($fileExt != $mimeExt && ($mimeExt== 'jpeg' && $fileExt !='jpg')){
                $errors[]= 'Dosya uzantınız dosya içeriğiyle eşleşmiyor.';

            }

        }
        if(!empty($errors)){
            echo display_errors($errors);

        }else{
            //upload photo and insert into database
            if(!empty($_FILES)) {
               // $tmpLoc      = $photo['tmp_name'];
                move_uploaded_file($tmpLoc, $uploadPath);
            }
/*
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
*/

            $insertSql   = "INSERT INTO product(title, price,list_price,brand,categories,description,image,sizes)
            VALUES ('$title','$price','$list_price','$brand','$category','$description','$dbPath','$sizes')";

            if (isset($_GET['edit'])){
                $insertSql = "UPDATE product SET title = '$title', price = '$price' ,list_price = '$list_price',brand = '$brand',
            categories = '$category', sizes ='$sizes', image = '$dbPath', description = '$description' WHERE id = '$edit_id'";
            }

            $db->query($insertSql);
            //var_dump($insertSql);die();
            header('Location:products.php');

        }
    }



?>
    <h2 class="text-center">Ürün <?=((isset($_GET['edit']))?'Düzenle' : 'Ekle');?> </h2><hr>
    <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id : 'add=1');?>" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for ="title">Ürün Adı*:</label>
            <input type="text" class="form-control" name="title" id="title" value="<?=$title;?>">
        </div>
        <div class="form-group col-md-3">
            <label for="brand">Marka*:</label>
            <select class="form-control" id="brand" name="brand" >
                <option value=""<?=(($brand == '' )?' selected' : '');?>></option>
                <?php while ($b = mysqli_fetch_assoc($brandQuery)):?>
                <option value="<?=$b['id'];?>" <?=(($brand == $b['id'])?' selected':'' );?> > <?=$b['brand'];?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Ana Kategori*:</label>
            <select class="form-control" id="parent" name="parent" >
                <option value="" <?=(($parent == '' )?'selected' : '')?>></option>
                <?php while ($p = mysqli_fetch_assoc($parentQuery)):?>
                <option value="<?=$p['id'];?>" <?=(($parent == $p['id'])?'selected' : '');?> ><?=$p['category'];?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="child">Alt Kategori*:</label>
            <select id="child" class="form-control" name="child" ></select>
        </div>
        <div class="form-group col-md-3">
            <label for="price">İndirimli Fiyatı*:</label>
            <input type="number" class="form-control" name="price" id="price" value="<?=$price;?>">
        </div>
        <div class="form-group col-md-3">
            <label for="list_price">Fiyatı:</label>
            <input type="number" class="form-control" name="list_price" id="list_price" value="<?=$list_price;?>" >
        </div>
        <div class="form-group col-md-3">
            <label>Adet & Beden*: </label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;"> Adet & Beden</button>
        </div>
        <div class="form-group col-md-3">
            <label for="sizes">Adet & Beden Önizleme:</label>
            <input type="text" class="form-control" id="sizes" name="sizes" value="<?=$sizes; ?>" readonly>
        </div>
        <div class="form-group col-md-6">
            <?php if ($saved_image != ''): ?>
            <div class="saved-image"><img src="<?=$saved_image ;?>" alt="saved image"><br><br>
            <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger ">Resmi Sil</a>
            </div>
            <?php else: ?>
            <label for="photo">Ürün Fotoğrafı:</label>
            <input type="file" id="photo" name="photo" class="form-control">
            <?php endif; ?>
        </div>
        <div class="form-group col-md-6">
            <label for="description">Ürün Tanımı:</label>
            <textarea id="description" name="description" class="form-control" rows="6" ><?=$description;?></textarea>
        </div>
        <div class="form-group pull-right">
            <a href="products.php" class="btn btn-default">İptal</a>
            <input type="submit" class="btn btn-success " value="Kaydet">
        </div><div class="clearfix"></div>
    </form>

    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sizesModalLabel">Adet & Beden </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                    <?php for($i =1; $i<=12; $i++): ?>
                        <div class="col-md-4 form-group">
                            <label for="size<?=$i;?>">Beden: </label>
                            <input type="text" class="form-control" id="size<?=$i;?>" name="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))? $sArray[$i-1]: ''  );?>" >
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="qty<?=$i;?>" >Adet: </label>
                            <input type="number" class="form-control" id="qty<?=$i;?>" name="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))? $qArray[$i-1]: ''  );?>" min="0">
                        </div>
                    <?php endfor;?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Kaydet </button>
                </div>
            </div>
        </div>
    </div>


<?php }else{

$sql = "SELECT *FROM product WHERE deleted = 0";
$presults = $db->query($sql);

if(isset($_GET['featured'])){
    $id          = (int)$_GET['id'];
    $featured    = (int)$_GET['featured'];
    $featuredSql = "UPDATE product SET featured ='$featured 'WHERE id = '$id'";
    $db->query($featuredSql);
    header('Location:products.php');

}

?>

<h2 class="text-center">Ürünler</h2><hr><br>
<div class="clearfix pull-right">
<a href="products.php?add=1" class="btn btn-success " id="add-product-btn">Yeni Ürün Ekle</a>
</div><br><br>
<table class="table table-bordered table-striped table-condensed">
    <thead>
    <th>Ürün</th>
    <th>Fiyatı</th>
    <th>Kategori</th>
    <th>Ürünü Durumu</th>
    <th>Satıldı</th>
    <th>Eylem</th>
    </thead>
    <tbody>
    <?php while($product = mysqli_fetch_assoc($presults)):
        $childID = $product['categories'];
        $childSql = "SELECT *FROM categories WHERE  id = '$childID'";
        $childResult = $db->query($childSql);
        $child = mysqli_fetch_assoc($childResult);
        $parentID = $child['parent'];
        $parentSql = "SELECT *FROM categories WHERE id = '$parentID'";
        $parentResult = $db->query($parentSql);
        $parent = mysqli_fetch_assoc($parentResult);
        $category = $parent['category'].'||'.$child['category'];
        ?>
    <tr>
        <td><?=$product['title'];?></td>
        <td><?=money($product['price']) ;?></td>
        <td><?=$category;?></td>
        <td><a href="products.php?featured=<?=(($product['featured'] == 0) ? '1' : '0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
                <span class="glyphicon glyphicon-<?=(($product['featured']== 1)? 'minus': 'plus') ?>"></span>
            </a>&nbsp<?=(($product['featured'])? 'Ürün Siteye eklendi': '' );?>

        </td>
        <td>0</td>
        <td>
            <a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>



<?php } include 'includes/footer.php'; ?>
<script>
    jQuery('document').ready(function () {
        get_child_options('<?=$category;?>');
g    });
</script>
