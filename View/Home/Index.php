<ul class="categories" >
<?php
// $select_keywords_query = "SELECT *
// FROM `Keywords`
// LIMIT 100";

// $result = $conn->query($select_keywords_query);

while (0) {
?>
<li><a class="btn profile-edit-btn" href="index.php?q=<?php echo $item['Title'] ?>"><?php echo $item['Title'] ?></a></li>
<?php
}
?>
</ul>



<div class="podcast">
<?php
// $select_podcast_query = "SELECT *
// FROM `Podcasts`
// ORDER BY `Id` DESC
// LIMIT 1";
// $result = $conn->query($select_podcast_query);
while (0) {
?>
<h1><?php echo $item['Title'] ?></h1>
<h2><?php echo $item['PublishDate'] ?></h2>
<audio controls preload="metadata" >
    <!-- <source src="myfile.ogg" type="audio/ogg"> -->
    <source src="<?php echo $item['FileUrl'] ?>" type="audio/mpeg">
    مرورگر شما از پخش کننده‌ی صدا پشتیبانی نمی‌کند/
    <a href="<?php echo $item['FileUrl'] ?>">
    دانلود
    </a>
</audio>
<?php
}
?>
</div>




<div class="gallery">

    <?php

    foreach ($Data['Models']['Posts'] as $item) {
    ?>
            <div class="gallery-item" tabindex="0" >
            
            
            <div class="gallery-item card">
                            <div class="gallery-item-front ">
                                    <img src="image-generator.php?id=<?php echo $item['Id'] ?>" class="gallery-image" alt="<?php echo $item['Title'] ?>">
                            </div>
                                <div class="gallery-item-back">
                                        <div class="gallery-item-back-content ">
                                                <h2 class="titles"><?php echo $item['Title'] ?></h2>
                                                <span class="Paragraf"><?php
                                                $AllowedCharsLimit = 30;
                                                if(strlen($item['Abstract']) > $AllowedCharsLimit)
                                                    echo substr($item['Abstract'], 0, $AllowedCharsLimit)."...";
                                                else
                                                    echo $item['Abstract'];
                                                    ?></span>

                                                <a href="view.php?id=<?php echo $item['Id'] ?>" class="buttons"><span>بیشتر بدانید</span></a>
                                        </div>
                                    </div>
                </div>

            </div>
    
    <?php
    }
    ?>

</div>
<!-- End of gallery -->