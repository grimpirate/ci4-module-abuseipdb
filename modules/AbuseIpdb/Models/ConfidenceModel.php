<?php

namespace Modules\AbuseIpdb\Models;

use CodeIgniter\Model;

use Modules\AbuseIpdb\Entities\ConfidenceEntity;

class ConfidenceModel extends Model
{
	protected $DBGroup = 'abuseipdb';
	protected $table = 'confidence';
	protected $primaryKey = 'ip_address';
	protected $useAutoIncrement = false;
	protected $allowedFields = [
		'abuse_confidence_score',
		'whitelisted',
	];
	protected $useSoftDeletes = true;
	protected $useTimestamps = true;
	protected $returnType = ConfidenceEntity::class;

	public function ok($ipAddress): bool
	{
		$row = $this->where('ip_address', $ipAddress)->first();
		if(empty($row)) return false;
		return $row->ok;
	}
}
