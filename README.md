# AbuseIPDB
A CodeIgniter4 Module that provides filtering of IPs as a countermeasure to abuse by using the freely available API from [abuseipdb.com](https://www.abuseipdb.com)

## Setup
~
```
git clone --depth 1 --branch main --single-branch https://github.com/grimpirate/ci4-module-abuseipdb
mv ci4-module-abuseipdb/modules .
rm -rf ci4-module-abuseipdb
```
.env
```
Modules\AbuseIpdb\Config\AbuseIpdb.apiKey=YOUR_ABUSEIPDB_API_KEY;
OR
abuseipdb.apiKey=YOUR_ABUSEIPDB_API_KEY;
```
modules/AbuseIpdb/Config/AbuseIpdb.php
```
public string $apiKey = 'YOUR_ABUSEIPDB_API_KEY';
```
modules/AbuseIpdb/Config/Registrar.php
```
<?php

namespace Modules\AbuseIpdb\Config;

class Registrar
{
    public static function AbuseIpdb(): array
    {
        return [
            'apiKey' => 'YOUR_ABUSEIPDB_API_KEY',
        ];
    }
}
```
app/Config/Autoload.php
```
public $psr4 = [
    'Modules\AbuseIpdb' => ROOTPATH . 'modules/AbuseIpdb',
];
```
app/Config/Database.php
```
public array $abuseipdb = [
    'database'    => 'abuseipdb.db',
    'DBDriver'    => 'SQLite3',
];
```
app/Config/Filters.php
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
~
```
php spark db:create abuseipdb --ext db
php spark migrate -n Modules\\AbuseIpdb -g abuseipdb

chown -R apache:apache abuseipdb.db
```
