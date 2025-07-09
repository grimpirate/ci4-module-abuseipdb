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
		'setup' => [
			'group' => 'AbuseIpdb',
			'name' => 'abuseipdb:setup',
			'description' => 'Intial module setup',
			'usage' => 'abuseipdb:setup',
			'arguments' => [
				'key' => 'AbuseIpdb API key',
			],
			'prompt' => [
				'key' => "API key",
			],
		],
		'whitelist' => [
			'group' => 'AbuseIpdb',
			'name' => 'abuseipdb:whitelist',
			'description' => 'Exempt an ip from filtering',
			'usage' => 'abuseipdb:whitelist <ip>',
			'arguments' => [
				'ip' => 'Internet Protocol Address',
			],
			'prompt' => [
				'ip' => "IP address",
			],
		],
	],
	'htaccess' => [
		'add' => "\tRequire not ip {ip}",
		'remove' => "\tRequire not ip {ip}\n",
	],
];