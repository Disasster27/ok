<?php
require_once("config.php");
require_once("UserGroups.php");


if (isset($_GET['code'])) {

    // Формируем ссылку для POST запроса на получение access_token
    $paramsT = array(
        'client_id'     => $clientId,
        'redirect_uri'  => $redirectUri,
        'code' => $_GET['code'],
        'grant_type' => 'authorization_code', 
        'client_secret' => $clientSecret,
    );


    $url = $tokenUrl . http_build_query( $paramsT );

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

    $result = curl_exec($ch);

    curl_close($ch);

    var_dump ($result);

    $refreshToken = json_decode($result)->refresh_token;
    $accessToken = json_decode($result)->access_token;

    $_SESSION['token'] = json_decode($result)->access_token;
    $_SESSION['refresh'] = json_decode($result)->refresh_token;

    $handleToken = fopen('token.txt', 'w');
    fwrite($handleToken, $accessToken);
    fclose($handleToken);
    echo $accessToken;

    $handleRefresh = fopen('refresh.txt', 'w');
    fwrite($handleRefresh, $refreshToken);
    fclose($handleRefresh);
    echo $refreshToken;

    echo '<a href="logout.php">Exit</a><br />';
    echo '<p>Выберите группу для подключения</p><br />';

    $userGroups  = new UserGroups();
    $userGroups ->getUserGroups();

    echo ("<a href='repeat.php'>stat<a/>");
};

// function getSig ($secretKey, $applicationKey, $method, $fields = '')
// {
//     $fieldsQuery = $fields ? "fields={$fields}" : ''; 
//     $imp = "application_key={$applicationKey}format=jsonmethod={$method}{$fieldsQuery}" . $secretKey;

//     $sig = md5($imp);

//     return $sig;
// }




// function getGroupInfo ($groupArr, $fields, $secretKey, $applicationKey, $method, $methodUrl, $accessToken)
// {

//     $groupsIdArr = [];
//     foreach($groupArr as $value) {
//         $groupsIdArr[] = $value->groupId;
//     }

//     $groupsId = implode($groupsIdArr, ',');

//     $imp = "application_key={$applicationKey}fields={$fields}format=jsonmethod={$method}uids={$groupsId}" . $secretKey;

//     $sig = md5($imp);

//     $queryParams = 
//     [
//         'application_key' => $applicationKey,
//         'format' => 'json',
//         'method' => $method,
//         'sig' => $sig,
//         'access_token' => $accessToken,
//         'fields' => $fields,
//         'uids' => $groupsId,
//     ];

//     $file = json_decode(file_get_contents($methodUrl . http_build_query( $queryParams )));

//     return $file;
// }




// $fr2 = "application_key=CPKJMOJGDIHBABABAfields=feed.*,media_topic.*format=jsongid=60196527866082method=stream.getpatterns=POST";

// $md = md5("$fr2$seKe");

// echo '<br/>';
// $file = file_get_contents("https://api.ok.ru/fb.do?application_key={$applicationKey}&fields=feed.*,media_topic.*&format=json&gid=60196527866082&method=stream.get&patterns=POST&sig={$md}&access_token={$accessToken}");
// // var_dump ($_POST);
// var_dump (json_decode($file));