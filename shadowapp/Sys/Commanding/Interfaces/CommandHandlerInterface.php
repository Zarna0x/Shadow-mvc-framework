<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shadowapp\Sys\Commanding\Interfaces;



interface CommandHandlerInterface
{
    public function handle( CommandInterface $command );
}
