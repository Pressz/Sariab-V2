<a class="profile-edit-btn background-dark color-white border-radius" href="<?php echo $Data['FeedbackUrl']?>">بازگشت</a>
<strong>بازخورد / گزارش شما برای ما ارزشمند است و ما حتما آن‌را بررسی خواهیم کرد.</strong>
<?php echo isset($Data['FeedbackTitle']) ? '<h1>' . $Data['FeedbackTitle'] . '</h1>' : '' ?>

<script>
    var feedbackForm = document.getElementById("Feedback");
    feedbackForm.style.display = "block";
</script>