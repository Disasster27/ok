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
        // получение массива групп, на обновления которых подписаны **из DB
        $group = file('subscribeGroups.txt');
        
        // проверка каждой группы на новые посты и комментарии
        if($group){
            foreach($group as $elem) {
                $stat = $this->getStat (trim($elem));
                if(count($stat)){
                    $this->validate ($stat);
                } else {
                    echo "Нет статистики по группе $elem";
                }
            }
        } else {
            echo "Нет подписанных групп.";
        }
        
    }

    // получение постов группы и количество комментов к каждому из них (start_time\end_time )
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

        var_dump ('getStat',$file);

        return $file->topics;
    }

    // определить новые посты и новые комментарии
    private function validate ($stat)
    {
        // DB
        // количество постов в группе на данный момент
        $newCount = count($stat);

        $topics = (array)(json_decode(file_get_contents('stat.txt')));
        
        // количество постов в группе при прошлом запросе
        $oldCount = count($topics);

        if($newCount > $oldCount){
            $count = $newCount - $oldCount;
            // берём новые посты
            $newPost = array_slice($stat, 0, $count);
            $this->getPost ($newPost);
            // добавить новые посты в stat
            $this->addNewTopics($newPost);
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

    // получает инфо о новых постах
    private function getPost ($newPost)
    {
        // собираем ID новых постов в строку для запроса
        $post = [];

        foreach ($newPost as $value) {
            $post[] = $value->id;
        }
        $post = implode($post, ',');

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

        var_dump ('getPost',$file->media_topics);

        return $file->media_topics;
    }

    // получение новых комментариев
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
            'count' => $count,
            'access_token' => $this->longToken,
            'discussionType' => 'GROUP_TOPIC',
            'discussionId' => $value->id,
        ];

        $file = $this->getContent ($queryParams);
        
        var_dump ('getComments',$file);

        $this->writeNewStat ($file);
        return $file;
    }

    // **объединить два метода
    // добавляет новые посты в отслеживание (stat)
    private function addNewTopics ($topics)
    {
        $group = (array) json_decode(file_get_contents('stat.txt'));

        foreach($topics as $elem){
            $group[$elem->id] = $elem->comments;
        }

        file_put_contents('stat.txt', json_encode($group));
    }

    // обновление счётчика комментариев
    private function writeNewStat ($discussion)
    {
        $topics = (array) json_decode(file_get_contents('stat.txt'));

        $topics[$discussion->discussionId] += count($discussion->comments);

        file_put_contents('stat.txt', json_encode($topics));
    }
}

// получать инфо об авторе поста\коммента

$rep = new Repeat();

$rep->repeat();

