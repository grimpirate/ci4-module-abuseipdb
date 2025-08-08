<?php

namespace Modules\AbuseIpdb\Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\HTTP\RequestInterface;

class AbuseIpdb extends BaseConfig
{
	public string $apiKey            = 'YOUR_ABUSEIPDB_API_KEY';
	public int $abuseConfidenceScore = 75;
	public int $maxAgeInDays         = 30;
	public bool $autoBlacklist       = false;

	public static function blacklist(RequestInterface $request): bool
	{
		if($request->getUserAgent()->isRobot()) return true;

		$userAgentString = $request->getUserAgent()->getAgentString();

		if(empty($userAgentString)) return true;

		if(1 === preg_match('/x11; (?:linux|cros) x86_64|windows nt 6.1|android|iphone/i', $userAgentString)) return true;

		if(1 === preg_match('/robots|security|xml|wp-include|file-manager/i', $request->getPath())) return true;

		return false;
	}
}