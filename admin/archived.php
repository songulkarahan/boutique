<?php
/**
 * Created by PhpStorm.
 * User: songu
 * Date: 3.06.2019
 * Time: 04:34
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
$sql= "SELECT *FROM product WHERE deleted = 1" ;
$archived = $db->query($sql);

if (isset($_GET['refresh'])){
    $id= (int)$_GET['id'];
    $archived = "UPDATE product SET deleted = 0 WHERE id = '$id'";
    $db->query($archived);
    header('Location:archived.php');
}

if (isset($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $db->query("DELETE FROM product WHERE id= '$delete_id'");
    header('Location: archived.php');
}

?>

<h2 class="text-center">Arşiv</h2><hr><br>

<table class="table table-bordered table-striped table-condensed">
    <thead>
    <th>Ürün</th>
    <th>Fiyatı</th>
    <th>Kategori</th>
    <th>Satıldı</th>
    <th>Eylem</th>
    </thead>
    <tbody>
<?php while ($product = mysqli_fetch_assoc($archived)):
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
        <td><?=$product['price']?></td>
        <td><?=$category;?></td>
        <td><?=0?></td>
        <td>
            <a href="archived.php?refresh=<?=(($product['deleted'] == 1) ? '0' : '1');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
            <a href="archived.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a>

        </td>
    </tr>
<?php endwhile; ?>
    </tbody>
</table>


<?php include 'includes/footer.php';?>