<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="container">
        <a href="/boutique/admin/index.php" class="navbar-brand">SK's Boutique Admin</a>
        <ul class="nav navbar-nav">
            <!-- Menu Items-->
            <li><a href="brands.php">Markalar</a></li>
            <li><a href="categories.php">Kategoriler</a></li>
            <li><a href="products.php">Ürünler</a></li>
            <li><a href="archived.php">Arşiv</a></li>
            <?php if (has_permission('admin')):?>
            <li><a href="users.php">Kullanıcılar</a></li>
            <?php endif; ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Merhaba <?=$user_data['first'];?>!<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="change_password.php">Parola Değiştir</a></li>
                    <li><a href="logout.php">Çıkış</a></li>
                </ul>
            </li>
            <!--    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'];?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                            <li><a href="#"></a></li>
                    </ul>
                </li>-->
        </ul>
    </div>
</nav>
<br>
<br>
<br>
<br>