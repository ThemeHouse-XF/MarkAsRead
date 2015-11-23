<?php

class ThemeHouse_MarkAsRead_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_MarkAsRead' => array(
                'model' => array(
                    'XenForo_Model_Thread',
                    'XenForo_Model_Feed'
                ),
                'controller' => array(
                    'XenForo_ControllerPublic_Thread'
                ),
            ),
        );
    }

    public static function loadClassModel($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_MarkAsRead_Listener_LoadClass', $class, $extend, 'model');
    }

    public static function loadClassController($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_MarkAsRead_Listener_LoadClass', $class, $extend, 'controller');
    }
}