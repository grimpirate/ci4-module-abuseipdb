<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Psr\Log\LoggerInterface;

use Modules\AbuseIpdb\Entities\ConfidenceEntity;
use Modules\AbuseIpdb\Models\ConfidenceModel;

class AbuseIpdbBlacklist extends BaseCommand
{
	public function __construct(LoggerInterface $logger, Commands $commands)
	{
		parent::__construct($logger, $commands);
		$this->group = lang('AbuseIpdb.spark.blacklist.group');
		$this->name = lang('AbuseIpdb.spark.blacklist.name');
		$this->description = lang('AbuseIpdb.spark.blacklist.description');
		$this->usage = lang('AbuseIpdb.spark.blacklist.usage');
		$this->arguments = [
			'ip' => lang('AbuseIpdb.spark.blacklist.arguments.ip'),
		];
	}

	public function run(array $params)
	{
		$ipAddress = !isset($params[0]) ? CLI::prompt(lang('AbuseIpdb.spark.blacklist.prompt.ip'), null, 'required') : $params[0];
        
        $json = null;
		try
		{
			$json = service('abuseipdb')->check($ipAddress);
		}
		catch(HTTPException $e)
		{
			// In case of an error do not block
			log_message('error', '[ERROR] {exception}', ['exception' => $e]);
			return false;
		}

		model(ConfidenceModel::class)->save(new ConfidenceEntity([
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $json['data']['abuseConfidenceScore'],
            'blacklist' => true,
		]));
	}
}