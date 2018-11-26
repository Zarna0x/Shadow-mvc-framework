<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Components\Eventing\Events;

use Shadowapp\Sys\Eventing\Interfaces\EventInterface;
use Shadowapp\Models\StaffShadow;

class UserWasUpdated implements EventInterface
{
    protected $staff;
    
    public function __construct(StaffShadow $staff )
    {
       $this->staff = $staff;
    }
    
    public function getStaff()
    {
        return $this->staff;
    }
}
