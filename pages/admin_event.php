<?php 
    require_once '../static/functions/connect.php';
?>

<!DOCTYPE html>
<html lang="th" prefix="og:http://ogp.me/ns#">

<head>
    <?php require_once '../static/functions/head.php'; ?>
</head>
<body>
    <?php needAdmin(); ?>
    <?php require_once '../static/functions/navbar.php'; ?>
    <div class="container mb-3">
        <?php
            $_GET['path'] = '../static/elements/carousel/event/'; 
            $_GET['allowCreateFolder'] = 1;
            $_GET['upload'] = 1;
            $_GET['title'] = "แก้ไขภาพกิจกรรม";
            include '../pages/file.php';
            //TODO: Add upload,edit,delete buttons.
        ?>
    </div>
    <?php require_once '../static/functions/popup.php'; ?>
    <?php require_once '../static/functions/footer.php'; ?>
</body>

</html>