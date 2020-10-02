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
        'client_secret' => file_get_contents('clientSecret.txt'),
    );


    $url = $tokenUrl . http_build_query( $paramsT );

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);

    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

    $result = curl_exec($ch);

    curl_close($ch);

    $refreshToken = json_decode($result)->refresh_token;
    $accessToken = json_decode($result)->access_token;

    $handleToken = fopen('token.txt', 'w');
    fwrite($handleToken, $accessToken);
    fclose($handleToken);

    $handleRefresh = fopen('refresh.txt', 'w');
    fwrite($handleRefresh, $refreshToken);
    fclose($handleRefresh);

    echo '<a href="logout.php">Exit</a><br />';
    echo '<p>Выберите группу для подключения</p><br />';

    $userGroups  = new UserGroups();
    $userGroups ->getUserGroups();

    echo ("<a href='repeat.php'>stat<a/>");
};