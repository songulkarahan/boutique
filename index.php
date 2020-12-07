<?php
require_once 'core/init.php';
include  'includes/head.php';
include  'includes/navigation.php';
include  'includes/headerfull.php';
include  'includes/leftsidebar.php';
 $sql = "SELECT *FROM product WHERE featured = 1";
 $featured = $db->query($sql);
?>


<!-- Main Content-->
     <div class="col-md-8">
         <div class="row text-center">
             <h2 class="product-title ">İndirim Fırsatları</h2><br><br>
             <?php while($product = mysqli_fetch_assoc($featured)): ?>
             <div class="col-sm-3 image-box">
                 <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb"/>
                 <h4><?= $product['title']; ?></h4>
                 <p class="list-price text-danger">Fiyat <s>$<?= $product['list_price']; ?></s></p>
                 <p class="price" >İndirimli Fiyat: $<?= $product['price']; ?></p>
                 <button type="button" class="btn btn-sm btn-detail" onclick="detailsmodal(<?=$product['id']; ?>)" >Detaylar</button>
             </div>
             <?php endwhile; ?>
         </div>
     </div>


<?php
    include  'includes/rightsidebar.php';
    include  'includes/footer.php';
?>

