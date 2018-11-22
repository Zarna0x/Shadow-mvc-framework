<?php

namespace Shadowapp\Sys\Events;

use SplSubject;
use SplObserver;

class EventObserver implements SplObserver 
{

    public function update(SplSubject $event) 
    {
        $Handle = $event->getS();
        $this->handle($handle);
    }

    public function handle($meth)
    {
        self::$meth();
    }

}
