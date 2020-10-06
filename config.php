<?php


class Config
{
    protected $clientId = '512000641198';
    protected $scope = 'VALUABLE_ACCESS;GROUP_CONTENT';
    protected $clientSecret = '7649E37AB9547FD1E16AD2E3';
    protected $applicationKey = 'CPKJMOJGDIHBABABA';
    protected $redirectUri = 'http://ok/index.php';
    protected $authorizeUrl = 'https://connect.ok.ru/oauth/authorize?';
    protected $methodUrl = 'https://api.ok.ru/fb.do?';
    protected $tokenUrl = 'https://api.ok.ru/oauth/token.do?';
    protected $longToken;
    protected $secretKey;

    public function __construct ()
    {  
        $this->clientSecret = file_get_contents('clientSecret.txt');
        $this->longToken = file_get_contents('longToken.txt') ? file_get_contents('longToken.txt') : file_get_contents('token.txt');
        $this->secretKey = md5($this->longToken . $this->clientSecret);
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
        $this->longToken =  file_get_contents('token.txt');
        $this->secretKey = md5($this->longToken . $this->clientSecret);
    }
}
