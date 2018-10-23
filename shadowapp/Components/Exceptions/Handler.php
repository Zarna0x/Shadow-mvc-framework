<?php

namespace Shadowapp\Components\Exceptions;

use Exception;
use Shadowapp\Sys\ExceptionHandler;
use Shadowapp\Sys\View\View;


class Handler extends ExceptionHandler
{  

	protected $dontReport = [
      \Shadowapp\Sys\Exceptions\MiddlewareNotFoundException::class
	];
    
    public function report(Exception $exception) : bool
	{
         
	}


	public function render (Exception $exception) : bool
	{
       View::run('errors/404',[
           'errorMessage' => $exception->getMessage()
       ]);
       die;
	}
}