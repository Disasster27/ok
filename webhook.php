<?php
// http://dev671.iactive.pro/scr/webhookOk/webhookOk.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json;charset=utf-8');

$token = 'tkn1U9dvFMQH5vs3zDAYszK6cEdRGxmHSvZErEogSP2x5tNHiESP5bcOs4r1cIs4ctFE2:CKOMIMJGDIHBABABA';

$subscribe = "http://ok/webhook.php";


function subscribe ($token, $subscribe)
{
    // $url = 'https://api.ok.ru/graph/me/unsubscribe?access_token='.$token;
    $url = 'https://api.ok.ru/graph/me/subscribe?access_token='.$token;
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => "{'url': $subscribe}"
            )
        )));
        print_r($result);
}


$_POST = json_decode(file_get_contents('php://input'));


var_dump ($_POST);

// информация о сообщении
$chatId = $_POST->recipient->chat_id;
$userId = $_POST->sender->user_id;
$text = $_POST->message->text;
$mid = $_POST->message->mid;

// информация о пользователе отправившем сообщение
$user = userGetInfo ($userId);


function sendMessage ($text, $chatId, $token)
{
    $urlC = "https://api.ok.ru/graph/$chatId/messages?access_token=$token";

    $data = [
        "recipient" => [
            "chat_id" => "$chatId"
        ],
        "message" => [
            "text" => "$text"
        ]
    ];

    $data = json_encode($data);


    $result = file_get_contents($urlC, false, stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/json;charset=utf-8',
            'content' => $data
        )
    )));
    print_r($result);
}

// var_dump (date("Y-m-d\TH:i:s\Z",$_POST->timestamp/1000));



function userGetInfo ($uid)
{
    $uid = str_replace('user:', '', $uid);
    $imp = "application_key=CPKJMOJGDIHBABABAfields=NAME,online,PIC50X50,PIC_FULLformat=jsonmethod=users.getInfouids={$uid}d50c64766964e75bbe2023eec2d963fd";
        
    $sig = md5($imp);
    $longToken ='tkn1QWSiZcS3fJO4RC3HKyldofk7f6TI9rotT2TIg5GDyu6lmZZyFQGxF6dM5sBhXDFR3';

    $queryParams = 
    [
        'application_key' => 'CPKJMOJGDIHBABABA',
        'format' => 'json',
        'method' => 'users.getInfo',
        'sig' => $sig,
        'access_token' => $longToken,
        'fields' => 'NAME,online,PIC50X50,PIC_FULL',
        'uids' => $uid,
    ];

    $file = json_decode(file_get_contents('https://api.ok.ru/fb.do?' . http_build_query( $queryParams )));
    
    var_dump ($file);
    return $file[0];
}

sendMessage ("message from webhook", $chatId, $token);