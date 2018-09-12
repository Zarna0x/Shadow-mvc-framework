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

    /*
     * @desc set header
     * @param String $headerKey
     * @param mixed $headerValue
     * @return Shadowapp\Sys\Http
     */
	public function setHeader ($headerKey, $headerValue) 
	{

      if ( !is_string( $headerKey ) || !is_string( $headerValue )) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException;
      }

      header(trim($headerKey).': '. trim( $headerValue ) );

      return $this;
	}


    /*
     * @desc set status code
     * @param int $statusCode
     * @return Shadowapp\Sys\Http
     */
   	public function setStatusCode ( $statusCode )
	{
       if ( !is_integer( $statusCode ) ) {
          throw new \Shadowapp\Sys\Exceptions\WrongVariableTypeException;
      }
      
      http_response_code( $statusCode );
      return $this;
	}

	/*
	 *  @desc get status code
	 */

	public function getStatusCode (  ) 
	{
      return http_response_code();
    }

    /*
     * @desc Get all headers, or specified key
     * @param String $headerKey
     * @return Mixed
     */
	public function getHeaders( $headerKey = '' )
	{
       $headerList = getAllHeaders();

       if ( !empty( $headerKey ) && is_string( $headerKey ) ) {
         return shcol($headerKey,$headerList);
       }

       return $headerList;
	}
}