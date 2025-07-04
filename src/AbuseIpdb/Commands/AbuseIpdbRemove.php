<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;

class AbuseIpdbRemove extends BaseCommand
{
    protected $group = 'AbuseIpdb';
    protected $name = 'abuseipdb:remove';
    protected $description = 'Remove a blocked ip from the .htaccess file';
    protected $usage = 'abuseipdb:remove <ip>';
    protected $arguments = [
        'ip' => 'Internet Protocol Address',
    ];

    public function run(array $params)
    {
        $ipAddress = !isset($params[0]) ? CLI::prompt("IP address", null, 'required') : $params[0];
        $publisher = new Publisher(FCPATH);
	    $publisher->replace(
		    '.htaccess',
		    [
                "\tRequire not ip {$ipAddress}\n" => '',
            ]
	    );
    }
}