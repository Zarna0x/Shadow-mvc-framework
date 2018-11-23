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

    public static function raise(EventInterface $event, $table = null )
    {
        $eventName = self::getEventName($event);
        self::$eventQueue[$eventName]['event'] = $event;
        if (!is_null($table)) {
            self::$eventQueue[$eventName]['table'] = $table;
        }
        
    }

    public static function raiseMany( array $events, $table = null )
    {
        foreach ( $events as $event ) {
            self::raise($event , $table);
        }
        
    }
    
    public static function fire( $eventName )
    {
        
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
