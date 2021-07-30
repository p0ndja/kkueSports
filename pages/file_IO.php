<?php 
    require_once '../static/functions/connect.php';

    if (isAdmin() && isset($_GET['function'])) {
        if ($_GET['function'] == "delete") {
            if (isset($_GET['name']) && isset($_GET['method'])) {
                $file = $_GET['name'];
                if ($_GET['method'] == "file") {
                    unlink($file);
                    $file_name = pathinfo($file, PATHINFO_FILENAME) . "." . pathinfo($file, PATHINFO_EXTENSION);
                    $_SESSION['swal_success'] = "ลบไฟล์สำเร็จ";
                    $_SESSION['swal_success_msg'] = "ลบไฟล์ $file_name เรียบร้อยแล้ว!";
                } else if ($_GET['method'] == "dir") {
                    if (remove_directory($file)) {
                        $path = pathinfo($file, PATHINFO_DIRNAME);
                        $_SESSION['swal_success'] = "ลบโฟลเดอร์สำเร็จ";
                        $_SESSION['swal_success_msg'] = "ลบโฟลเดอร์ $path เรียบร้อยแล้ว!";
                    } else {
                        $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                        $_SESSION['swal_error_msg'] = "พบไฟล์ในโฟลเดอร์ โปรดลบไฟล์ในโฟลเดอร์ก่อนแล้วจึงลบโฟลเดอร์นี้";
                    }
                }
            }
        } else if ($_GET['function'] == "create") {
            if (isset($_GET['mkdir'])) {
                $mkdir = $_GET['mkdir'];
                $path = $_GET['path'];
                $mkdir = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $mkdir);
                make_directory("$path/$mkdir");
            } else if (count($_FILES['attachment']['name'])) {
                $fileTotal = count($_FILES['attachment']['name']);
                if (is_uploaded_file($_FILES['attachment']['tmp_name'][0])) {
                    $path = $_POST['path'];
                    if (!file_exists("$path/")) {
                        make_directory("$path/");
                    }
                    $name_file = "";
                    for ($i = 0; $i < $fileTotal; $i++) {
                        if($_FILES['attachment']['tmp_name'][$i] != ""){
                            $name_file = $_FILES['attachment']['name'][$i];
                            $tmp_name = $_FILES['attachment']['tmp_name'][$i];
                            $locate_img = "$path/";
                            move_uploaded_file($tmp_name,$locate_img.$name_file);
                            rename($locate_img.$name_file, $locate_img.$name_file);
                        }
                    }

                    $_SESSION['swal_success'] = "อัพโหลดไฟล์สำเร็จ";
                    if ($fileTotal == 1) {
                        $_SESSION['swal_success_msg'] = "เพิ่มไฟล์ $name_file เรียบร้อยแล้ว!";
                    } else {
                        $_SESSION['swal_success_msg'] = "เพิ่ม $fileTotal ไฟล์เรียบร้อยแล้ว!";
                    }
                }
            }
        } else if ($_GET['function'] == "rename") {
            if (isset($_GET['old']) && isset($_GET['new']) && file_exists($_GET['old'])) {
                if (file_exists($_GET['new'])) {
                    $_SESSION['swal_warning'] = "พบข้อผิดพลาด";
                    $_SESSION['swal_warning_msg'] = ErrorMessage::FILE_DUPLICATE;
                } else {                  
                    $path = $_GET['old'];
                    $dir = pathinfo($path, PATHINFO_DIRNAME);
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    if (!empty($dir)) $dir .= "/";
                    if (!empty($ext)) $ext = ".$ext";

                    rename($_GET['old'], $dir . $_GET['new'] . $ext);
                }
            }
        }
    }

    back();
?>