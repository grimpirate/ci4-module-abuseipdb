<?php

namespace Modules\AbuseIpdb\Services;

use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\HTTP\CURLRequest;

use Modules\AbuseIpdb\Models\ConfidenceModel;

class AbuseIpdb
{
	protected ConfidenceModel $model;
	protected CURLRequest $client;

	public function __construct()
	{
		$this->model = model(ConfidenceModel::class);
		$this->client = service('curlrequest', [
			'baseURI' => 'https://api.abuseipdb.com/api/v2/',
			'headers' => [
				'Accept' => 'application/json',
				'Key' => setting('AbuseIpdb.apiKey'),
			],
		]);
	}

	public function block(string $ipAddress): bool
	{
		if($this->model->ok($ipAddress)) return false;

		$response = null;
		try
		{
			$response = $this->client->request('GET', 'check', [
				'query' => [
					'ipAddress' => $ipAddress,
					'maxAgeInDays' => setting('AbuseIpdb.maxAgeInDays'),
				]
			]);
		}
		catch(HTTPException $e)
		{
			// In case of an error do not block
			log_message('error', '[ERROR] {exception}', ['exception' => $e]);
			return false;
		}

		$json = json_decode($response->getBody(), true);

		// In case of an error do not block
		if(array_key_exists('errors', $json))
		{
			log_message('error', '[ERROR] Modules\AbuseIpdb: {status} {detail}', $json['errors'][0]);
			return false;
		}

		$this->model->store($ipAddress, $json['data']['abuseConfidenceScore']);

		return $json['data']['abuseConfidenceScore'] > setting('AbuseIpdb.abuseConfidenceScore');
	}
}