<?php

namespace Shadowapp\Sys\Eventing;

use Shadowapp\Sys\Traits\Eventing\Eventer;

class EventDispatcher
{
	
	protected static $listeners;
	
	public static function addListener( string $eventName, $listener )
	{
		 self::$listeners[trim($eventName)][] = $listener;
	}
	
	public static function getListener( string $eventName)
	{
		if (isset(self::$listeners[$eventName])) {
			 return self::$listeners[$eventName];
		}
		
		return false;
	}
	
	public static function getAllListeners()
	{
		 return self::$listeners;
	}
	
}