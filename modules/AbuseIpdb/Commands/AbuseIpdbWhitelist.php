<?php

namespace Modules\AbuseIpdb\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Psr\Log\LoggerInterface;

namespace Modules\AbuseIpdb\Models\ConfidenceModel;

class AbuseIpdbWhitelist extends BaseCommand
{
	public function __construct(LoggerInterface $logger, Commands $commands)
	{
		parent::__construct($logger, $commands);
		$this->group = lang('AbuseIpdb.spark.whitelist.group');
		$this->name = lang('AbuseIpdb.spark.whitelist.name');
		$this->description = lang('AbuseIpdb.spark.whitelist.description');
		$this->usage = lang('AbuseIpdb.spark.whitelist.usage');
		$this->arguments = [
			'ip' => lang('AbuseIpdb.spark.whitelist.arguments.ip'),
		];
	}

	public function run(array $params)
	{
		$ipAddress = !isset($params[0]) ? CLI::prompt(lang('AbuseIpdb.spark.whitelist.prompt.ip'), null, 'required') : $params[0];
        
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

		model(ConfidenceModel::class)->save([
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $json['data']['abuseConfidenceScore'],
            'whitelisted' => true,
		]);
	}
}