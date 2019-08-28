<?php

namespace Shadowapp\Controllers;

use Shadowapp\Sys\View\View;
use Shadowapp\Sys\Http\Middleware;
use Shadowapp\Sys\Routing\Router;
use Shadowapp\Sys\Commanding\CommandBus;

use Shadowapp\Components\Commanding\Commands\CreateNewOrder;

class ApiShadow
{

    public function __construct()
    {
        route('uscrete', [
            'username' => 'Someone',
            'resourceid' => 78
        ]);
    }

    public function kk()
    { 
        var_dump('asd');
        //CommandBus::execute(new CreateNewOrder(3,'asdasd'));
    }

    public function auth($username, $resourceId, $k = 'asd')
    {
        
    }

    public function withMiddleware()
    {
        View::run('contact/contact', [
            'username' => 'wtfd'
        ]);
    }

}
