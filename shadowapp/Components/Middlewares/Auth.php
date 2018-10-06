<?php

namespace Shadowapp\Components\Middlewares;

use Shadowapp\Sys\Session;
use Shadowapp\Sys\Http\MiddlewareInterface;
use Shadowapp\Sys\Http\Requester as Request;

class Auth implements MiddlewareInterface
{
	public function register(): array
	{
		return [
          'member',
          'guest'
		];
	}

	public static function member()
	{
      if (!Session::has('staffMember')) {
       	  Request::redirect('login');
       }
	}

	public function guest()
	{
       if (Session::has( 'staffMember' )) {
         Request::redirect('/psystem');
       } 
    }
}