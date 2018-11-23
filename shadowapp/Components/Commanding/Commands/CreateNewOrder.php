<?php

namespace Shadowapp\Components\Commanding\Commands;

use Shadowapp\Sys\Commanding\Interfaces\CommandInterface;

class CreateNewOrder implements CommandInterface
{
    public $staffId;
    public $title;
    
    public function __construct( $staffId, $title )
    {
       $this->staffId = $staffId;
       $this->title = $title;       
    }
}

