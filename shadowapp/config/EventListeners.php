<?php
use Shadowapp\Sys\Eventing\EventDispatcher;
use Shadowapp\Components\Eventing\Events\OrderWasGenerated;
use Shadowapp\Components\Eventing\Events\UserWasUpdated;
use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

$dispatcher = new EventDispatcher();

$dispatcher->addListener('order.generated', 'OnOrderGeneration');
$dispatcher->addListener('order.generated', function (EventInterface  $event ) {
    var_dump($event);
});
//$dispatcher->raiseMany( [ new OrderWasGenerated( 'k' ), new UserWasUpdated('3') ] );



