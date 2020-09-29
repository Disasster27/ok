<?php

require_once("config.php");
require_once("UserGroups.php");

class Authorize extends Config
{
    public function authorized ()
    {
        if (file_exists('longToken.txt') && !file_get_contents('longToken.txt')) {
            if (file_get_contents('refresh.txt')) {

                $this->refreshToken ();

                echo ("<a href='index.php'>Exit</a>
                    <br/>
                    <p>Выберите группу(ы).</p>");
        
        
                $userGroups  = new UserGroups();
                $userGroups ->getUserGroups();
        
                echo ("<a href='repeat.php'>stat<a/>");
        
            } else {
        //         // Получаем token по code
                echo ("<a href='" . getAuthorizeUrl ($clientId, $redirectUri, $scope, $authorizeUrl) . "'>Авторизация через OK.RU</a>");
        
            }
        } else {
            // $longToken = file_get_contents('longToken.txt');
            echo ("<a href='index.php'>Exit</a>
                    <br/>
                    <p>Выберите группу(ы).</p>");
        
            $userGroups  = new UserGroups();
            $userGroups ->getUserGroups();
        
            echo "<a href='repeat.php'>stat<a/>";
        }

    }

    // Получаем новый token по refresh 
    private function refreshToken ()
    {
        $paramsRe = array(
            'client_id'     => $this->clientId,
            'refresh_token' => file_get_contents('refresh.txt'),
            'grant_type' => 'refresh_token', 
            'client_secret' => $this->clientSecret,
        );

        $url = $this->tokenUrl . http_build_query( $paramsRe );

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
    private function getAuthorizeUrl ($clientId, $redirectUri, $scope, $authorizeUrl)
    {
        $params = [
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'layout' => 'w', 
            'scope'         => $scope,
        ];

        return $authorizeUrl . http_build_query( $params );
    }
}