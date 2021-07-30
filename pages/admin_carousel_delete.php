<?php
    require_once '../static/functions/connect.php';

    if (isAdmin()) {
        if (isset($_GET['target']) && file_exists('../static/elements/carousel/banner/' . $_GET['target'])) {
            $pic = $_GET['target'];
            $picName = pathinfo($pic, PATHINFO_FILENAME);

            if (unlink('../static/elements/carousel/banner/' . $pic) && unlink("../static/elements/carousel/banner/$picName.txt")) {
                $_SESSION['swal_success'] = "ลบรูปภาพสำเร็จ!";
            } else {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด!";
                $_SESSION['swal_error_msg'] = "ลบรูปภาพไม่สำเร็จ!";
            }
            
        }
    }
    header("Location: ../home/#carousel");
?>