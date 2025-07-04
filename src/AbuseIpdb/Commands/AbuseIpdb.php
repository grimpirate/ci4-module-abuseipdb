<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;

class AbuseIpdb extends BaseCommand
{
    protected $group = 'AbuseIpdb';
    protected $name = 'abuseipdb:add';
    protected $description = 'Append a blocked ip to the .htaccess file';
    protected $usage = 'abuseipdb:add <ip>';
    protected $arguments = [
        'ip' => 'Internet Protocol Address',
    ];

    public function run(array $params)
    {
        $ipAddress = !isset($params[0]) ? CLI::prompt(lang('TOTP.spark.totp.input.id'), null, 'required') : $params[0];
        $publisher = new Publisher(FCPATH);
	    $publisher->addLineBefore(
		    '.htaccess',
		    "\tRequire not ip {$ipAddress}",
		    '</RequireAll>'
	    );
    }
}