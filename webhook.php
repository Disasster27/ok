<?php
// http://dev671.iactive.pro/scr/webhookOk/webhookOk.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json;charset=utf-8');

$tocken = 'tkn1U9dvFMQH5vs3zDAYszK6cEdRGxmHSvZErEogSP2x5tNHiESP5bcOs4r1cIs4ctFE2:CKOMIMJGDIHBABABA';

// // $url = 'https://api.ok.ru/graph/me/unsubscribe?access_token='.$tocken;
// $url = 'https://api.ok.ru/graph/me/subscribe?access_token='.$tocken;
//     $result = file_get_contents($url, false, stream_context_create(array(
//         'http' => array(
//             'method'  => 'POST',
//             'header'  => 'Content-type: application/json',
//             'content' => '{"url": "http://ok/webhook.php"}'
//         )
//     )));
//     print_r($result);




$_POST = json_decode(file_get_contents('php://input'));


var_dump ($_POST);


$chatId = $_POST->recipient->chat_id;
$userId = $_POST->sender->user_id;
$text = $_POST->message->text;

$user = userGetInfo ($userId);
var_dump ($user);

$urlC = "https://api.ok.ru/graph/$chatId/messages?access_token=$tocken";

$data = [
    "recipient" => [
        "chat_id" => "$chatId"
    ],
    "message" => [
        "text" => "$userId,{$user->name},{$user->online},{$user->pic50x50},{$user->pic_full}"
    ]
];



$data = json_encode($data);
var_dump ($data);
// die;


// // $url = 'https://api.ok.ru/graph/me/unsubscribe?access_token='.$tocken;
// $url = 'https://api.ok.ru/graph/me/subscribe?access_token='.$tocken;
$result = file_get_contents($urlC, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/json;charset=utf-8',
        'content' => $data
    )
)));
print_r($result);

// var_dump (date("Y-m-d\TH:i:s\Z",$_POST->timestamp/1000));



function userGetInfo ($uid)
{
    $uid = str_replace('user:', '', $uid);
    $imp = "application_key=CPKJMOJGDIHBABABAfields=NAME,online,PIC50X50,PIC_FULLformat=jsonmethod=users.getInfouids={$uid}d50c64766964e75bbe2023eec2d963fd";
        $sig = md5($imp);

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