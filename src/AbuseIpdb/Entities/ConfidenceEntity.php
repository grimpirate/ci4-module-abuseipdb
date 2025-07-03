<?php

namespace Modules\AbuseIpdb\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class ConfidenceEntity extends Entity
{
	protected $casts = [
		'ip_address' => 'string',
		'abuse_confidence_score' => 'integer',
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	public function getOk(): bool
	{
		$now = Time::now();
		$diff = $now->difference($this->updated_at);
		$days = -$diff->getDays();

		if($days > setting('AbuseIpdb.maxAgeInDays')) return false;

		if($this->abuse_confidence_score > setting('AbuseIpdb.abuseConfidenceScore')) return false;

		return true;
	}
}
