<?php

namespace Shadowapp\Components\Middlewares;

use Shadowapp\Sys\Http\MiddlewareInterface;

class Http implements MiddlewareInterface
{
	public function register() : array
	{
        return ['test'];
	}

	public static function test()
	{
		if (2>1) {
          exit('Wrong');
		}
	}
}