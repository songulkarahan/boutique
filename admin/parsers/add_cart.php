<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique/core/init.php';
$product_id = sanitize($_POST['product_id']);
$size = sanitize($_POST['size']);
$quantity = sanitize($_POST['quantity']);
$available = sanitize($_POST['available']);
$item = array();
$item [] = array(
    'id'         => $product_id,
    'size'       => $size,
    'quantity'   => $quantity
);

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST'] : false;
$query = $db->query("SELECT *FROM product WHERE id = '{$product_id}'");
$product = mysqli_fetch_assoc($query);

$_SESSION['success_flash'] = $product['title'].'sepetinize eklendi ' ;



//eğer sepette cookie varsa
//Oğor sopotto cookoo vorso
//İhi ihi ihi
//ahahahaha
//Author by Furkan

if ($cart_id != ''){
    $cartQuery = $db->query("SELECT *FROM cart WHERE id = '{$cart_id}'");
    $cart = mysqli_fetch_assoc($cartQuery);
    $previous_items = json_decode($cart['items'],true);
    $item_match = 0;
    $new_items = array();
    foreach($previous_items as $pitem){
        if ($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size'] ){
            $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
            if ($pitem['quantity'] > $available){
                $pitem['qunatity'] = $available;
            }
            $item_match = 1;
        }
        $new_items[] = $pitem;
    }
    if ($item_match !=1){
        $new_items = array_merge($item, $previous_items);
    }
    $items_json = json_encode($new_items);
    $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
    $db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
    setcookie(CART_COOKIE,'', 1,"/", $domain, false);
    setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
}else{
    //sepeti database ekle cookie ayarla

    $items_json = json_encode($item);
    $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
    $db->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}', '{$cart_expire}')");
    $cart_id = $db->insert_id;// son insert edilen id yi alır.
    var_dump($cart_id);
   // $cart_id = $db->lastInsertId();// son insert edilen id yi alır.
   // var_dump($cart_id);
    setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
}

?>