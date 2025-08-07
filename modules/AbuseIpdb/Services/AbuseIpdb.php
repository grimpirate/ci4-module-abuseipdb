<?php

namespace Modules\AbuseIpdb\Services;

use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\RequestInterface;

use Modules\AbuseIpdb\Config\AbuseIpdb as AbuseIpdbConfig;
use Modules\AbuseIpdb\Entities\ConfidenceEntity;
use Modules\AbuseIpdb\Models\ConfidenceModel;
use Modules\AbuseIpdb\Exceptions\UnloggedException;

class AbuseIpdb
{
	protected ConfidenceModel $model;
	protected CURLRequest $client;
	protected AbuseIpdbConfig $config;

	public function __construct()
	{
		$this->config = config(AbuseIpdbConfig::class);
		$this->model = model(ConfidenceModel::class);
		$this->client = service('curlrequest', [
			'baseURI' => 'https://api.abuseipdb.com/api/v2/',
			'headers' => [
				'Accept' => 'application/json',
				'Key' => $this->config->apiKey,
			],
		]);
	}

	public function block(RequestInterface $request): bool
	{
		$ipAddress = $request->getIPAddress();

		try
		{
			$logged = $this->model->logged($ipAddress);

			if($logged->blacklist) return true;

			if($logged->whitelist) return false;

			if(!$logged->isExpired() && $logged->isAbusive()) return true;

		} catch (UnloggedException $e) {}

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

		$this->model->save(new ConfidenceEntity($this->config->autoBlacklist ? [
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $json['data']['abuseConfidenceScore'],
			'user_agent' => $request->getUserAgent(),
			'blacklist' => AbuseIpdbConfig::blacklist($request),
		] : [
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $json['data']['abuseConfidenceScore'],
			'user_agent' => $request->getUserAgent(),
		]));

		if($json['data']['abuseConfidenceScore'] <= $this->config->abuseConfidenceScore) return false;

		command("abuseipdb:add {$ipAddress}");

		return true;
	}

	public function check(string $ipAddress): array
	{
		$response = $this->client->request('GET', 'check', [
			'query' => [
				'ipAddress' => $ipAddress,
				'maxAgeInDays' => $this->config->maxAgeInDays,
			]
		]);

		$json = json_decode($response->getBody(), true);

		if(array_key_exists('errors', $json))
			throw new HTTPException("API: {$json['errors'][0]['status']} {$json['errors'][0]['detail']}");

		return $json;
	}
}