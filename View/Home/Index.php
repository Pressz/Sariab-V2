<!-- Roadmaps -->
<section>
    <div class="roads slideshow-container">
    <?php
    for ($i = 0 ; $i < count($Data['Models']['Roads']) ; $i ++ ) {
    $item = $Data['Models']['Roads'][$i]
    ?>
    <!-- Full-width images with number and caption text -->
    <div class="mySlides fade">
        <a href="<?php echo _Root . 'Home/Roadmap/' . $item['Id']?>">
            <div class="numbertext"><?php echo $i + 1 ?> از <?php echo count($Data['Models']['Roads']) ?></div>
            <img src="<?php echo $item['ImageUrl'] ?>" style="width:100%">
            <h1 class="text">
                <?php echo $item['Title']?>
            </h1>
        </a>
    </div>
    <?php
    }
    ?>
    <!-- Next and previous buttons -->
    <span class="prev" onclick="plusSlides(-1)">&#10094;</span>
    <span class="next" onclick="plusSlides(1)">&#10095;</span>
    </div>
    <br>

    <!-- The dots/circles -->
    <div style="text-align:center">
    <?php for ($i = 0 ; $i < count($Data['Models']['Roads']) ; $i ++ ) { ?>
    <span class="dot" onclick="currentSlide(<?php echo $i + 1 ?>)"></span>
    <?php } ?>
    </div>

    <!-- JavaScript -->
    <script>
    var slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
    showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
    showSlides(slideIndex = n);
    }

    function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    }
    </script>
</section>
<!-- End of Roadmaps -->

<!-- Podcast -->
<section>
    <?php
    foreach ($Data['Models']['Podcasts'] as $item) {
    ?>
    <h1 class="h1" ><b>بشنوید:</b> <?php echo $item['Title'] ?></h1>
    <div class="background-dark color-white podcast border-radius">
        <audio controls preload="metadata" >
            <!-- <source src="myfile.ogg" type="audio/ogg"> -->
            <source src="<?php echo $item['FileUrl'] ?>" type="audio/mpeg">
            مرورگر شما از پخش کننده‌ی صدا پشتیبانی نمی‌کند
            <a href="<?php echo $item['FileUrl'] ?>">
            دانلود
            </a>
        </audio>
        <strong><?php echo $item['PublishDate'] ?></strong>
        <span><a href="https://vrgl.ir/DwyG8" target="_blank">دنبال کردن پادکست در برنامه‌های مورد علاقه‌ی شما! (کلیک کنید)</a></span>
    </div>
    <?php
    }
    ?>
</section>
<!-- End of Podcast -->

<!-- Hub -->
<section>
    <span class="h1" >تازه‌ها؛ صفحه <?php echo $Data['PostsPage'] ?></span>
    <div class="hub-controls">
        <a href="<?php echo _Root . 'Home/Index/*/' . ($Data['PostsPage'] - 1) ?>#Hub">صفحه قبل</a>
        | <a href="<?php echo _Root . 'Home/Index/*/' . ($Data['PostsPage'] + 1) ?>#Hub">صفحه بعد</a>
        | <a href="<?php echo _Root ?>Home/RSS">خوراک</a>
        | <a class="profile-edit-btn background-dark color-white border-radius" href="<?php echo _Root ?>Home/Submit">ارسال مطلب</a>
    </div>
    <?php
    if (count($Data['Models']['Posts']) == 0) {
    ?>
    <strong>هیچ پستی یافت نشد</strong>
    <?php
    }
    else
    {
    ?>
    <ol class="hub" id="Hub">
    <?php
        foreach ($Data['Models']['Posts'] as $item)
        {
    ?>
        <li>
            <a href="<?php echo _Root . 'Home/Redirect/' . $item['Id'] ?>" class="color-dark" target="_blank">
                <h2><?php echo $item['Title'] ?></h2>
            </a>
            <p>
                <?php echo $item['IsExternalWriter'] ? '<i class="fas fa-exclamation-circle tooltip"><span class="tooltiptext"> نویسنده غیر رسمی</span></i>' : $item['Publisher'] ?>
                | <?php echo time_elapsed_string($item['Submit']) ?>
                | <a class="color-dark" href="<?php echo _Root . 'Home/Feedback/Post/' . $item['Id'] ?>">گزارش لینک</a>
                | <?php echo parse_url($item['Canonical'], PHP_URL_HOST) ?>
                | <a class="color-dark" href="<?php echo _Root . 'Home/View/' . $item['Id'] ?>">خلاصه</a>
                <?php foreach (explode(',', $item['Meta']) as $tag) { ?>
                | <a class="color-dark background-gold border-radius" href="<?php echo _Root . 'Home/Index/' . $tag ?>"><?php echo $tag ?></a>
                <?php } ?>
                | <?php
                    switch($item['Language'])
                    {
                        case "fa":
                        case "fa-ir":
                            echo "🇮🇷";
                            break;
                        case "en":
                        case "en-us":
                            echo "🇺🇸";
                            break;
                    }
                ?>
            </p>
            <?php /*
            <p><?php
                $AllowedCharsLimit = 400;
                if(strlen(strip_tags($item['Abstract'])) > $AllowedCharsLimit)
                    echo substr(strip_tags($item['Abstract']), 0, $AllowedCharsLimit)."...";
                else
                    echo strip_tags($item['Abstract']);
            ?></p>
            */ ?>
        </li>
    <?php
        }
    ?>
    </ol>
    <?php
    }
    ?>
</section>
<!-- End of Hub -->

<!-- Categories -->
<section>
    <span class="h1" ><b>کلیدواژه</b>‌ها، ایده‌هایی برای جستجو</span>
    <ul class="background-white categories overflow" >
        <?php
        foreach ($Data['Models']['Keywords'] as $item) {
        ?>
        <li class="background-dark color-white border-radius"><a class="btn profile-edit-btn" href="<?php echo _Root . 'Home/Index/' . $item['Title'] . '#Posts'?>"><?php echo $item['Title'] ?></a></li>
        <?php
        }
        ?>
    </ul>
</section>
<!-- End of Categories -->