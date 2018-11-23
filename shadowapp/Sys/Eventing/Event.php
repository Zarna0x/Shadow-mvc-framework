<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Sys\Eventing;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

class Event
{

    protected static $eventQueue = [];

    public static function raise(EventInterface $event)
    {
        $eventName = self::getEventName($event);
        self::$eventQueue[$eventName] = $event;
    }

    public static function raiseMany( array $events )
    {
        foreach ( $events as $event ) {
            self::raise($event);
        }
        
        echo '<pre>'.print_R(self::$eventQueue,1).'</pre>'; 
    }

    private static function getEventName(EventInterface $event)
    {
        return (new \ReflectionClass($event))->getShortName();
    }

    public static function getQueue()
    {
        return self::$eventQueue;
    }

}
