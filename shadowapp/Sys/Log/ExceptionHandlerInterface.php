<?php

namespace Shadowapp\Sys\Log;

use Exception;

Interface ExceptionHandlerInterface 
{
	public function report(Exception $exception);
	public function render(Exception $exception);
}