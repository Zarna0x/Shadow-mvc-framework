<?php
namespace Shadowapp\Sys\Traits\Eventing;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;
use Shadowapp\Sys\Eventing\EventDispatcher;
use Shadowapp\Sys\Traits\RouteValidatorTrait;

trait Eventer
{
  use RouteValidatorTrait;
	
	protected $eventQueue = [];

	public function raise( EventInterface $event )
	{
		$this->eventQueue[$this->getEventName( $event )]['event'] = $event;

	}

	public function raiseMany( array $events )
	{
		foreach ( $events as $event )
		{
			$this->raise( $event );
		}

	}

	public function fire( $eventName )
	{
		$eventsToHandle = $this->eventQueue[$eventName];

	}
	
	public function addListener( string $eventName, $listener  )
	{
		EventDispatcher::addListener($eventName, $listener);
	}

	private function getEventName( EventInterface $event )
	{
		try
		{
			$constName = (new \ReflectionClassConstant( $event, 'NAME' ) )->getValue();

			if ( is_string( $constName ) && !empty( $constName ) )
			{
				return trim( $constName );
			}
		}
		catch ( \ReflectionException $e )
		{
			
		}

		$className = (new \ReflectionClass( $event ) )->getShortName();

		return self::splitAtUpperCase( $className, '.' );

	}

	public function getEventQueue()
	{
		return $this->eventQueue;

	}

	public function dispatchAll()
	{

		if ( !count( $this->eventQueue ) )
		{
			throw new \Exception( 'No event is raised.' );
		}

		// execute every Handle method for each event


		foreach ( $this->getEventQueue() as $eventName => $event )
		{
			$listeners = EventDispatcher::getListener( $eventName );
			if ( !$listeners )
			{
				continue;
			}
			$this->handleListeners( $eventName, $listeners );
		}

	}

	protected function handleListeners( string $eventName, array $listeners )
	{
		if ( !count( $listeners ) )
		{
			return;
		}

		foreach ( $listeners as $listener )
		{

			if ( !is_callable( $listener ) && !is_string( $listener ) )
			{
				continue;
			}

			$postFix = (is_callable( $listener )) ? 'Callable' : 'Class';
			$methToExec = 'handleListener'.$postFix;
			
			$this->$methToExec($eventName, $listener );
		}

	}

	private function handleListenerClass( string $eventName, string $listener)
	{
		  $listenerPath = EVENT_LISTENERS_DIR . $listener . '.php';
			
			$fileInfo = new \SplFileInfo( $listenerPath );

			if ( $fileInfo->getRealPath() === false )
			{
				return;
			}

			$fullClassName = '\\Shadowapp\\Components\\Eventing\\Listeners\\' . $listener;
			$reflectionClass = new \ReflectionClass( $fullClassName );
			$expectedInterface = 'Shadowapp\Sys\Eventing\Interfaces\EventHandlerInterface';

			if ( !in_array( $expectedInterface, $reflectionClass->getInterfaceNames() ) )
			{
				throw new \Exception( $listener . ' must implement ' . $expectedInterface );
			}

			$methodToExecute = $reflectionClass->getMethod( 'handle' );

			$event = $this->eventQueue[$eventName]['event'];

			$methodToExecute->invoke( new $fullClassName, $event );
	}

	private function handleListenerCallable(string $eventName, callable $listenerCallable )
	{
		var_dump('okkk');		
	}
	
	 

}
