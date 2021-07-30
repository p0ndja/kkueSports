<?php
    require_once '../static/functions/connect.php';

    if (isAdmin()) {

        $vdo = $_POST['vdo'];

        $AllVDO = "";
        foreach($vdo as $v) {
            /* Case Youtube */
            $v = str_replace("youtube.com/watch?v=", "youtube.com/embed/", $v);
            $v = str_replace("youtu.be/", "youtube.com/embed/", $v);
            if (!empty($v))
                $AllVDO .= "$v\n";
        }

        $file = fopen("../static/elements/video.txt","w");
        if (!fwrite($file,"$AllVDO")) {
            $_SESSION['swal_error'] = ErrorMessage::ERROR;
            $_SESSION['swal_error_msg'] = ErrorMessage::FILE_IO;
        } else {
            $_SESSION['swal_success'] = ErrorMessage::SUCCESS;
            $_SESSION['swal_success_msg'] = "แก้ไขรายการวิดีโอแนะนำสำเร็จ";
        }
        fclose($file);
    }

    header("Location: ../home/#VDOSection");
?>