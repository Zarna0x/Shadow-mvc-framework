<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Models;

use Shadowapp\Sys\Db\Model;
use Shadowapp\Components\Events\OrderWasGenerated;
use Shadowapp\Components\Events\SomethingHappened;

class OrdersShadow extends Model
{
   public function store( array $orderData )
   {

       $this->staff_id = $orderData['staffId'];
       $this->title = $orderData['title'];
       
       $this->save();
       
       // Raise EVENTS!
       
       $this->raiseEvents([new orderWasGenerated($this),new SomethingHappened($this)]);
               
       
       
       
   }
}
