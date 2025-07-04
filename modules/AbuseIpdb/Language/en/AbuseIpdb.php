<?php

return [
    // Spark commands
    'spark' => [
        'add' => [
            'group' => 'AbuseIpdb',
            'name' => 'abuseipdb:add',
            'description' => 'Append a blocked ip to the .htaccess file',
            'usage' => 'abuseipdb:add <ip>',
            'arguments' => [
                'ip' => 'Internet Protocol Address',
            ],
            'prompt' => [
                'ip' => "IP address",
            ],
        ],
        'remove' => [
            'group' => 'AbuseIpdb',
            'name' => 'abuseipdb:remove',
            'description' => 'Remove a blocked ip from the .htaccess file',
            'usage' => 'abuseipdb:remove <ip>',
            'arguments' => [
                'ip' => 'Internet Protocol Address',
            ],
            'prompt' => [
                'ip' => "IP address",
            ],
        ],
    ],
    'htaccess' => "\tRequire not ip {ip}",
];