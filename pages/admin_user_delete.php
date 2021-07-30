<?php require_once '../static/functions/connect.php'; ?>
<head><?php require_once '../static/functions/head.php'; ?></head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-normal fixed-top scrolling-navbar" id="nav"
        role="navigation">
        <?php require_once '../static/functions/navbar.php'; ?>
    </nav>
<?php
    needAdmin();
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        global $conn;
        $result = $conn->multi_query("DELETE FROM `user` WHERE id = $id; DELETE FROM `achievement` WHERE id = $id; DELETE FROM `profile` WHERE id = $id")
        if (!$result) {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
            $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
            print_r($conn->error);
            header("Location: ../admin/user-$id");
            die();
        }
        
        remove_directory("../file/profile/$id");
        ?>
        <script>swal("ลบผู้ใช้งาน ID : <?php echo $id; ?> เรียบร้อยแล้ว!", {icon: "success",}).then(setTimeout(function (){window.history.back()}, 1800)); </script>
    <?php }
?>
<footer class="d-none">
<?php require_once '../static/functions/footer.php'; ?>
</footer>
<?php require_once '../static/functions/popup.php'; ?>
</body>