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
		'whitelisted' => 'boolean',
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	public function getOk(): bool
	{
		if($this->whitelisted) return true;
		
		$days = $this->updated_at->difference(Time::now())->getDays();

		$config = config(AbuseIpdb::class);

		if($days > $config->maxAgeInDays) return false;

		if($this->abuse_confidence_score > $config->abuseConfidenceScore) return false;

		return true;
	}
}
