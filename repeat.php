<?php
require_once("config.php");

echo ("<a href='index.php'>Exit</a>");

class Repeat extends Config
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function repeat ()
    {
        $group = file('subscribeGroups.txt');
        
        foreach($group as $elem) {
            $stat = $this->getStat (trim($elem));
            $this->validate ($stat);
        }
        
    }

    private function getStat ($group) 
    {

        $imp = "application_key={$this->applicationKey}fields=COMMENTS,IDformat=jsongid={$group}method=group.getStatTopics" . $this->secretKey;
        $sig = md5($imp);


        $queryParams = 
        [
            'application_key' => $this->applicationKey,
            'format' => 'json',
            'method' => 'group.getStatTopics',
            'sig' => $sig,
            'access_token' => $this->longToken,
            'fields' => 'COMMENTS,ID',
            'gid' => $group,
        ];

        $file = $this->getContent ($queryParams);

        var_dump ($file);
        return $file->topics;
    }

    private function validate ($stat)
    {
        // var_dump ($stat);
        $newCount = count($stat);
        // var_dump ($newCount);


        // $statistik[$stat[0]->id] = $stat[0]->comments;
        // $statistik[$stat[1]->id] = $stat[1]->comments;
        // $statistik = json_encode($statistik);
        // var_dump($statistik);

        // $handleStat = fopen('stat.txt', 'w');
        // fwrite($handleStat, $statistik);
        // fclose($handleStat);
        // echo $statistik;
// die;
        $topics = (array)(json_decode(file_get_contents('stat.txt')));
        
        var_dump ($topics);
        $oldCount = count($topics);
        var_dump ($oldCount);

        if($newCount > $oldCount){
            $count = $newCount - $oldCount;
            $output = array_slice($stat, 0, $count);
            $this->getPost ($output);
        } 

        // Если счетчик комментов увеличился, то вернуть новый комментарии  
        foreach($stat as $value) {
            foreach ($topics as $key => $elem) {
                if($value->id == $key){
                    if($value->comments > $elem){
                        $this->getComments ($value, $elem);
                    }
                }
            }
        }
    }

    private function getPost ($newPost)
    {

        $post = [];
        foreach ($newPost as $value) {
            $post[] = $value->id;
        }
        $post = implode($post, ',');
        var_dump ($post);

        $imp = "application_key={$this->applicationKey}fields=media_topic.*format=jsonmethod=mediatopic.getByIdstopic_ids={$post}" . $this->secretKey;

        $sig = md5($imp);


        $queryParams = 
        [
            'application_key' => $this->applicationKey,
            'format' => 'json',
            'method' => 'mediatopic.getByIds',
            'sig' => $sig,
            'access_token' => $this->longToken,
            'fields' => 'media_topic.*',
            'topic_ids' => $post,
        ];

        $file = $this->getContent ($queryParams);
        // добавить новые посты в stat
        return $file->media_topics;
    }

    private function getComments ($value, $elem)
    {

        $count = $value->comments - $elem;

        $imp = "application_key={$this->applicationKey}count={$count}discussionId={$value->id}discussionType=GROUP_TOPICformat=jsonmethod=discussions.getComments" . $this->secretKey;
        $sig = md5($imp);

        $queryParams = 
        [
            'application_key' => $this->applicationKey,
            'format' => 'json',
            'method' => 'discussions.getComments',
            'sig' => $sig,
            'count' => 1,
            'access_token' => $this->longToken,
            'discussionType' => 'GROUP_TOPIC',
            'discussionId' => $value->id,
        ];


        $file = json_decode(file_get_contents($this->methodUrl . http_build_query( $queryParams )));

        var_dump ($file);
 
        $this->writeNewStat ($file);
        return $file;
    }

    private function writeNewStat ($discussion)
    {
        $topics = (array)(json_decode(file_get_contents('stat.txt')));

        $topics[$discussion->discussionId] += count($discussion->comments);

        // var_dump ($topics);

        $statistik = json_encode($topics);

        // var_dump($statistik);

        $handleStat = fopen('stat.txt', 'w');
        fwrite($handleStat, $statistik);
        fclose($handleStat);
    }
}

$rep = new Repeat();

// while (1){
//     sleep(3);
//     $rep->repeat();
// }

$rep->repeat();

