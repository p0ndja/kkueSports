<?php
    require_once './static/functions/function.php';
    make_directory('./file/');
    make_directory('./file/upload/');
    make_directory('./file/forum/');
    make_directory('./file/post/');
    make_directory('./file/post/editor/');
    make_directory('./file/profile/');
    make_directory('./static/elements/people/');
    make_directory('./static/elements/carousel/');
    unlink('mkdir.php');
?>