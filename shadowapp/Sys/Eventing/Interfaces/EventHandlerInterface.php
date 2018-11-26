<?php

namespace Shadowapp\Sys\Eventing\Interfaces;

interface EventHandlerInterface
{
    public function handle( EventInterface $event );
}
