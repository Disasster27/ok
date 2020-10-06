<?php
require_once("config.php");

echo '<a href="logout.php">Exit</a><br />';

class Subscribe extends Config
{
    public function subscribeGroups ($groups)
    {
        // ** запись в DB
        $file = 'subscribeGroups.txt';

        foreach ($groups as $value) {

            if ($this->checkForExistence($value) === FALSE) {
                $text = $value . PHP_EOL;
                file_put_contents($file, $text, FILE_APPEND);
                echo "Subscribe group $value";
            } else {
                echo("$value already subscribe");
            }
        }
    }

    // проверка на то, что группа уже подписана
    private function checkForExistence ($group)
    {
        $subscribeGroups = file('subscribeGroups.txt');

        return array_search($group, $subscribeGroups);
    }

    // создание Webhook для группы
    private function subscribeWebhook ()
    {
        // нужно получать параметром
        $token = 'tkn1U9dvFMQH5vs3zDAYszK6cEdRGxmHSvZErEogSP2x5tNHiESP5bcOs4r1cIs4ctFE2:CKOMIMJGDIHBABABA';

        $url = 'https://api.ok.ru/graph/me/subscribe?access_token='.$token;
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                // куда будут доставляться сообщения о событиях в чатах
                'content' => '{"url": "http://ok/webhook.php"}'
            )
        )));
        print_r($result);
    }

}

// **вынести логику

// group subscription 
if ($_REQUEST) {
    var_dump ($_REQUEST);
    $groups = array_keys($_REQUEST);

    $subscribe = new Subscribe ();
    $subscribe->subscribeGroups ($groups);


    echo ("<a href='repeat.php'>stat<a/>");
}
