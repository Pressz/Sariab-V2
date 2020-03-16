<?php
require_once 'config.pass.php';
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>قدردان شماییم</title>

    <link rel="stylesheet" href="static/css/layout.css">
    <link rel="stylesheet" href="static/css/thankyou.css">
</head>
<body>
<header>
<h1>قدردان شماییم</h1>

</header>

<main>
<div class="container">

    <?php
    $select_posts_query = "SELECT *
    FROM `Supports`";

    $result = $conn->query($select_posts_query);
    while ($row = $result->fetch_assoc()) {
    ?>
    <div class="item">
        <h3><?php echo $row['Name'] ?></h3>
        <a href="#">🔗</a>
    </div>
    <?php
    }
    ?>

    <!-- <div class="loader"></div> -->

</div>
<!-- End of container -->

</main>
</body>
</html>
