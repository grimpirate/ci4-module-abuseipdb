<?php

namespace Modules\AbuseIpdb\Models;

use CodeIgniter\Model;
use CodeIgniter\Publisher\Publisher;

use Modules\AbuseIpdb\Entities\ConfidenceEntity;

class ConfidenceModel extends Model
{
	protected $DBGroup = 'abuseipdb';
	protected $table = 'confidence';
	protected $primaryKey = 'ip_address';
	protected $allowedFields = [
		'abuse_confidence_score',
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

	public function store(string $ipAddress, int $abuseConfidenceScore): bool
	{
		if($abuseConfidenceScore > setting('AbuseIpdb.abuseConfidenceScore'))
		{
			$publisher = new Publisher(FCPATH);
			$publisher->addLineBefore(
				'.htaccess',
				" Require not ip {$ipAddress}",
				'</RequireAll>'
			);
		}

		return $this->save([
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $abuseConfidenceScore,
		]);
	}
}