<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Components\Events;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

class OrderWasGenerated implements EventInterface
{
    public $order;
    
    public function __construct( $order )
    {
        $this->order = $order;
    }
}
