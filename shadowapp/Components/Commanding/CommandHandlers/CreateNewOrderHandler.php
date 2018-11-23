<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Components\Commanding\CommandHandlers;

use Shadowapp\Sys\Commanding\Interfaces\CommandInterface;
use Shadowapp\Sys\Commanding\Interfaces\CommandHandlerInterface;
use Shadowapp\Models\OrdersShadow as Order;

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
        
    }
}
