<?php
    require_once 'connect.php';

    $data = array(
                'secret' => "0xE838c11950365625476E7a2d3fD0353F1bd467f0",
                'response' => $_POST['h-captcha-response']
            );

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    // var_dump($response);

    $responseData = json_decode($response);
    if ($responseData->success) {
        echo "SUCCESS";
    } else {
        echo "REJECTED";
    }
?>