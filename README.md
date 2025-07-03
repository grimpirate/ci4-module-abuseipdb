# AbuseIPDB
CodeIgniter Module

## Setup

modules/AbuseIpdb/Config/AbuseIpdb
```
public string $apiKey = 'YOUR_ABUSEIPDB_API_KEY';
```
app/Config/Autoload
```
public $psr4 = [
    'Modules\AbuseIpdb' => ROOTPATH . 'modules/AbuseIpdb',
];
```
app/Config/Database
```
public array $abuseipdb = [
    'database'    => 'abuseipdb.db',
    'DBDriver'    => 'SQLite3',
];
```
app/Config/Filters
```
use Modules\AbuseIpdb\Filters\AbuseIpdb;

public array $aliases = [
    'abuseipdb' => AbuseIpdb::class,
];

public array $required = [
    'before' => [
        'abuseipdb',
    ],
];
```
.htaccess
```
# Block IPs
<RequireAll>
    Require all granted
</RequireAll>
```

spark
```
php spark db:create abuseipdb --ext db
php spark migrate
```
