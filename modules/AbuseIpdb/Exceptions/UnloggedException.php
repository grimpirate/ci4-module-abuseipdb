<?php

namespace Modules\AbuseIpdb\Exceptions;

class UnloggedException extends \ErrorException
{
	public function __construct($message = null, $code = 0, $severity = E_ERROR, $filename = null, $line = null, \Throwable $previous = null)
	{
		parent::__construct($message ?? lang('AbuseIpdb.exceptions.unlogged'), $code, $severity, $filename, $line, $previous);
	}
}