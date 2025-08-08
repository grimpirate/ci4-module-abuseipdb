<?php

namespace Modules\AbuseIpdb\Models;

use CodeIgniter\Model;

use Modules\AbuseIpdb\Entities\ConfidenceEntity;
use Modules\AbuseIpdb\Exceptions\UnloggedException;

class ConfidenceModel extends Model
{
	protected $DBGroup = 'abuseipdb';
	protected $table = 'confidence';
	protected $primaryKey = 'ip_address';
	protected $useAutoIncrement = false;
	protected $allowedFields = [
		'abuse_confidence_score',
		'blacklist',
		'whitelist',
		'user_agent',
	];
	protected $useSoftDeletes = true;
	protected $useTimestamps = true;
	protected $returnType = ConfidenceEntity::class;

	public function logged($ipAddress): ConfidenceEntity
	{
		$row = $this->where('ip_address', $ipAddress)->first();
		if(empty($row)) throw new UnloggedException();
		return $row;
	}
}
