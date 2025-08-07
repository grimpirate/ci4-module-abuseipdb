<?php

namespace Modules\AbuseIpdb\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

use Modules\AbuseIpdb\Config\AbuseIpdb;

class ConfidenceEntity extends Entity
{
	protected $casts = [
		'ip_address' => 'string',
		'abuse_confidence_score' => 'integer',
		'blacklist' => 'boolean',
		'whitelist' => 'boolean',
		'user_agent' => '?string',
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	public function isExpired(): bool
	{	
		$config = config(AbuseIpdb::class);

		$days = $this->updated_at->difference(Time::now())->getDays();

		return $days > $config->maxAgeInDays;
	}

	public function isAbusive(): bool
	{
		$config = config(AbuseIpdb::class);

		return $this->abuse_confidence_score > $config->abuseConfidenceScore;
	}
}
