<?php

$clientId = '512000641198';
$scope = 'VALUABLE_ACCESS;GROUP_CONTENT';
$clientSecret = '7649E37AB9547FD1E16AD2E3';
$applicationKey = 'CPKJMOJGDIHBABABA';
$redirectUri = 'http://ok/login.php';
$authorizeUrl = 'https://connect.ok.ru/oauth/authorize?';
$methodUrl = 'https://api.ok.ru/fb.do?';
$tokenUrl = 'https://api.ok.ru/oauth/token.do?';
$longToken = file_get_contents('longToken.txt');
$secretKey = md5($longToken . $clientSecret);

class Config
{
    protected $clientId = '512000641198';
    protected $scope = 'VALUABLE_ACCESS;GROUP_CONTENT';
    protected $clientSecret = '7649E37AB9547FD1E16AD2E3';
    protected $applicationKey = 'CPKJMOJGDIHBABABA';
    protected $redirectUri = 'http://ok/login.php';
    protected $authorizeUrl = 'https://connect.ok.ru/oauth/authorize?';
    protected $methodUrl = 'https://api.ok.ru/fb.do?';
    protected $tokenUrl = 'https://api.ok.ru/oauth/token.do?';
    protected $longToken;
    protected $secretKey;

    public function __construct ()
    {
        
        $this->longToken = file_get_contents('longToken.txt') ? file_get_contents('longToken.txt') : file_get_contents('token.txt');
        $this->secretKey = md5($this->longToken . $this->clientSecret);
        // var_dump ("qqqq" . $this->longToken);
    }

    protected function getContent ($params)
    {
        $file = json_decode(file_get_contents($this->methodUrl . http_build_query( $params )));

        if($file->error_code == 102){
            $this->refreshToken ();
            $file = json_decode(file_get_contents($this->methodUrl . http_build_query( $params )));
        }

        return $file;
    }

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
}

session_start();