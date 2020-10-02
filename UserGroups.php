<?php

class UserGroups extends Config
{

    public function getUserGroups ()
    {

        $groupsArr = $this->getGroups();

        $userGroups = $this->getGroupInfo($groupsArr);

        $this->render ($userGroups);
    }

    // Возвращает ID групп, в которых пользователь ADMIN  
    private function getGroups() : array
    {
        $imp = "application_key={$this->applicationKey}format=jsonmethod=group.getUserGroupsV2" . $this->secretKey;
        $sig = md5($imp);

        $queryParams = 
        [
            'application_key' => $this->applicationKey,
            'format' => 'json',
            'method' => 'group.getUserGroupsV2',
            'sig' => $sig,
            'access_token' => $this->longToken,
        ];

        // Получение всех групп пользователя
        $file = $this->getContent ($queryParams);

        // Сортировка по статусу ADMIN
        $resArr = array_filter($file->groups, function ($elem) {

            if($elem->status == 'ADMIN') {
                return $elem;
            };
        });
      
        return $resArr;
    }

    // Возвращает ID и названием групп администрируемых пользователем
    private function getGroupInfo ($groupArr) : array
    {

        $groupsIdArr = [];

        foreach($groupArr as $value) {
            $groupsIdArr[] = $value->groupId;
        }

        $groupsId = implode($groupsIdArr, ',');

        $imp = "application_key={$this->applicationKey}fields=NAME,UIDformat=jsonmethod=group.getInfouids={$groupsId}" . $this->secretKey;

        $sig = md5($imp);

        $queryParams = 
        [
            'application_key' => $this->applicationKey,
            'format' => 'json',
            'method' => 'group.getInfo',
            'sig' => $sig,
            'access_token' => $this->longToken,
            'fields' => 'NAME,UID',
            'uids' => $groupsId,
        ];

        $file = $this->getContent ($queryParams);

        return $file;
    }

    private function render ($userGroups)
    {
        echo ("<form action='subscribe.php' method='post'>");
        foreach($userGroups as $elem) {
            echo ("<div>
                    <input type='checkbox' id='{$elem->uid}' name='{$elem->uid}'>
                    <label for='{$elem->uid}'>{$elem->name}</label>
                    </div>" . '<br/>');
        }
        echo ('<input type="submit" value="send"><br/>
        </form>');
    }
}