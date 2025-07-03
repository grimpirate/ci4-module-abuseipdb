<?php

namespace Modules\AbuseIpdb\Config;

use CodeIgniter\Config\BaseService;
use Modules\AbuseIpdb\Services\AbuseIpdb;

class Services extends BaseService
{
    public static function abuseipdb(bool $getShared = true): AbuseIpdb
    {
        if($getShared)
            return self::getSharedInstance('abuseipdb');

        return new AbuseIpdb();
    }
}