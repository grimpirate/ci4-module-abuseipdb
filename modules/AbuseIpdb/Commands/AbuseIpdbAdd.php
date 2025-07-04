<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use CodeIgniter\Publisher\Publisher;
use Psr\Log\LoggerInterface;

class AbuseIpdbAdd extends BaseCommand
{
    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        $this->group = lang('AbuseIpdb.spark.add.group');
        $this->name = lang('AbuseIpdb.spark.add.name');
        $this->description = lang('AbuseIpdb.spark.add.description');
        $this->usage = lang('AbuseIpdb.spark.add.usage');
        $this->arguments = [
            'ip' => lang('AbuseIpdb.spark.add.arguments.ip'),
        ];
    }

    public function run(array $params)
    {
        $ipAddress = !isset($params[0]) ? CLI::prompt(lang('AbuseIpdb.spark.add.prompt.ip'), null, 'required') : $params[0];
        $publisher = new Publisher(FCPATH);
	    $publisher->addLineBefore(
		    '.htaccess',
		    lang('AbuseIpdb.htaccess', ['ip' => $ipAddress]),
		    '</RequireAll>'
	    );
    }
}