<?php

namespace Modules\AbuseIpdb\Publisher;

use CodeIgniter\Publisher\Publisher as CodeIgniterPublisher;

class Publisher extends CodeIgniterPublisher
{
    private function verifyAllowed(string $from, string $to): void
    {}
}