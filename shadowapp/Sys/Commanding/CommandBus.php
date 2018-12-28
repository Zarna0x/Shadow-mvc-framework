<?php

namespace Shadowapp\Sys\Commanding;

use Shadowapp\Sys\Commanding\Interfaces\CommandInterface;
use Shadowapp\Sys\Commanding\Interfaces\CommandHandlerInterface;

class CommandBus
{
    protected $defaultCommandHandlerNamespace = 'Shadowapp\\Components\\Commanding\\CommandHandlers\\';

    protected $defaultCommandHandlerSuffix = 'Handler';
    
    private function __construct(){}
    
    public static function execute( CommandInterface $command  )
    {
        $bus = new static();
        
        $handlerToExecute = $bus->getCommandHandler($command);
        
        (new \ReflectionMethod($handlerToExecute, 'handle'))->invoke(new $handlerToExecute,$command);
    }
    
    public function getCommandHandler( CommandInterface $command ) 
    {
        $commandName = (new \ReflectionClass($command))->getShortName();
        
        $expectedHandler = $this->defaultCommandHandlerNamespace.$commandName.$this->defaultCommandHandlerSuffix;
        
        if (!class_exists($expectedHandler)) {
            throw new \Exception($expectedHandler .' does not exists');
        }
        
        if (!is_a($expectedHandler, 'Shadowapp\Sys\Commanding\Interfaces\CommandHandlerInterface', true)) {
            throw new \Exception($expectedHandler .' must implement CommandHandlerInterface');
        }
        
        return $expectedHandler;
    }
    
}
