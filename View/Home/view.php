<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $Data['Title'] ?></title>

    <link rel="stylesheet" href="<?php echo _Root ?>static/css/sariab.css">
    <link rel="stylesheet" href="<?php echo _Root ?>static/css/layout.css">
    <link rel="stylesheet" href="<?php echo _Root ?>static/css/view.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.css">

    <!-- Primary Meta Tags -->
    <meta name="title" content="<?php echo strip_tags($Data['Model']['Title']) ?>">
    <meta name="description" content="<?php echo strip_tags($Data['Model']['Abstract']) ?>">
    <meta name="keywords" content="<?php echo strip_tags($Data['Model']['Meta']) ?>" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo _Root ?>">
    <meta property="og:title" content="<?php echo strip_tags($Data['Model']['Title']) ?>">
    <meta property="og:description" content="<?php echo strip_tags($Data['Model']['Abstract']) ?>">
    <meta property="og:image" content="http://sariab.ir/image-generator.php?id=<?php echo $Data['Model']['Id'] ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo _Root ?>">
    <meta property="twitter:title" content="<?php echo strip_tags($Data['Model']['Title']) ?>">
    <meta property="twitter:description" content="<?php echo strip_tags($Data['Model']['Abstract']) ?>">
    <meta property="twitter:image" content="http://sariab.ir/image-generator.php?id=<?php echo $Data['Model']['Id'] ?>">

</head>
<body>
<!-- View Page Post -->
<main>
<!-- Image Post View -->
<div class="container background-white blog-post ">
   <div>
        <a class="border-rounded background-white color-dark blog-post__close "
        <?php if (isset($Data['RoadId'])) { ?>
        href="<?php echo _Root ?>Home/Roadmap/<?php echo $Data['RoadId'] ?>#post_<?php echo $Data['Model']['Id'] ?>"
        <?php } else { ?>
        href="<?php echo _Root ?>Home/Index#Hub"
        <?php } ?>
        >✖
        </a>
        <?php if ($Data['Model']['IsExternalWriter']) { ?>
        محتوای غیر رسمی
        <i class="fas fa-exclamation-circle tooltip"><span class="tooltiptext">اجازه رسمی پیوند به این پست توسط ساریاب دریافت نشده است؛ و یا نویسنده عضو رسمی بلاگر های آزاد ساریاب نیست.</span></i>
        <?php } ?>
        <div class="gallery-item card blog-post__img background-dark color-white"></div>
    </div> 
    <!-- End Image Post View  -->
    <!-- Post Description -->
    <div class="blog-post__info blog-post__date">
        <h1 class="blog-post__title"><?php echo $Data['Model']['Title'] ?></h1>
        <p class="blog-post__text"><?php echo $Data['Model']['Abstract'] ?></p>
        <span><?php echo $Data['Model']['Publisher'] ?></span>
          <span><?php echo $Data['Model']['Submit'] ?></span>

        <?php if (isset($Data['Navigation']['Previous'])) { ?>
        <a class="border-radius background-gold color-dark button-medium"
        href="<?php echo _Root . 'Home/View/' .  $Data['Navigation']['Previous'] . '/' . $Data['RoadId'] ?>">
        قبلی
        </a>
        <?php } ?>


        <a class="border-radius background-gold color-dark button-medium" target="_blank"
        href="<?php echo _Root . 'Home/Redirect/' .  $Data['Model']['Id'] ?>">
        مطالعه پست
        </a>

        <?php if (isset($Data['Navigation']['Next'])) { ?>
        <a class="border-radius background-gold color-dark button-medium"
        href="<?php echo _Root . 'Home/View/' .  $Data['Navigation']['Next'] . '/' . $Data['RoadId'] ?>">
        بعدی
        </a>
        <?php } ?>
    </div>
    <!-- End Post Description -->
    <!-- End View Page Post -->
    <script>
    // Your application has indicated there's an error
    window.setTimeout(function(){

        // Move to a new location or you can do something else
        window.location.href = "<?php echo _Root . 'Home/Redirect/' .  $Data['Model']['Id'] ?>";

    }, 60000);
    </script>

</div>
<!-- End of container -->

</main>
</body>
</html>