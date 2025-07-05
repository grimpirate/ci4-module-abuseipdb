<?php

namespace Modules\AbuseIpdb\Config;

use CodeIgniter\Config\BaseConfig;

class AbuseIpdb extends BaseConfig
{
	public string $apiKey            = 'YOUR_ABUSEIPDB_API_KEY';
	public int $abuseConfidenceScore = 75;
	public int $maxAgeInDays         = 30;
}