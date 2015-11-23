<?php

/**
 *
 * @see XenForo_ControllerPublic_Thread
 */
class ThemeHouse_MarkAsRead_Extend_XenForo_ControllerPublic_Thread extends XFCP_ThemeHouse_MarkAsRead_Extend_XenForo_ControllerPublic_Thread
{

    /**
     *
     * @see XenForo_ControllerPublic_Thread::actionIndex()
     */
    public function actionIndex()
    {
        $response = parent::actionIndex();
        
        if ($response instanceof XenForo_ControllerResponse_View) {
            if (isset($response->params['thread']) && isset($response->params['forum'])) {
                $thread = $response->params['thread'];
                $forum = $response->params['forum'];
                
                $response->params['canMarkThreadRead'] = $this->_getThreadModel()->canMarkThreadRead($thread, $forum);
                $response->params['canMarkThreadGlobalRead'] = $this->_getThreadModel()->canMarkThreadGlobalRead(
                    $thread, $forum);
            }
        }
        
        return $response;
    }

    /**
     * Marks a thread as read.
     *
     * @return XenForo_ControllerResponse_Abstract
     */
    public function actionMarkAsRead()
    {
        $threadId = $this->_input->filterSingle('thread_id', XenForo_Input::UINT);
        
        $ftpHelper = $this->getHelper('ForumThreadPost');
        list($thread, $forum) = $ftpHelper->assertThreadValidAndViewable($threadId);
        
        $threadModel = $this->_getThreadModel();
        
        $global = $this->_input->filterSingle('global', XenForo_Input::UINT);
        
        $this->_assertCanMarkThreadRead($thread, $forum, $global);
        
        if ($this->isConfirmedPost()) {
            $readDate = XenForo_Application::$time;
            
            if ($global) {
                $threadModel->markThreadGlobalRead($thread, $forum, $readDate);
            } else {
                $threadModel->markThreadRead($thread, $forum, $readDate);
            }
            
            return $this->responseRedirect(XenForo_ControllerResponse_Redirect::SUCCESS, 
                XenForo_Link::buildPublicLink('threads', $thread), new XenForo_Phrase('th_thread_marked_as_read_markasread'));
        } else {
            return $this->responseView('ThemeHouse_MarkAsRead_ViewPublic_Thread_MarkAsRead',
                'th_mark_thread_read_confirm_markasread',
                array(
                    'thread' => $thread,
                    'forum' => $forum,
                    'nodeBreadCrumbs' => $ftpHelper->getNodeBreadCrumbs($forum),
                    
                    'global' => $global
                ));
        }
    }

    /**
     * Asserts that the currently browsing user can mark this thread as read.
     *
     * @param array $thread
     * @param array $forum
     * @param boolean $global Mark as thread for everyone
     */
    protected function _assertCanMarkThreadRead(array $thread, array $forum, $global)
    {
        if ($global) {
            if (!$this->_getThreadModel()->canMarkThreadGlobalRead($thread, $forum, $errorPhraseKey)) {
                throw $this->getErrorOrNoPermissionResponseException($errorPhraseKey);
            }
        } else {
            if (!$this->_getThreadModel()->canMarkThreadRead($thread, $forum, $errorPhraseKey)) {
                throw $this->getErrorOrNoPermissionResponseException($errorPhraseKey);
            }
        }
    }
}