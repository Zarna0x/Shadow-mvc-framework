<?php

namespace Shadowapp\Components\Eventing\Listeners;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;
use Shadowapp\Sys\Eventing\Interfaces\EventHandlerInterface;

class DoSomeFuckinShit implements EventHandlerInterface
{
    public function handle(EventInterface $event )
    {
        echo '<pre>'.print_R($event,1).'</pre>'; 
        //var_dump("Order Was Generated by <h1>".$event->order->title.'</h1>');
    }
}
