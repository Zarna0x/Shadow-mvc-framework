<?php

namespace Shadowapp\Components\Commanding\CommandHandlers;

use Shadowapp\Sys\Commanding\Interfaces\CommandInterface;
use Shadowapp\Sys\Commanding\Interfaces\CommandHandlerInterface;
use Shadowapp\Models\OrdersShadow as Order;
use Shadowapp\Models\StaffShadow as Staff;
use Shadowapp\Sys\Eventing\Event;

class CreateNewOrderHandler implements CommandHandlerInterface
{
    protected $order;
    
    public function __construct()
    {
        $this->order = new Order;
        $this->staff = new Staff();
    }
    
    public function handle(CommandInterface $command )
    {
       //Store Data;
       $this->order->store([
           'staffId' => $command->staffId,
           'title' => $command->title
       ]);
       
       $this->staff->addOrder($command);
      
       $this->order->dispatchAll();
       
        
    }
}
