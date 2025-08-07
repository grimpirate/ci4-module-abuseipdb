<?php

namespace Modules\AbuseIpdb\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AbuseIpdb implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		if(service('abuseipdb')->block($request))
			return service('response')->setStatusCode(403);
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do nothing
	}
} 