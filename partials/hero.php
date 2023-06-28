<?php

$hero = $blocks['hero'];
?>
<section class="hero">
    <div class="container">
        <div class="content">
            <h1 class="title"><?php echo $hero['title']; ?></h1>
            <p class="desc"><?php echo $hero['desc'] ?></p>
            <!-- <?php if (isset($hero['image']['src'])) :
                $alt = $hero['image']['alt'] ?? '';
                $src = $hero['image']['src'];
                $link = $hero['image']['link'] ?? false;
                if ($link) echo "<a href='$link' target='_blank'>";
            ?>
                <img src="<?php echo $src; ?>" alt="<?php echo $alt; ?>">
            <?php
                if ($link) echo "</a>";
            endif; ?> -->
        </div>
    </div>
</section>