<?php 
    require_once '../static/functions/connect.php';
    
    function generateRandomS($length = 16) {
        $characters = md5(time());
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    if (isset($_POST['id']) && ((int) $_SESSION['user']->getID() == (int) $_POST['id'])) {
        $id = $_SESSION['user']->getID();
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];
        $real_email = $_POST['real_email'];
        $finalProfile = $_POST['profile_final'];

        if ($stmt = $conn->prepare("UPDATE `user` set firstname = ?, lastname = ?, email = ?, profile = ? WHERE id = ?")) {
            $stmt->bind_param('ssssi', $fname, $lname, $email, $finalProfile, $id);
            if (!$stmt->execute()) {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
                print_r($conn->error);
                header("Location: ../profile/");
                die();
            }
        } else {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
            $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_ESTABLISH . ": " . $conn->error;
            echo "Can't establish database";
            header("Location: ../profile/");
            die();
        }

        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $pass = md5($_POST['password']);
            if ($stmt = $conn->prepare("UPDATE `user` set password = ? WHERE id = ?")) {
                $stmt->bind_param('si', $pass, $id);
                if (!$stmt->execute()) {
                    $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                    $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
                    print_r($conn->error);
                    header("Location: ../profile/");
                    die();
                }
            } else {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_ESTABLISH . ": " . $conn->error;
                echo "Can't establish database";
                header("Location: ../profile/");
                die();
            }
        }

        /*
        if(isset($_FILES['profile_upload']) && $_FILES['profile_upload']['name'] != ""){
            if ($_FILES['profile_upload']['name']) {
                if (!$_FILES['profile_upload']['error']) {
                    $name = "profile_" . generateRandomS(8);
                    $ext = explode('.', $_FILES['profile_upload']['name']);
                    $filename = $name . '.' . $ext[1];
        
                    if (!file_exists('../file/profile/'. $id .'/')) {
                        mkdir('../file/profile/'. $id .'/');
                    }
        
                    $destination = '../file/profile/'. $id .'/' . $filename; //change this directory
                    $location = $_FILES["profile_upload"]["tmp_name"];
                    move_uploaded_file($location, $destination);
                    $finalFile = '../file/profile/'. $id .'/' . $filename;//change this URL
                    $r = mysqli_query($conn, "UPDATE `user` SET profile = '$finalFile' WHERE id = '$id'");
                    if (! $r) die("Could not set profile: " . mysqli_error($conn));
                }
            }
        }
        */

        $_SESSION['user']->setName($fname, $lname);
        $_SESSION['user']->setProfile($finalProfile);
        $_SESSION['user']->setEmail($email);
        /*
        if ($real_email != $email) {
            header("Location: ../static/functions/verify/mail.php?key=$pass&email=$email&name=$fname&method=changeEmail");
        } else {
            $_SESSION['swal_success'] = "ปรับปรุงข้อมูลสำเร็จ";
            header("Location: ../profile/");
        }
        */
        $_SESSION['swal_success'] = "ปรับปรุงข้อมูลสำเร็จ";
    }
    header("Location: ../profile/");
?>