<?php

namespace Modules\AbuseIpdb\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateConfidenceTable extends Migration
{
	protected $DBGroup = 'abuseipdb';

	public function up()
	{
		$this->forge->addField([
			'ip_address' => [
				'type' => 'TEXT',
				'unique' => true,
			],
			'abuse_confidence_score' => [
				'type' => 'INTEGER',
			],
			'whitelisted' => [
				'type' => 'BOOLEAN',
				'default' => false,
			],
			'created_at' => [
				'type' => 'INTEGER',
				'default' => new RawSql('CURRENT_TIMESTAMP'),
				'null' => true,
			],
			'updated_at' => [
				'type' => 'INTEGER',
				'default' => new RawSql('CURRENT_TIMESTAMP'),
				'null' => true,
			],
			'deleted_at' => [
				'type' => 'INTEGER',
				'null' => true,
			],
		]);
		$this->forge->addPrimaryKey('ip_address');
		$this->forge->createTable('confidence', true);
	}

	public function down()
	{
		$this->forge->dropTable('confidence', true);
	}
}