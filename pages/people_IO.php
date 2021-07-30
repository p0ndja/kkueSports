<?php
    require_once '../static/functions/connect.php';

    if (isAdmin() && isset($_GET['function'])) {
        $topPath = "../static/elements/people/";
        if ($_GET['function'] == "create") {
            if (isset($_GET['mkdir'])) {
                if (isset($_GET['top'])) $topPath .= $_GET['top']."/";
                $name = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $_GET['mkdir']);
                $targetPath = $topPath.$name;
                make_directory($targetPath);
                $_SESSION['swal_success'] = "สร้างหมวดหมู่สำเร็จ";
                $_SESSION['swal_success_msg'] = "เพิ่มหมวดหมู่ $name เรียบร้อยแล้ว!";
            } else if (isset($_FILES['img']) && $_FILES['img']['name'] != "") {
                if (is_uploaded_file($_FILES['img']['tmp_name'])) {
                    $path = $_POST['path'];
                    if (!file_exists("$path/")) {
                        make_directory("$path/");
                    }
                    $name_file = "";
                    if($_FILES['img']['tmp_name'] != ""){
                        $name_file = $_FILES['img']['name'];
                        $tmp_name = $_FILES['img']['tmp_name'];
                        $locate_img = "$path/";
                        while (file_exists($locate_img.$name_file)) {
                            $name_file = pathinfo($name_file, PATHINFO_FILENAME) . "_" . generateRandom(5) . "." . pathinfo($name_file, PATHINFO_EXTENSION);
                        }
                        move_uploaded_file($tmp_name,$locate_img.$name_file);
                        rename($locate_img.$name_file, $locate_img.$name_file);

                        $title = $_POST['name'];
                        $description = isset($_POST['description']) && !empty($_POST['description']) ? $_POST['description'] : "";

                        $file = fopen("$path/$name_file.txt","w");
                        if (!fwrite($file,"$title\n$description")) {
                            $_SESSION['swal_error'] = "พบข้อผิดพลาด!";
                            $_SESSION['swal_error_msg'] = "ไม่สามารถเขียน/อ่านไฟล์ได้";
                        } else {
                            fclose($file);
                            $_SESSION['swal_success'] = "อัปโหลดสำเร็จ";
                            $_SESSION['swal_success_msg'] = "เพิ่ม $title เรียบร้อยแล้ว!";    
                        }
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
        } else if ($_GET['function'] == "delete") {
            if (isset($_GET['name']) && isset($_GET['method'])) {
                $file = $_GET['name'];
                if ($_GET['method'] == "file") {
                    unlink($file);
                    unlink($file.".txt");
                    $file_name = pathinfo($file, PATHINFO_FILENAME) . "." . pathinfo($file, PATHINFO_EXTENSION);
                    $_SESSION['swal_success'] = "ลบสำเร็จ";
                    $_SESSION['swal_success_msg'] = "ลบ $file_name เรียบร้อยแล้ว!";
                } else if ($_GET['method'] == "dir") {
                    if (remove_directory($file)) {
                        $path = pathinfo($file, PATHINFO_FILENAME);
                        $_SESSION['swal_success'] = "ลบหมวดหมู่สำเร็จ";
                        $_SESSION['swal_success_msg'] = "ลบหมวดหมู่ $path เรียบร้อยแล้ว!";
                    } else {
                        $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                        $_SESSION['swal_error_msg'] = "พบในหมวดหมู่ โปรดลบในหมวดหมู่ก่อนแล้วจึงลบหมวดหมู่นี้";
                    }
                }
            }
        } else if ($_GET['function'] == "update") {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                $old_file_name = $_GET['target'];
                $title = $_POST["name_$id"];
                $description = $_POST["description_$id"];

                if (isset($_FILES["img_$id"]) && $_FILES["img_$id"]['name'] != "") {
                    if (is_uploaded_file($_FILES["img_$id"]['tmp_name'])) {
                        $path = $_POST['path'];
                        if (!file_exists("$path/")) {
                            make_directory("$path/");
                        }
                        $name_file = "";
                        if($_FILES["img_$id"]['tmp_name'] != ""){
                            $name_file = $_FILES["img_$id"]['name'];
                            $tmp_name = $_FILES["img_$id"]['tmp_name'];
                            $locate_img = "$path/";
                            while (file_exists($locate_img.$name_file)) {
                                $name_file = pathinfo($name_file, PATHINFO_FILENAME) . "_" . generateRandom(5) . "." . pathinfo($name_file, PATHINFO_EXTENSION);
                            }
                            move_uploaded_file($tmp_name,$locate_img.$name_file);
                            rename($locate_img.$name_file, $locate_img.$name_file);

                            if ($old_file_name != $name_file) {
                                unlink("$path/$old_file_name");
                                unlink("$path/$old_file_name.txt");
                            }

                            $file = fopen("$path/$name_file.txt","w");
                            if (!fwrite($file,"$title\n$description")) {
                                $_SESSION['swal_error'] = "พบข้อผิดพลาด!";
                                $_SESSION['swal_error_msg'] = "ไม่สามารถเขียน/อ่านไฟล์ได้";
                            } else {
                                fclose($file);
                                $_SESSION['swal_success'] = "อัปโหลดสำเร็จ";
                                $_SESSION['swal_success_msg'] = "เพิ่ม $title เรียบร้อยแล้ว!";    
                            }
                        }
                    }
                } else {
                    $file = fopen("$old_file_name.txt","w");
                    if (!fwrite($file,"$title\n$description")) {
                        $_SESSION['swal_error'] = "พบข้อผิดพลาด!";
                        $_SESSION['swal_error_msg'] = "ไม่สามารถเขียน/อ่านไฟล์ได้";
                    } else {
                        fclose($file);
                        $_SESSION['swal_success'] = "อัปโหลดสำเร็จ";
                        $_SESSION['swal_success_msg'] = "เพิ่ม $title เรียบร้อยแล้ว!";    
                    }
                }

            }
        }
    }
    
    back();
?>