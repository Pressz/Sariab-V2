<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $Data['Title'] ?></title>

    <link rel="manifest" href="manifest.json">
    
    <link rel="stylesheet" href="<?php echo _Root ?>static/css/sariab.css">
    <link rel="stylesheet" href="<?php echo _Root ?>static/css/layout.css">
    <link rel="stylesheet" href="<?php echo _Root ?>static/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.css">
    
    <script type="text/javascript">
        (function(){
        var now = new Date();
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.async = true;
        var script_address = 'https://cdn.yektanet.com/js/sariab.ir/native-sariab.ir-18316.js';
        script.src = script_address + '?v=' + now.getFullYear().toString() + '0' + now.getMonth() + '0' + now.getDate() + '0' + now.getHours();
        head.appendChild(script);
        })();
	</script>
    
</head>
<body>
<header class="container background-white color-dark">
    <div class="profile">
        <div class="profile-image">
        <a href="http://sariab.ir">
		<img class=" background-dark color-white border-rounded" width="150" src="<?php echo _Root ?>logo/Icon.svg" alt="Sariab Logo">
        </a>
        </div>
        <div class="profile-user-settings">
            <strong class="profile-user-name box-with-text background-dark">sariabbloggers</strong>
            <span class="profile-real-name">
                <strong>ساریاب</strong>
                گردآوری و اشتراک دانش و تجربه؛ و ایجاد انگیزه.
            </span>
        </div>
        <div class="searchbox-container">
            <form class="searchbox" method="GET" action="<?php echo _Root . 'Home/Index' ?>" >
                <input class="search-txt color-white border-radius background-gold" type="text" name="q" placeholder="جستجو...">
                <button type="submit" class="border-rounded search-btn background-dark color-white" >
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>
        <div class="contribute-links">
            <a class="profile-edit-btn background-dark color-white border-radius" href="<?php echo _Root ?>Home/Positions">عضویت ساریاب</a>
            <a class="profile-edit-btn background-gold color-dark border-radius shine" href="https://zarinp.al/@tayyebi">حمایت از ساریاب</a>
        </div>
        <div class="social-button">
            <a href="https://instagram.com/sariabbloggers" class="border-rounded icon insta background-dark color-white">
                <span class="background-dark color-white border-radius">اینستاگرام</span>
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.linkedin.com/company/sariab/" class="border-rounded icon insta background-dark color-white">
                <span class="background-dark color-white border-radius">لینکداین</span>
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://t.me/sariabbloggers"class="border-rounded icon tel background-dark color-white">
                <span class="background-dark color-white border-radius">تلگرام</span> 
                <i class="fab fa-telegram"></i>
            </a>
            <a href="https://github.com/Pressz/Sariab-V2"class="border-rounded icon git background-dark color-white">
                <span class="background-dark color-white border-radius">متن باز</span>
                <i class="fab fa-github"></i>
            </a>
            <a href="https://vrgl.ir/D2lo5"class="border-rounded icon faq background-dark color-white">
                <span class="background-dark color-white border-radius">سوالات پرتکرار (FAQ)</span>
                <i class="fa fa-question"></i>
            </a>
            <a href="<?php echo _Root ?>Home/ThankYou"class="border-rounded icon heart background-dark color-white">
                <span class="background-dark color-white border-radius">قدردانی</span>
                <i class="fa fa-heart"></i>
            </a>
            <a href="<?php echo _Root ?>Home/Blog"class="border-rounded icon blog background-dark color-white">
                <span class="background-dark color-white border-radius">بلاگ</span>
                <i class="fa fa-blog"></i>
            </a>
            <a href="https://virgool.io/sariab"class="border-rounded icon mag background-dark color-white">
                <span class="background-dark color-white border-radius">مجله</span>
                <i class="fa fa-book"></i>
            </a>
        </div>
    </div>
    <!-- End of profile section -->
</header>

<div id="pos-article-text-25423"></div>

<main class="container background-white color-dark">


    <?php if (isset($Data['Message']) ) { ?>
    <!-- Message -->
    <div id="snackbar" class="toast show"><?php echo $Data['Message'] ?></div>
    <!-- Message End -->
    <?php } ?>

    <!--VIEW_CONTENT-->
        
    <!-- Feedback -->
    <a  onclick="showFeedback()" href="#Feedback" class="shine float background-dark color-white"><i class="fa fa-comments font-xxlarge my-float"></i></a>
    <form
        class="feedback submit-form"
        id="Feedback" method="post"
        action="<?php echo _Root . 'Home/Feedback' ?>">
        <span class="h1">بازخورد</span>
        <p>
            همچنین ما خوشحال می‌شیم اگه بتوانیم به شما کمکی بکنیم. راحت باشید :)
        </p>

        <div class="form-group radio-group">
            <input type="radio" id="happy" name="Status" value="happy" checked>
            <label for="happy">😀</label>
            <input type="radio" id="sad" name="Status" value="sad">
            <label for="sad">☹️</label>
        </div>
        <div class="form-group"><label for="Contact">آیا لازم است با شما تماس بگیریم؟ (دلخواه)</label><input class="form-control" type="text" name="Contact" placeholder="تلفن / موبایل / ایمیل / نام کاربری" value="" ></div>
        <div class="form-group"><label for="Message">پیام شما (دلخواه)</label><textarea class="form-control html-editor" type="text" name="Message" ></textarea></div>

        <input name="Meta" type="hidden" value="<?php echo isset($Data['FeedbackMeta']) ? $Data['FeedbackMeta'] : ''; ?>" />
        <input name="Url" type="hidden" value="<?php echo _RequestUri ?>" />

        <input name="insert" type="submit" value="ارسال" class="background-gold">
        <a href="<?php echo _Root . 'Home/Positions' ?>">می‌خواهید شما هم بخشی از قصه‌ی ساریاب باشید؟</a> |
        <a href="https://zarinp.al/@tayyebi">می‌خواهید از ساریاب حمایت کنید؟</a>

    </form>
    <!-- Feedback End -->

    <!-- Cookie -->
    <div class="cookie-container background-dark color-white">
        <p class="cookie-p">
        we care about your data, and we'd use cookies only to improve your experience</br>
        ما همچنین از کوکی‌ها برای بهبود کیفیت خدمات استفاده می‌کنیم
        </p>
        <button class="cookie-btn background-gold">
            قوانین را قبول دارم
        </button>
        <a class="cookie-btn background-gold color-dark" target="_blank" href="<?php _Root ?>Home/Rules">مرور قوانین</a>
    </div>
    <!-- Cookie End -->
</main>

<script src="<?php echo _Root . 'static/js/layout.js' ?>"></script>

</body>
</html>
