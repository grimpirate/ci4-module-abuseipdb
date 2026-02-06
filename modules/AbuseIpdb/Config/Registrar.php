<?php

namespace Modules\AbuseIpdb\Config;

class Registrar
{
    public static function UserAgents(): array
    {
        return [
            'robots' => [
                'censys'        => 'Censys',
                'paloalto'      => 'Palo Alto',
                'hawaiibot'     => 'HawaiiBot',
                'python'        => 'Python',
                'facebook'      => 'facebook',
                'cms-checker'   => 'CMS-Checker',
                'scrapy'        => 'Scrapy',
                'wanscannerbot' => 'WanScannerBot',
                'gptbot'        => 'GPTBot',
                'applebot'      => 'Applebot',
                'ort'           => 'ORTc.me',
            ],
        ];
    }
}