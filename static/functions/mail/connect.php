<?php

    $maildb = array(
        "hostname" => "203.159.94.111",
        "username" => "graderga",
        "password" => "8db!#yYvK]8Lw6F|37wz:UwU",
        "table" => "p0ndja"
    );

    $mailsender = array(
        "name"=>"pedmd.kku.ac.th",
        "email"=>"p0ndja.dev@gmail.com",
        "password"=>"fbP>ZM4kZVPM"
    );
    $mailsenderData = json_encode($mailsender);

    global $mailconn;
    $mailconn = new mysqli($maildb["hostname"], $maildb["username"], $maildb["password"], $maildb["table"]);
    mysqli_set_charset($mailconn, 'utf8mb4');

    if(!$mailconn)
        die('Cannot established connection with database: ' . mysqli_connect_error());
    
    date_default_timezone_set('Asia/Bangkok');

?>