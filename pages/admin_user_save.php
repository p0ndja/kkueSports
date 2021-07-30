<?php 
    require_once '../static/functions/connect.php';

    if (isset($_POST['real_id']) && isAdmin()) {
        $id = $_POST['real_id'];
        $username = $_POST['username'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];

        $admin = isset($_POST['role']) ? ($_POST['role'] == "admin") : false;
        
        if ($stmt = $conn->prepare("UPDATE `user` set username = ?, firstname = ?, lastname = ?, email = ?, admin = ? WHERE id = ?")) {
            $stmt->bind_param('ssssii', $username, $fname, $lname, $email, $admin, $id);
            if (!$stmt->execute()) {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
                print_r($conn->error);
                header("Location: ../admin/user-$id");
                die();
            }
        } else {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
            $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
            echo "Can't establish database";
            header("Location: ../admin/user-$id");
            die();
        }

        if (isset($_POST['password']) && !empty($_POST['password'])) {
            if ($stmt = $conn->prepare("UPDATE `user` set password = ? WHERE id = ?")) {
                $stmt->bind_param('si', $pass, $id);
                if (!$stmt->execute()) {
                    $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                    $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
                    print_r($conn->error);
                    header("Location: ../admin/user-$id");
                    die();
                }
            } else {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
                echo "Can't establish database";
                header("Location: ../admin/user-$id");
                die();
            }
        }

        $finalFile = "";
        if(isset($_FILES['profile_upload']) && $_FILES['profile_upload']['name'] != ""){
            if ($_FILES['profile_upload']['name']) {
                if (!$_FILES['profile_upload']['error']) {
                    $name = "profile_" . generateRandom(8);
                    $ext = explode('.', $_FILES['profile_upload']['name']);
                    $filename = $name . '.' . $ext[1];
        
                    if (!file_exists('../file/profile/'. $id .'/')) {
                        mkdir('../file/profile/'. $id .'/');
                    }
        
                    $destination = '../file/profile/'. $id .'/' . $filename; //change this directory
                    $location = $_FILES["profile_upload"]["tmp_name"];
                    move_uploaded_file($location, $destination);
                    $finalFile = '../file/profile/'. $id .'/' . $filename;//change this URL

                    if ($stmt = $conn->prepare("UPDATE `user` set profile = ? WHERE id = ?")) {
                        $stmt->bind_param('si', $finalFile, $id);
                        if (!$stmt->execute()) {
                            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                            $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
                            print_r($conn->error);
                            header("Location: ../admin/user-$id");
                            die();
                        }
                    } else {
                        $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                        $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
                        echo "Can't establish database";
                        header("Location: ../admin/user-$id");
                        die();
                    }
                }
            }
        }

        $_SESSION['swal_success'] = "ปรับปรุงข้อมูลสำเร็จ";
        $_SESSION['swal_success_msg'] = "คุณได้ปรับปรุงข้อมูลของ $fname $lname ($id)";
        header("Location: ../admin/user-$id");
    }
?>