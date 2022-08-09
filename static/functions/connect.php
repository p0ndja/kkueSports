<?php declare(strict_types=1);
    ob_start();
    require_once 'init.php';
    require_once 'config.php';
    session_start();
    $start_time = microtime(TRUE);
    setlocale(LC_ALL,'en_US.UTF-8');

    global $conn;
    global $connForum;
    global $db;
    $conn = new mysqli($db["hostname"], $db["username"], $db["password"], $db["table"]);
    mysqli_set_charset($conn, 'utf8mb4');

    if(!$conn)
        die('Cannot established connection with database: ' . mysqli_connect_error());

    require_once 'function.php';
    
    date_default_timezone_set('Asia/Bangkok');
?>
