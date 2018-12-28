<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Components\Eventing\Events;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;

class OrderWasGenerated implements EventInterface
{
	  const NAME = 'order.generated';
		
    private $order;
    
    public function __construct( $order )
    {
    	$this->order = $order;
    }

    public function getObject()
    {
    	return $this->order;
    }
}
