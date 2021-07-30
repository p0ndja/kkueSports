<?php
    require_once '../static/functions/connect.php';
    if (isAdmin() && isset($_GET['id']) && isset($_GET['target'])) {
        $id = (int) $_GET['id'];
        $target = $_GET['target'];

        $post = new Post((int) $id);
        if ($target == "hide") {
            $post->setProperty('hide', !($post->getProperty('hide')));
        } else if ($target == "pin") {
            $post->setProperty('pin', !($post->getProperty('pin')));
        }

        $property = json_encode($post->properties());
        if ($stmt = $conn -> prepare("UPDATE `post` SET properties=? WHERE id=?")) {
            $stmt->bind_param('si', $property,$id);
            if (!$stmt->execute()) {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = "ไม่สามารถ Query Database ได้";
                die($conn->error);
            } else {
                $_SESSION['swal_success'] = "สำเร็จ!";
                if ($post->getProperty('hide'))
                    $_SESSION['swal_success_msg'] = "ปิดการมองเห็นโพสต์ #$id แล้ว";
                else
                    $_SESSION['swal_success_msg'] = "เปิดการมองเห็นโพสต์ #$id แล้ว";
                echo "Toggled";
            }
        } else {
            echo "Can't establish database";
        }
    }
    back();
?>