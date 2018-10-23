<?php

namespace Shadowapp\Sys\Log;

use Exception;

Interface ExceptionHandlerInterface 
{
	public function report(Exception $exception) : bool;
	public function render(Exception $exception) : bool;
}