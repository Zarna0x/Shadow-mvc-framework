<?php

namespace Shadowapp\Sys\Http;

class Response extends AbstractHttp
{
	public function sendJson( $jsonArray )
	{

      if (!is_array( $jsonArray ) && !is_object($jsonArray)) {
            throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException;
      }
       

      $this->setContentType('application/json');

      echo json_encode( $jsonArray );
      
      exit;


	}

	
}