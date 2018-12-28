<?php
use Shadowapp\Sys\Eventing\EventDispatcher;
use Shadowapp\Components\Eventing\Events\OrderWasGenerated;
use Shadowapp\Components\Eventing\Events\UserWasUpdated;
use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

EventDispatcher::addListener('order.generated',[ 'OnOrderGeneration',function () {
	var_dump('ok');
}]);
EventDispatcher::addListener('order.generated',function (){
	echo 'KIDESXFVA';
});

//$dispatcher->raiseMany( [ new OrderWasGenerated( 'k' ), new UserWasUpdated('3') ] );
