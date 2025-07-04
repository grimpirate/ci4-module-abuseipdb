<?php

namespace Modules\AbuseIpdb\Services;

use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\HTTP\CURLRequest;

use Modules\AbuseIpdb\Config\AbuseIpdb;
use Modules\AbuseIpdb\Entities\ConfidenceEntity;
use Modules\AbuseIpdb\Models\ConfidenceModel;

class AbuseIpdb
{
	protected ConfidenceModel $model;
	protected CURLRequest $client;
	protected AbuseIpdb $config;

	public function __construct()
	{
		$this->config = config(AbuseIpdb::class);
		$this->model = model(ConfidenceModel::class);
		$this->client = service('curlrequest', [
			'baseURI' => 'https://api.abuseipdb.com/api/v2/',
			'headers' => [
				'Accept' => 'application/json',
				'Key' => $this->config->apiKey,
			],
		]);
	}

	public function block(string $ipAddress): bool
	{
		if($this->model->ok($ipAddress)) return false;

		$json = null;
		try
		{
			$json = $this->check($ipAddress);
		}
		catch(HTTPException $e)
		{
			// In case of an error do not block
			log_message('error', '[ERROR] {exception}', ['exception' => $e]);
			return false;
		}

		$this->model->save(new ConfidenceEntity([
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $json['data']['abuseConfidenceScore'],
		]));

		if($json['data']['abuseConfidenceScore'] <= $this->config->abuseConfidenceScore) return false;

		command("abuseipdb:add {$ipAddress}");

		return true;
	}

	private function check(string $ipAddress): array
	{
		$response = $this->client->request('GET', 'check', [
			'query' => [
				'ipAddress' => $ipAddress,
				'maxAgeInDays' => $this->config->maxAgeInDays,
			]
		]);

		$json = json_decode($response->getBody(), true);

		if(array_key_exists('errors', $json))
			throw new HTTPException('API response contains errors');

		return $json;
	}
}