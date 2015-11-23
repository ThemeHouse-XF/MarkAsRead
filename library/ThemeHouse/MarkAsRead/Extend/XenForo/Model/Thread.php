<?php

/**
 *
 * @see XenForo_Model_Thread
 */
class ThemeHouse_MarkAsRead_Extend_XenForo_Model_Thread extends XFCP_ThemeHouse_MarkAsRead_Extend_XenForo_Model_Thread
{

    public function markThreadGlobalRead(array $thread, array $forum)
    {
        $db = $this->_getDb();
        
        $readDate = XenForo_Application::$time;
        
        /* @var $userModel XenForo_Model_User */
        $userModel = $this->getModelFromCache('XenForo_Model_User');
        
        $userIds = $userModel->getUserIds(array());
        
        $values = array();
        $rowLength = 0;
        $rows = array();
        foreach ($userIds as $userId) {
            $row = '(' . $userId . ',' . $thread['thread_id'] . ',' . $readDate . ')';
            
            $rows[] = $row;
            $rowLength += strlen($row);
            
            if ($rowLength > 500000) {
                $db->query(
                    '
					INSERT INTO xf_thread_read
						(user_id, thread_id, thread_read_date)
					VALUES
						' . implode(',', $rows) . '
        			ON DUPLICATE KEY UPDATE thread_read_date = VALUES(thread_read_date)
        		');
                
                $rows = array();
                $rowLength = 0;
            }
        }
        
        if ($rows) {
            $db->query(
                '
    			INSERT INTO xf_thread_read
    				(user_id, thread_id, thread_read_date)
    			VALUES
    				' . implode(',', $rows) . '
    			ON DUPLICATE KEY UPDATE thread_read_date = VALUES(thread_read_date)
    		');
        }
        
        if ($readDate < $thread['last_post_date']) {
            // we haven't finished reading this thread - forum won't be read
            return false;
        }
        
        XenForo_Application::defer('ThemeHouse_MarkAsRead_Deferred_MarkAsRead',
            array(
                'forum_id' => $forum['node_id']
            ), 'ThemeHouseMarkForumRead_' . $forum['node_id']);
        
        return true;
    }

    /**
     * Determines if the thread can be marked as read with the given
     * permissions.
     * This does not check forum viewing permissions.
     *
     * @param array $thread Info about the thread
     * @param array $forum Info about the forum the thread is in
     * @param string $errorPhraseKey Returned phrase key for a specific error
     * @param array|null $nodePermissions
     * @param array|null $viewingUser
     *
     * @return boolean
     */
    public function canMarkThreadRead(array $thread, array $forum, &$errorPhraseKey = '', array $nodePermissions = null, 
        array $viewingUser = null)
    {
        $this->standardizeViewingUserReferenceForNode($thread['node_id'], $viewingUser, $nodePermissions);
        
        if (!$viewingUser['user_id']) {
            return false;
        }
        
        return XenForo_Permission::hasContentPermission($nodePermissions, 'markThreadRead');
    }

    /**
     * Determines if the thread can be marked as read for everyone with the
     * given permissions.
     * This does not check forum viewing permissions.
     *
     * @param array $thread Info about the thread
     * @param array $forum Info about the forum the thread is in
     * @param string $errorPhraseKey Returned phrase key for a specific error
     * @param array|null $nodePermissions
     * @param array|null $viewingUser
     *
     * @return boolean
     */
    public function canMarkThreadGlobalRead(array $thread, array $forum, &$errorPhraseKey = '', 
        array $nodePermissions = null, array $viewingUser = null)
    {
        $this->standardizeViewingUserReferenceForNode($thread['node_id'], $viewingUser, $nodePermissions);
        
        return XenForo_Permission::hasContentPermission($nodePermissions, 'markThreadGlobalRead');
    }
}