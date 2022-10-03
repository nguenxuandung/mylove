<?php
$baseDir = dirname(dirname(__FILE__));

return [
    'plugins' => [
        'ADmad/SocialAuth' => $baseDir . '/vendor/admad/cakephp-social-auth/',
        'AdminlteAdminTheme' => $baseDir . '/plugins/AdminlteAdminTheme/',
        'AdminlteMemberTheme' => $baseDir . '/plugins/AdminlteMemberTheme/',
        'ClassicTheme' => $baseDir . '/plugins/ClassicTheme/',
        'CloudTheme' => $baseDir . '/plugins/CloudTheme/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'ModernTheme' => $baseDir . '/plugins/ModernTheme/',
        'Queue' => $baseDir . '/vendor/dereuromark/cakephp-queue/',
    ],
];
