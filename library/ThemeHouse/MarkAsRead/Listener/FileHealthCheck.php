<?php

class ThemeHouse_MarkAsRead_Listener_FileHealthCheck
{

    public static function fileHealthCheck(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes = array_merge($hashes,
            array(
                'library/ThemeHouse/MarkAsRead/Deferred/MarkAsRead.php' => 'cd7976cf0c70f578b45392f32025d63a',
                'library/ThemeHouse/MarkAsRead/Extend/XenForo/ControllerPublic/Thread.php' => '526d14cbb13e0618a7793a76b4e5ddb8',
                'library/ThemeHouse/MarkAsRead/Extend/XenForo/Model/Feed.php' => '93f077fc853bfa5480a1780ef4f4e192',
                'library/ThemeHouse/MarkAsRead/Extend/XenForo/Model/Thread.php' => 'b283bb1efa0f40dda7d71e39ec78b4fa',
                'library/ThemeHouse/MarkAsRead/Install/Controller.php' => '41ae40171eee2043f120c1d834b3bfc7',
                'library/ThemeHouse/MarkAsRead/Listener/LoadClass.php' => 'c130eb3ec9a71c47f0add1f9132d0e53',
                'library/ThemeHouse/Install.php' => '18f1441e00e3742460174ab197bec0b7',
                'library/ThemeHouse/Install/20151109.php' => '2e3f16d685652ea2fa82ba11b69204f4',
                'library/ThemeHouse/Deferred.php' => 'ebab3e432fe2f42520de0e36f7f45d88',
                'library/ThemeHouse/Deferred/20150106.php' => 'a311d9aa6f9a0412eeba878417ba7ede',
                'library/ThemeHouse/Listener/ControllerPreDispatch.php' => 'fdebb2d5347398d3974a6f27eb11a3cd',
                'library/ThemeHouse/Listener/ControllerPreDispatch/20150911.php' => 'f2aadc0bd188ad127e363f417b4d23a9',
                'library/ThemeHouse/Listener/InitDependencies.php' => '8f59aaa8ffe56231c4aa47cf2c65f2b0',
                'library/ThemeHouse/Listener/InitDependencies/20150212.php' => 'f04c9dc8fa289895c06c1bcba5d27293',
                'library/ThemeHouse/Listener/LoadClass.php' => '5cad77e1862641ddc2dd693b1aa68a50',
                'library/ThemeHouse/Listener/LoadClass/20150518.php' => 'f4d0d30ba5e5dc51cda07141c39939e3',
            ));
    }
}