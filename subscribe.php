<?php
require_once("config.php");

var_dump ($_REQUEST);
var_dump (array_keys($_REQUEST));


echo '<a href="logout.php">Exit</a><br />';

class Subscribe extends Config
{
    public function subscribeGroups ($groups)
    {
        $file = 'subscribeGroups.txt';

        // var_dump (file('subscribeGroups.txt'));

        // $subscribeGroups = file('subscribeGroups.txt');

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

    private function checkForExistence ($group)
    {
        $subscribeGroups = file('subscribeGroups.txt');

        return array_search($group, $subscribeGroups);
    }

}

// group subscription 
if ($_REQUEST) {
    $groups = array_keys($_REQUEST);

    $subscribe = new Subscribe ();
    $subscribe->subscribeGroups ($groups);


    echo ("<a href='repeat.php'>stat<a/>");
}








    
// $fr2 = "application_key={$applicationKey}fields=feed.*,media_topic.*format=jsongid=60177729323234method=stream.getpatterns=COMMENT,POST";

// $md = md5("$fr2$secretKey");

// echo '<br/>';
// $file = file_get_contents("https://api.ok.ru/fb.do?application_key={$applicationKey}&fields=feed.*,media_topic.*&format=json&gid=60177729323234&method=stream.get&patterns=COMMENT,POST&sig={$md}&access_token=" . $_SESSION['token']);
// // var_dump ($_POST);
// var_dump (json_decode($file));

// COMMENTS,COMPLAINTS,CONTENT_OPENS,CREATED_MS,ENGAGEMENT,EXTERNAL_ID,FEEDBACK,FEEDBACK_TOTAL,HIDES_FROM_FEED,ID,LIKES,LINK_CLICKS,MUSIC_PLAYS,NEGATIVES,PROMO_FROM,PROMO_TO,REACH,REACH_EARNED,REACH_OWN,RENDERINGS,RENDERINGS_EARNED,RENDERINGS_OWN,RESHARES,VIDEO_PLAYS