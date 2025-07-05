<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Psr\Log\LoggerInterface;

use Modules\AbuseIpdb\Publisher\Publisher;

class AbuseIpdbRemove extends BaseCommand
{
    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        $this->group = lang('AbuseIpdb.spark.remove.group');
        $this->name = lang('AbuseIpdb.spark.remove.name');
        $this->description = lang('AbuseIpdb.spark.remove.description');
        $this->usage = lang('AbuseIpdb.spark.remove.usage');
        $this->arguments = [
            'ip' => lang('AbuseIpdb.spark.remove.arguments.ip'),
        ];
    }

    public function run(array $params)
    {
        $ipAddress = !isset($params[0]) ? CLI::prompt(lang('AbuseIpdb.spark.remove.prompt.ip'), null, 'required') : $params[0];
        $publisher = new Publisher();
	    $publisher->replace(
		    FCPATH . '.htaccess',
		    [
                lang('AbuseIpdb.htaccess.remove', ['ip' => $ipAddress]) => '',
            ]
	    );
    }
}