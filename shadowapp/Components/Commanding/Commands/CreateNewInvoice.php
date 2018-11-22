<?php

namespace Shadowapp\Components\Commanding\Commands;

use Shadowapp\Sys\Commanding\Interfaces\CommandInterface;

class CreateNewInvoice implements CommandInterface
{
    private $userId;
    private $cost;
    private $name;
    
    
    public function __construct($userId, $cost, $name)
    {
       $this->userId = $userId;
       $this->cost = $cost;
       $this->name = $name;
    }
}
