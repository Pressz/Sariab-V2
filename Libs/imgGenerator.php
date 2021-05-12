<?php
// Load GDText Library
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/Struct/Point.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/Struct/Rectangle.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/Box.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/BoxPatched.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/Color.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/HorizontalAlignment.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/TextWrapping.php');
include_once(dirname(__DIR__) . '/Libs/gd-text-master/src/VerticalAlignment.php');
// include_once(dirname(__DIR__) . '/Libs/php-gd-persian-master/phpgd/fagd.php');
include_once(dirname(__DIR__) . '/Libs/php-gd-persian-master/phpgd/fagd1.php');

use GDText\BoxPatched;
use GDText\Color;

class ImageGenerator {
    function PostCard($title, $author)
    {
        $im = imagecreatetruecolor(500, 500);
        $backgroundColor = imagecolorallocate($im, 254,251,234);
        imagefill($im, 0, 0, $backgroundColor);

        // Create box pointing image
        $box = new BoxPatched($im);
        $fagd = new FAGD();
        // $box->enableDebug();

        $font = dirname(__DIR__).'/static/fonts/Vazir-Bold.ttf';
        $box->setFontFace($font);









        if (preg_match('/.*[چجحخهعغفقثصضشسیبلاتنمکگوپدذرزطظژ].*/u', $title)){
            // Persian Text
            $title = $fagd->Do($title,'fa','normal');
            $box->setReverseTextLinesOrder(true);
        }
        $box->setBox(20, 20, 460, 460);
        $box->setFontColor(new Color(30, 30, 30));
        $box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);
        $box->setFontSize(40);
        $box->setStrokeColor(new Color(255,239,0)); // Set stroke color
        $box->setStrokeSize(1); // Stroke size in pixels
        $box->setTextAlign('center', 'center');
        $box->draw($title);








        if (preg_match('/.*[چجحخهعغفقثصضشسیبلاتنمکگوپدذرزطظژ].*/u', $author)){
            // Persian Text
            $author = $fagd->Do($author,'fa','normal');
            $box->setReverseTextLinesOrder(true);
        }
        $box->setFontSize(12);
        $box->setLineHeight(1.5);
        $box->setBox(20, 20, 460, 460);
        $box->setTextAlign('right', 'bottom');
        $box->draw($author);






        return $im;
    }
    
}
?>