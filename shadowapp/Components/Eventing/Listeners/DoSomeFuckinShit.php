<?php

namespace Shadowapp\Components\Eventing\Listeners;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;
use Shadowapp\Sys\Eventing\Interfaces\EventHandlerInterface;

class DoSomeFuckinShit implements EventHandlerInterface
{
	protected $eventsToHandle = [];

    public function handle(EventInterface $event )
    {
			
       $class = explode('\\', get_class($this));

       echo " From => ". end($class) ;
       echo '<pre>'.print_R($event->getObject(),1).'</pre>'; 
    }

    private function reallyHandle()
    {
       
    }
}
