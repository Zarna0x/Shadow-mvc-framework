<?php
use Shadowapp\Sys\Eventing\EventDispatcher;
use Shadowapp\Components\Eventing\Events\OrderWasGenerated;
use Shadowapp\Components\Eventing\Events\UserWasUpdated;
use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

EventDispatcher::addListener('order.generated', 'OnOrderGeneration');
EventDispatcher::addListener('order.generated', function (EventInterface $event) {
	var_dump($event);
});
//$dispatcher->raiseMany( [ new OrderWasGenerated( 'k' ), new UserWasUpdated('3') ] );



