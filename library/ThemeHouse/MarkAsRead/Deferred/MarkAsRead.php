<?php

class ThemeHouse_MarkAsRead_Deferred_MarkAsRead extends XenForo_Deferred_Abstract
{

    public function execute(array $deferred, array $data, $targetRunTime, &$status)
    {
        $data = array_merge(array(
            'position' => 0,
            'batch' => 1000
        ), $data);
        $data['batch'] = max(1, $data['batch']);
        
        if (!isset($data['forum_id'])) {
            return true;
        }
        
        /* @var $userModel XenForo_Model_User */
        $userModel = XenForo_Model::create('XenForo_Model_User');
        
        /* @var $forumModel XenForo_Model_Forum */
        $forumModel = XenForo_Model::create('XenForo_Model_Forum');
        
        $forum = $forumModel->getForumById($data['forum_id']);
        
        $userIds = $userModel->getUserIdsInRange($data['position'], $data['batch']);
        if (sizeof($userIds) == 0) {
            return true;
        }
        
        $startTime = microtime(true);
        
        foreach ($userIds as $userId) {
            $data['position'] = $userId;
            
            $user = $userModel->getUserById($userId, array(
                'join' => XenForo_Model_User::FETCH_USER_PROFILE
            ));
            
            $forumModel->markForumReadIfNeeded($forum, $user);
            
            if ($targetRunTime && (microtime(true) - $startTime) > $targetRunTime) {
                break;
            }
        }
        
        return $data;
    }
}