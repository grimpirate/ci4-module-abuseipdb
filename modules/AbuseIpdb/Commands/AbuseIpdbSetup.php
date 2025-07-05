<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use CodeIgniter\Publisher\Publisher;
use Psr\Log\LoggerInterface;

class AbuseIpdbSetup extends BaseCommand
{
    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        $this->group = lang('AbuseIpdb.spark.setup.group');
        $this->name = lang('AbuseIpdb.spark.setup.name');
        $this->description = lang('AbuseIpdb.spark.setup.description');
        $this->usage = lang('AbuseIpdb.spark.setup.usage');
        $this->arguments = [
            'key' => lang('AbuseIpdb.spark.setup.arguments.key'),
        ];
    }

    public function run(array $params)
    {
        $publisher = new Publisher();

        $publisher->addLineBefore(
            APPPATH . 'Config/Database.php',
            "\tpublic array \$abuseipdb = [\n\t\t'database'    => 'abuseipdb.db',\n\t\t'DBDriver'    => 'SQLite3',\n\t];\n",
            'public function __construct()'
        );

        $publisher->addLineBefore(
            APPPATH . 'Config/Filters.php',
            "use Modules\\AbuseIpdb\\Filters\\AbuseIpdb;\n",
            'class Filters extends BaseFilters'
        );

        $publisher->addLineAfter(
            APPPATH . 'Config/Filters.php',
            "\t\t'abuseipdb' => AbuseIpdb::class,",
            'public array $aliases = ['
        );

        $publisher->addLineBefore(
            APPPATH . 'Config/Filters.php',
            "\t\t\t'abuseipdb',",
            "'forcehttps',"
        );

        $apiKey = !isset($params[0]) ? CLI::prompt(lang('AbuseIpdb.spark.setup.prompt.key'), null, 'required') : $params[0];

        $publisher = new Publisher();
        $publisher->replace(
            ROOTPATH . 'modules/AbuseIpdb/Config/AbuseIpdb.php',
            [
                '    public string $apiKey            = 'YOUR_ABUSEIPDB_API_KEY';' => "    public string \$apiKey            = '{$apiKey}';",
            ]
        );
        $publisher = new Publisher(FCPATH);
	    $publisher->addLineBefore(
		    '.htaccess',
		    "# Block IPs\n<RequireAll>\n\tRequire all granted\n</RequireAll>\n",
		    '# Disable directory browsing'
	    );

        command('db:create abuseipdb --ext db');
        command('migrate -n Modules\AbuseIpdb -g abuseipdb');
    }
}