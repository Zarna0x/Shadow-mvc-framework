<?php

namespace Shadowapp\Components\Eventing\Listeners;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;
use Shadowapp\Sys\Eventing\Interfaces\EventHandlerInterface;

class OnOrderGeneration implements EventHandlerInterface
{
   public function handle( EventInterface $event )
   {
		 var_dump('inhandle');

   }
}