<?php
require_once("config.php");

var_dump ($_REQUEST);
var_dump (array_keys($_REQUEST));


echo '<a href="logout.php">Exit</a><br />';

class Subscribe extends Config
{
    public function subscribeGroups ($groups)
    {
        var_dump (file('subscribeGroups.txt'));
        $subscribeGroups = file('subscribeGroups.txt');

        foreach ($groups as $value) {

            foreach ($subscribeGroups as $elem) {
                if ($value == $elem) {
                    echo("already subscribe");
                    return;
                }    
            }
            $handle = fopen('subscribeGroups.txt', 'a');
            fwrite($handle, $value);
            fclose($handle);
            echo "Subscribe group $value";
        }
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