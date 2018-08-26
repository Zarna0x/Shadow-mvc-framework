<?php

namespace Shadowapp\Sys\Http;

class AbstractHttp
{   
	/*
	 * @desc set content type 
	 * @param String $contentType
	 * @return Shadowapp\Sys\Http
	 */
	public function setContentType ($contentType) 
	{

      if ( !is_string( $contentType ) ) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException;
      }

      header('Content-Type: '. trim( $contentType ) );

      return $this;
	}

	public function setHeader ($headerKey, $headerValue) 
	{

      if ( !is_string( $headerKey ) || !is_string( $headerValue )) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException;
      }

      header(trim($headerKey).': '. trim( $headerValue ) );

      return $this;
	}


	public function setStatusCode ( $statusCode ) 
	{
       if ( !is_integer( $statusCode ) ) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException;
      }
      
      http_response_code( $statusCode );
      return $this;
	}

	public function getStatusCode (  ) 
	{
      return http_response_code();
      
	}

	public function getHeaders( $headerKey = '' )
	{
       $headerList = getAllHeaders();

       if ( !empty( $headerKey ) && is_string( $headerKey ) ) {
         return shcol($headerKey,$headerList);
       }

       return $headerList;
	}
}