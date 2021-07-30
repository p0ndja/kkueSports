<?php
    require_once '../static/functions/connect.php';

    if (isAdmin()) {

        $name_file = (isset($_GET['name'])) ? $_GET['name'] : "";

        if (isset($_FILES['carousel_file']) && $_FILES['carousel_file']['name'] != "") { //Update Image case
            $name_file = $_FILES['carousel_file']['name'];
            $tmp_name = $_FILES['carousel_file']['tmp_name'];
            $locate_img ="../static/elements/carousel/banner/";
            if (!file_exists($locate_img)) {
                make_directory($locate_img);
            }
            move_uploaded_file($tmp_name,$locate_img.$name_file);
        }


        if (isset($_GET['name']) && ($_GET['name'] != $name_file)) { //New Image File
            unlink($locate_img . $_GET['name']);
            unlink($locate_img . $_GET['name'] . '.txt');
        }

        $title = $_POST['cTitle'];

        $file = fopen("../static/elements/carousel/banner/$name_file.txt","w");
        if (!fwrite($file,"$title")) {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด!";
            $_SESSION['swal_error_msg'] = "ไม่สามารถเขียน/อ่านไฟล์ได้";
        } else {
            $_SESSION['swal_success'] = "สำเร็จ!";
            fclose($file);
        }
    }
    header("Location: ../home/#carousel");
?>