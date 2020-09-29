<?php
error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL ^E_NOTICE);

require_once("config.php");
require_once('UserGroups.php');
require_once('Authorize.php');

$auth = new Authorize();
$auth->authorized ();





// $paramsRe = array(
//     'client_id'     => $this->clientId,
//     'refresh_token' => file_get_contents('refresh.txt'),
//     'grant_type' => 'refresh_token', 
//     'client_secret' => $this->clientSecret,
// );

// $url = "https://api.ok.ru/graph/me/subscribe?access_token=tkn1U9dvFMQH5vs3zDAYszK6cEdRGxmHSvZErEogSP2x5tNHiESP5bcOs4r1cIs4ctFE2:CKOMIMJGDIHBABABA";

// $url = $this->tokenUrl . http_build_query( $paramsRe );

// $ch = curl_init();

// curl_setopt($ch,CURLOPT_URL, $url);
// curl_setopt($ch,CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
// curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

// $result = curl_exec($ch);
// var_dump ($result);
// curl_close($ch);

// $accessToken = json_decode($result)->access_token;








// $tocken = 'tkn1U9dvFMQH5vs3zDAYszK6cEdRGxmHSvZErEogSP2x5tNHiESP5bcOs4r1cIs4ctFE2:CKOMIMJGDIHBABABA';
// $url = 'https://api.ok.ru/graph/me/subscribe?access_token='.$tocken;
//     $result = file_get_contents($url, false, stream_context_create(array(
//         'http' => array(
//             'method'  => 'POST',
//             'header'  => 'Content-type: application/json',
//             'content' => '{"url": "http://ok/index.php"}'
//         )
//     )));
//     print_r($result);


    var_dump ($_REQUEST);






// https://api.ok.ru/graph/me/subscribe?access_token=tkn1U9dvFMQH5vs3zDAYszK6cEdRGxmHSvZErEogSP2x5tNHiESP5bcOs4r1cIs4ctFE2:CKOMIMJGDIHBABABA


















// tkn1QWSiZcS3fJO4RC3HKyldofk7f6TI9rotT2TIg5GDyu6lmZZyFQGxF6dM5sBhXDFR3





// // Используем для подписи secret_key = MD5(access_token + application_secret_key)
// // MD5 (-s-1g8ntIO09mCmoITn-hkLKE8J8eAqsBJo8N7LS1RCA-mm1 + 7649E37AB9547FD1E16AD2E3)
// // secret_key = 66bd5cf6909a005826e5c90009cf117e
// // Сортируем и склеиваем параметры запроса и secret_key
// // application_key=CPKJMOJGDIHBABABAformat=jsonmethod=friends.get66bd5cf6909a005826e5c90009cf117e
// // Рассчитываем MD5 от полученной строки и получаем параметр sig
// // a7407475c71f2bd1647e1bc148a98f76

// $access_token = 'tkn1QWSiZcS3fJO4RC3HKyldofk7f6TI9rotT2TIg5GDyu6lmZZyFQGxF6dM5sBhXDFR3';
// $application_secret_key = '7649E37AB9547FD1E16AD2E3';
// $application_key = 'CPKJMOJGDIHBABABA';
// $gid = '60177729323234';

// $seKe = md5("$access_token$application_secret_key");
// $fr = 'application_key=CPKJMOJGDIHBABABAformat=jsonmethod=friens.get';

// // $md = md5("$fr$seKe");

// // var_dump ($seKe);
// // var_dump ($md);

// // echo ("<a href='https://api.ok.ru/fb.do?application_key=CPKJMOJGDIHBABABA&format=json&method=friends.get&sig={$md}&access_token={$access_token}'>asd</a>");


// $fr2 = "application_key={$application_key}fields=feed.*,media_topic.*format=jsongid={$gid}method=stream.getpatterns=POST";

// $md = md5("$fr2$seKe");

// echo '<br/>';
// // $file = file_get_contents("https://api.ok.ru/fb.do?application_key={$application_key}&fields=feed.*,media_topic.*&format=json&gid={$gid}&method=stream.get&patterns=POST&sig={$md}&access_token={$access_token}");
// // // var_dump ($_POST);
// // var_dump (json_decode($file));

// $f = json_decode(file_get_contents("https://api.ok.ru/fb.do?application_key=CPKJMOJGDIHBABABA&format=json&method=group.getUserGroupsV2&sig=d5d6bdee2d9feb269133e907c024a17f&access_token=tkn1QWSiZcS3fJO4RC3HKyldofk7f6TI9rotT2TIg5GDyu6lmZZyFQGxF6dM5sBhXDFR3"));


// // discussions.getDiscussions
// // category: GROUP


//     // totalCount:2
//     // offset:0
//     // discussions:[
//     // {
//     // entityId:152176340661218
//     // entityType:GROUP_TOPIC
//     // entityOwnerId:559010004450
//     // lastActivityDate:2020-09-22 10:47:17
//     // lastUserAccessDate:2020-09-22 10:47:17
//     // newCommentsCount:0
//     // totalCommentsCount:1
//     // parentEntityId:60177729323234
//     // subjectLabel:
//     // parentSubjectLabel:Тестовая
//     // }
//     // {
//     // entityId:152176162010082
//     // entityType:GROUP_TOPIC
//     // entityOwnerId:559010004450
//     // lastActivityDate:2020-09-22 10:05:11
//     // lastUserAccessDate:2020-09-22 10:05:11
//     // newCommentsCount:0
//     // totalCommentsCount:1
//     // parentEntityId:60177729323234
//     // subjectLabel:Новая тема
//     // parentSubjectLabel:Тестовая
//     // }
//     // ]
//     // }

// echo ("<a href='https://api.ok.ru/graph/me/chats?access_token=tkn1siDVEEWvlK6wZbO2MnnbPQSe63tYNNimnnmwXu6wb6wbpxqhy7opLPQrzRWzRjzS5:CDECJPJGDIHBABABA'>chats</a>");