<?php
    require_once '../connect.php';
    require_once '../function.php';

if (isset($_POST['login_submit'])) {
    $user = $_POST['login_username'];
    $pass = md5($_POST['login_password']);

    $login = login($user, $pass);
    if (!empty($login)) {
        $_SESSION['user'] = $login;
        $_SESSION['swal_success'] = "เข้าสู่ระบบสำเร็จ";
        $_SESSION['swal_success_msg'] = "ยินดีต้อนรับ! " . $login->getName();
        
        if (isset($_POST['method'])) {
            if ($_POST['method'] == "loginPage") header("Location: ../../../home/");
            else if ($_POST['method'] == "loginNav") back();
            else header("Location: ../../../home/");
        } else {
            back();
        }

    } else {
        $_SESSION['error'] = ErrorMessage::AUTH_WRONG;
        header("Location: ../../../auth/login");
    }
}

if (isset($_POST['register_submit'])) {
    $user = $_POST['register_username'];
    $pass = md5($_POST['register_password']);
    $fname = $_POST['register_firstname'];
    $lname = $_POST['register_lastname'];
    $email = $_POST['register_email'];

    $id = latestIncrement($db["table"], 'user');

    if ($stmt = $conn->prepare("SELECT * FROM `user` WHERE username = ? OR email = ?")) {
        $stmt->bind_param('ss', $user, $email);
        if (!$stmt->execute()) {
            $_SESSION['error'] = "พบข้อผิดพลาด: " . ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
            print_r($conn->error);
            header("Location: ../../../auth/register");
            die();
        } else {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['error'] = "มีชื่อผู้ใช้นี้อยู่แล้ว หรือ อีเมลนี้ถูกใช้งานไปแล้ว";
                header("Location: ../../../auth/register");
                die();
            }
        }
    } else {
        $_SESSION['error'] = "พบข้อผิดพลาด: " . ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
        echo "Can't establish database";
        header("Location: ../../../auth/register");
        die();
    }

    if ($stmt = $conn->prepare("INSERT INTO `user` (username, password, firstname, lastname, email) VALUES (?,?,?,?,?)")) {
        $stmt->bind_param('sssss',$user,$pass,$fname,$lname,$email);
        if (!$stmt->execute()) {
            $_SESSION['error'] = "พบข้อผิดพลาด: " . ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
            print_r($conn->error);
            header("Location: ../../../auth/register");
            die();
        } else {
            $_SESSION['error'] = null;
            $_SESSION['swal_success'] = "สมัครผู้ใช้งานสำเร็จ";
            //$_SESSION['swal_success_msg'] = "อย่าลืมเข้าไปยืนยันตัวตนทางอีเมลนะครับ";
            $_SESSION['user'] = new User((int) $id);
            header("Location: ../../../home/");
            //header("Location: ../verify/mail.php?key=" . $pass . "&email=" . $email . "&name=" . $_SESSION['name']->getName() . "&method=reg");
        }
    } else {
        $_SESSION['error'] = "พบข้อผิดพลาด: " . ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
        echo "Can't establish database";
        header("Location: ../../../auth/register");
        die();
    }
}

if (isset($_GET['user']) && isset($_GET['pass'])) {
    $user = $_GET['user'];
    $pass = md5($_GET['pass']);
    if (isset($_GET['method']) && $_GET['method'] == "reset")
        $pass = $_GET['pass'];

    //Use login(username, password) function from function.php
    $login = login($user, $pass);
    if (!empty($login)) {
        $_SESSION['user'] = $login;
        $_SESSION['swal_success'] = "เข้าสู่ระบบสำเร็จ";
        $_SESSION['swal_success_msg'] = "ยินดีต้อนรับ! " . $login->getName();
        if (isset($_GET['method'])) {
            if ($_GET['method'] == "reset") {
                $_SESSION['allowAccessResetpasswordPage'] = true;
                header("Location: ../../../auth/resetpassword");
            }
            else header("Location: ../../../home/");
        } else {
            echo "Accept";
        }
    } else {
        $_SESSION['error'] = ErrorMessage::AUTH_WRONG;
    }
}
?>