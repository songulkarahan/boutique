<?php
require_once 'core/init.php';
include  'includes/head.php';
include  'includes/navigation.php';
include  'includes/cat-headerfull.php';
include  'includes/leftsidebar.php';

if (isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
}else{
    $cat_id = '';
}

$sql = "SELECT *FROM product WHERE categories = '$cat_id'";
$productCategory = $db->query($sql);
$category = get_category($cat_id);

?>


<!-- Main Content-->
<div class="col-md-8">
    <div class="row text-center">
        <?php while($product = mysqli_fetch_assoc($productCategory)): ?>
            <h2 class="product-title"><?=$category['parent'].' '.$category['child'].' Listesi'; ?></h2><br><br>
            <div class="col-sm-3 image-box">
                <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb"/>
                <h4><?= $product['title']; ?></h4>
                <p class="list-price text-danger">Fiyat <s>$<?= $product['list_price']; ?></s></p>
                <p class="price" >İndirimli Fiyat: $<?= $product['price']; ?></p>
                <button type="button" class="btn btn-sm btn-detail" onclick="detailsmodal(<?= $product['id']; ?>)" >Detaylar</button>
            </div>
        <?php endwhile; ?>
    </div>
</div>


<?php
include  'includes/rightsidebar.php';
include  'includes/footer.php';
?>

