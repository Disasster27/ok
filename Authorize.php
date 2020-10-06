<?php

require_once("config.php");
require_once("UserGroups.php");

class Authorize extends Config
{
    // **как вызывать в скрипте при ошибке токена
    // убрать повторы
    public function authorized ()
    {
        // ** методы работы с DB
        if (!file_get_contents('longToken.txt')) {
            if (file_get_contents('refresh.txt')) {

                $this->refreshToken ();

                // **для пробы
                echo ("<a href='logout.php'>Exit</a>
                    <br/>
                    <p>Выберите группу(ы).</p>");
        
        
                $userGroups  = new UserGroups();
                $userGroups ->getUserGroups();
        
                echo ("<a href='repeat.php'>stat<a/>");
        
            } else {
                // Получаем token по code
                echo ("<a href='" . $this->getAuthorizeUrl () . "'>Авторизация через OK.RU</a>");
        
            }
        } else {

            echo ("<a href='index.php'>Exit</a>
                    <br/>
                    <p>Выберите группу(ы).</p>");
        
            $userGroups  = new UserGroups();
            $userGroups ->getUserGroups();
        
            echo "<a href='repeat.php'>stat<a/>";
        }

    }

    public function getCode ($code)
    {
        // Формируем ссылку для POST запроса на получение access_token
        $paramsToken = array(
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'code' => $code,
            'grant_type' => 'authorization_code', 
            'client_secret' => file_get_contents('clientSecret.txt'),
        );


        $url = $this->tokenUrl . http_build_query( $paramsToken );

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        $result = json_decode(curl_exec($ch));

        curl_close($ch);

        $refreshToken = $result->refresh_token;
        $accessToken = $result->access_token;

        $handleToken = fopen('token.txt', 'w');
        fwrite($handleToken, $accessToken);
        fclose($handleToken);

        $handleRefresh = fopen('refresh.txt', 'w');
        fwrite($handleRefresh, $refreshToken);
        fclose($handleRefresh);

        // **
        echo '<a href="logout.php">Exit</a><br />';
        echo '<p>Выберите группу для подключения</p><br />';

        $userGroups  = new UserGroups();
        $userGroups ->getUserGroups();

        // для пробы
        echo ("<a href='repeat.php'>stat<a/>");
    }

    // Получаем новый token по refresh 
    private function refreshToken ()
    {
        $paramsRefresh = array(
            'client_id'     => $this->clientId,
            'refresh_token' => file_get_contents('refresh.txt'),
            'grant_type' => 'refresh_token', 
            'client_secret' => $this->clientSecret,
        );

        $url = $this->tokenUrl . http_build_query( $paramsRefresh );

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        $result = curl_exec($ch);

        curl_close($ch);

        $accessToken = json_decode($result)->access_token;

        $handleToken = fopen('token.txt', 'w');
        fwrite($handleToken, $accessToken);
        fclose($handleToken);
    }
     

    // Формирование ссылки для авторизации пользователя
    private function getAuthorizeUrl ()
    {
        $params = [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'layout' => 'w', 
            'scope'         => $this->scope,
        ];

        return $this->authorizeUrl . http_build_query( $params );
    }
}