<?php

namespace Shadowapp\Components\Commanding\CommandHandlers;

use Shadowapp\Sys\Commanding\Interfaces\CommandInterface;
use Shadowapp\Sys\Commanding\Interfaces\CommandHandlerInterface;
use Shadowapp\Models\OrdersShadow as Order;
use Shadowapp\Sys\Eventing\Event;

class CreateNewOrderHandler implements CommandHandlerInterface
{
    protected $order;
    
    public function __construct()
    {
        $this->order = new Order;
    }
    
    public function handle(CommandInterface $command )
    {
       //Store Data;
       $this->order->store([
           'staffId' => $command->staffId,
           'title' => $command->title
       ]);
      
       echo '<pre>'.print_R(Event::getQueue(),1).'</pre>'; 
        
    }
}
