<?php
    require_once '../static/functions/connect.php';
    
    if (isset($_GET['id']) && isAdmin()) {
        $id = (int) $_GET['id'];
        $post = new Post($id);
        if ($post->getProperty('allowDelete') == true) {
            if ($stmt = $conn->prepare("DELETE FROM `post` WHERE id = ?")) {
                $stmt->bind_param('i', $id);
                if (!$stmt->execute()) {
                    $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                    $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
                    print_r($conn->error);
                } else {
                    $_SESSION['swal_success'] = "สำเร็จ!";
                    $_SESSION['swal_success_msg'] = "ลบโพสต์ข่าว #$id แล้ว!";
                }
            } else {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
                echo "Can't establish database";
            }
        } else {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
            $_SESSION['swal_error_msg'] = "โพสต์นี้ไม่สามารถลบได้";
            echo "allowDelete = false";
        }
    }

    header("Location: ../post/");
?>