<?php

namespace Modules\AbuseIpdb\Config;

use CodeIgniter\Config\BaseConfig;

class AbuseIpdb extends BaseConfig
{
	public string $apiKey            = 'YOUR_ABUSEIPDB_API_KEY';
	public int $abuseConfidenceScore = 75;
	public int $maxAgeInDays         = 30;
	public bool $autoBlacklist       = true;

	public static function blacklist(RequestInterface $request): bool
	{
		$userAgent = $request->getUserAgent();
		if(empty($userAgent) || strcasecmp('-') === 0) return true;
		if(1 === preg_match('/censys|palo|hawaiibot|python|facebook|cms-checker|scrapy|wanscannerbot|googlebot|gptbot|bingbot|applebot/i', $userAgent)) return true;

		$routePath = $request->getRoutePath();
		if(1 === preg_match('/robots|security|xml|wp-include/i', $routePath)) return true;

		return false;
	}
}