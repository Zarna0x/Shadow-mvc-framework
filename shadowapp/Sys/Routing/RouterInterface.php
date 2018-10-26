<?php

namespace Shadowapp\Sys\Routing;

interface RouterInterface
{
	public static function define($uri,$method,$request_type);
	public static function api($uri,$method,$request_type);
	public static function run();
}