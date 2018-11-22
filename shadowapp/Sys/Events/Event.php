<?php

namespace Shadowapp\Sys\Events;

use SplSubject;
use SplObserver;

class Event implements SplSubject
{
   private $events = [];
   public  $eventName;

   public function __construct ( string $eventName ) 
   {

   	  if (empty($eventName)) {
        throw new Exception('Empty Event Name');
   	  }
   	  
      $this->eventName = trim($eventName);
      
   }

   public function attach( SplObserver $observer )
   {
      $this->events[spl_object_hash($observer)] = $observer;
   }


   public function detach( SplObserver $observer ){
      unset($this->events[spl_object_hash($observer)]);
   }

   public function notify(){
   	foreach ( $this->events as $event ) {
        $event->update($this);
   	} 
   }
}