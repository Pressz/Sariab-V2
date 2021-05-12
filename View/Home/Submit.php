<form
   class="submit-form"
   method="post">

   <div class="form-group"><label for="Title">عنوان</label><input class="form-control" type="text" name="Title" value="" ></div>
   <div class="form-group"><label for="Abstract">خلاصه</label><textarea class="form-control html-editor" type="text" name="Abstract" ></textarea></div>
   <div class="form-group"><label for="Canonical">لینک</label><input class="form-control" type="text" name="Canonical" value="" ></div>


   <?php
   if (isset($Data['ExternalWriter']) and $Data['ExternalWriter'] != null) {
   ?>
   <div class="form-group"><label for="Publisher">ارسال فرسته از طرف عضو نگارخانه</label><input readonly class="form-control" type="text" name="Publisher" value="<?php echo $Data['ExternalWriter'] ?>" ></div>
   <?php
   } else {
   ?>
   <div class="form-group"><label for="Publisher">نشر دهنده</label><input class="form-control" type="text" name="Publisher" value="" ></div>
   <?php } ?>
   <input name="Language" type="hidden" value="fa">
   <input name="insert" type="submit" value="ارسال" class="background-gold">
   <a href="<?php echo _Root . 'Home/Positions' ?>">نویسنده‌ی رسمی ساریاب شوید</a> |
   <a href="<?php echo _Root . 'Home/Submit/true' ?>">نویسنده‌ی رسمی هستید؟</a>
</form>