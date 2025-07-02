<?php

namespace Modules\AbuseIpdb\Models;

use CodeIgniter\Model;
use CodeIgniter\Publisher\Publisher;

use Modules\AbuseIpdb\Entities\WhitelistEntity;

class AbuseIpdbWhitelistModel extends Model
{
	protected $DBGroup = 'abuseipdb';
	protected $table = 'abuseipdb_whitelist';
	protected $primaryKey = 'ip_address';
	protected $allowedFields = [
		'abuse_confidence_score',
	];
	protected $useSoftDeletes = true;
	protected $useTimestamps = true;
	protected $returnType = WhitelistEntity::class;

	public function ok($ipAddress): bool
	{
		$row = $this->where('ip_address', $ipAddress)->first();
		if(empty($row)) return false;
		return $row->ok;
	}

	public function save(string $ipAddress, int $abuseConfidenceScore): bool
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

		return parent::save([
			'ip_address' => $ipAddress,
			'abuse_confidence_score' => $abuseConfidenceScore,
		]);
	}
}