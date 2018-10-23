<?php

namespace Shadowapp\Components\Exceptions;

use Exception;
use Shadowapp\Sys\ExceptionHandler;
use Shadowapp\Sys\Log\ExceptionHandlerInterface;

class Handler extends ExceptionHandler implements ExceptionHandlerInterface
{  

	protected $dontReport = [
      \Shadowapp\Sys\Exceptions\MiddlewareNotFoundException::class
	];
    


	public function report(Exception $exception)
	{
         
	}


	public function render (Exception $exception)
	{
       
	}
}