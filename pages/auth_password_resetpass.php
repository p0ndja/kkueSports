<?php 
    require_once '../static/functions/connect.php';
    require_once '../static/functions/function.php';

    if (isset($_POST['setNewPassword']) && isLogin() && isset($_SESSION['allowAccessResetpasswordPage']) && $_SESSION['allowAccessResetpasswordPage'] == true) {
        $password = $_POST['setNewPassword'];
        $md5_pass = md5($password);
        $id = $_SESSION['user']->getID();

        if ($stmt = $conn->prepare("UPDATE `user` SET `password` = ? WHERE id = ?")) {
            $stmt->bind_param('si', $md5_pass, $id);
            if ($stmt->execute()) {
                $_SESSION['swal_success'] = "เปลี่ยนรหัสผ่านสำเร็จ";
                unset($_SESSION['allowAccessResetpasswordPage']);
                header("Location: ../home/");
                die();
            } 
        } else {
            $_SESSION['error'] = "ไม่สามารถรีเซ็ตรหัสผ่านได้: ข้อผิดพลาดภายใน";
            header("Location: ../resetpassword/");
        }
    } else {
        header("Location: ../home/");
    }
?>