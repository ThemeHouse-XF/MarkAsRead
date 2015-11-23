<?php

/**
 *
 * @see XenForo_Model_Feed
 */
class ThemeHouse_MarkAsRead_Extend_XenForo_Model_Feed extends XFCP_ThemeHouse_MarkAsRead_Extend_XenForo_Model_Feed
{

    protected function _insertFeedEntry(array $entryData, array $feedData, array $feed)
    {
        $threadId = parent::_insertFeedEntry($entryData, $feedData, $feed);
        
        if (!empty($feed['node_id'])) {
            $xenOptions = XenForo_Application::get('options');
            
            if ($xenOptions->th_markAsRead_markFeedsRead) {
                /* @var $threadModel XenForo_Model_Thread */
                $threadModel = $this->getModelFromCache('XenForo_Model_Thread');
                
                $thread = $threadModel->getThreadById($threadId);
                if ($thread) {
                    $readDate = XenForo_Application::$time;
                    
                    $threadModel->markThreadGlobalRead($thread, $thread, $readDate);
                }
            }
        }
        
        return $threadId;
    }
}