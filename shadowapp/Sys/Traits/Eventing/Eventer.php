<?php

namespace Shadowapp\Sys\Traits\Eventing;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

trait Eventer
{

    protected $eventQueue = [];

    public function raise(EventInterface $event)
    {
        $this->eventQueue[$this->getEventName($event)]['event'] = $event;
    }

    public function raiseMany(array $events)
    {
        foreach ($events as $event) {
            $this->raise($event);
        }
    }

    public function fire($eventName)
    {
        $eventsToHandle = $this->eventQueue[$eventName];
    }

    private function getEventName(EventInterface $event)
    {
        return (new \ReflectionClass($event))->getShortName();
    }

    public function getEventQueue()
    {
        return $this->eventQueue;
    }

    public function dispatchAll()
    {
        if (!count($this->eventQueue)) {
            throw new \Exception('No event is raised.');
        }

        $listOfListeners = (new \Shadowapp\Sys\Config)->getFromFile('EventListeners');


        // execute every Handle method for each event

        if (!count($listOfListeners)) {
            return;
        }

        foreach ($listOfListeners as $eventName => $listeners) {
            if (!array_key_exists($eventName, $this->eventQueue))
                continue;
            $this->handleListeners($eventName, $listeners);
        }
    }

    protected function handleListeners(string $eventName, array $listeners)
    {
        if (!count($listeners))
            return;

        foreach ($listeners as $listener) {
            $listenerPath = EVENT_LISTENERS_DIR . $listener . '.php';
            $fileInfo = new \SplFileInfo($listenerPath);
            if ($fileInfo->getRealPath() === false) {
                continue;
            }

            $fullClassName = '\\Shadowapp\\Components\\Eventing\\Listeners\\' . $listener;
            $reflectionClass = new \ReflectionClass($fullClassName);
            $expectedInterface = 'Shadowapp\Sys\Eventing\Interfaces\EventHandlerInterface';

            if (!in_array($expectedInterface, $reflectionClass->getInterfaceNames())) {
                throw new \Exception($listener . ' must implement ' . $expectedInterface);
            }

            $methodToExecute = $reflectionClass->getMethod('handle');

            $event = $this->eventQueue[$eventName]['event'];
            
            $methodToExecute->invoke(new $fullClassName, $event);
        }
    }

}
