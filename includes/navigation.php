<?php
$sql = "SELECT *FROM categories WHERE parent = 0";
$pquery = $db->query($sql);

?>
<div class="menu">

    <div class = "logo col-sm-3">
        <a href="index.php"><h1>SK'S BOUTIQUE </h1></a>
    </div>
    <nav>
        <ul>
                <?php while($parent = mysqli_fetch_assoc($pquery)):  ?>

                    <?php $parent_id = $parent['id'];
                    $sql2 = "SELECT *FROM categories WHERE parent = '$parent_id'";
                    $cquery = $db->query($sql2);
                    ?>
                <li>
                    <a href="#"><?php echo $parent['category'];?> <span class="caret"></span></a>
                    <ul>
                        <?php while($child = mysqli_fetch_assoc($cquery)): ?>
                        <li><a href="category.php?cat=<?=$child['id'];?>"><?php echo $child['category'];?></a></li>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <?php endwhile; ?>
        </ul>
    </nav>
</div>