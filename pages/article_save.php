<?php
require_once '../static/functions/connect.php';

if (isset($_POST['submit']) && isAdmin()) {

    $method = $_POST['method'];
    $id = ($method == "create") ? latestIncrement($db["table"], 'post') : (int) $_GET['news'];
    $title = $_POST['title'];
    $article = $_POST['article'];
    $tags = explode(",", $_POST['tags']);

    $finaldir = $_POST['real_cover'];
    if (isset($_FILES['cover']) && $_FILES['cover']['name'] != "") {
        $name_file = $_FILES['cover']['name'];
        $tmp_name = $_FILES['cover']['tmp_name'];
        $locate_img ="../file/post/".$id."/"."thumbnail/";
        if (!file_exists($locate_img)) {
            if (!make_directory($locate_img)) die(ErrorMessage::FILE_IO);
        }
        if (!move_uploaded_file($tmp_name,$locate_img.$name_file)) die(ErrorMessage::FILE_UPLOAD_NOT_FOUND);
        $finaldir = $locate_img.$name_file;
        //$thumbnail = createThumbnail($finaldir, 0.33);
    }

    $fileTotal = count($_FILES['attachment']['name']);
    if (is_uploaded_file($_FILES['attachment']['tmp_name'][0])) {
        if (!file_exists("../file/post/$id/attachment/")) {
            //First time upload.
            make_directory("../file/post/$id/attachment/");
        } else {
            //Remove all uploaded file.
            foreach(glob("../file/post/$id/attachment/*") as $f) unlink($f);
        }
        for ($i = 0; $i < $fileTotal; $i++) {
            if($_FILES['attachment']['tmp_name'][$i] != ""){
                $name_file = $_FILES['attachment']['name'][$i];
                $tmp_name = $_FILES['attachment']['tmp_name'][$i];
                $locate_img ="../file/post/".$id.'/'.'attachment/';
                if (!move_uploaded_file($tmp_name,$locate_img.$name_file)) die(ErrorMessage::FILE_UPLOAD_NOT_FOUND);
                if (!rename($locate_img.$name_file, $locate_img.$name_file)) die(ErrorMessage::FILE_IO);
            }
        }
    } else if (empty($_POST['attachmentURL'])) {
        //User reset attachment field
        foreach(glob("../file/post/$id/attachment/*") as $f) unlink($f);
    }

    $post = new Post((int) $id);
        $post->setProperty('author', $_SESSION['user']->getID());
        $post->setProperty('category', $_POST['group']);
        $post->setProperty('updated', time());
        $post->setProperty('hide', (isset($_POST['isHidden']) && $_POST['isHidden'] == 'on') ? true : false);
        $post->setProperty('pin',  (isset($_POST['isPinned']) && $_POST['isPinned'] == 'on') ? true : false);
        $post->setProperty('tag', $tags);
        $post->setProperty('cover', $finaldir);
        if ($method == "create") $post->setProperty('allowDelete', true);
    $properties = json_encode($post->properties());

    if ($method == "create") {
        if ($stmt = $conn->prepare("INSERT INTO `post` (title, article, properties) VALUES (?,?,?)")) {
            $stmt->bind_param('sss', $title, $article, $properties);
            if (!$stmt->execute()) {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
                print_r($conn->error);
            } else {
                $_SESSION['swal_success'] = "สำเร็จ!";
                $_SESSION['swal_success_msg'] = "เพิ่มโพสต์ $title #$id แล้ว!";
                echo "Post Created";
            }
        } else {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
            $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
            echo "Can't establish database";
        }
    } else {
        if ($stmt = $conn->prepare("UPDATE `post` SET title=?, article=?, properties=? WHERE id=?")) {
            $stmt->bind_param('ssss', $title, $article, $properties, $id);
            if (!$stmt->execute()) {
                $_SESSION['swal_error'] = "พบข้อผิดพลาด";
                $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . "\n" . $conn->error;
                print_r($conn->error);
            } else {
                $_SESSION['swal_success'] = "สำเร็จ!";
                $_SESSION['swal_success_msg'] = "อัพเดทโพสต์ $title #$id แล้ว!";
                echo "Post Updated";
            }
        } else {
            $_SESSION['swal_error'] = "พบข้อผิดพลาด";
            $_SESSION['swal_error_msg'] = ErrorMessage::DATABASE_QUERY . ": " . $conn->error;
            echo "Can't establish database";
        }
    }
}
header("Location: ../category/".$_POST['group']."-1");
?>