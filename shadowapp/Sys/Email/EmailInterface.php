<?php

namespace Shadowapp\Sys\Email;

interface EmailInterface
{
   /*
    * @param string $to
    */
	public function setAddress( $to );

	/*
    * @param string $subject
    */
	public function setSubject( $subject );
    
    /*
    * @param string $message
    */
	public function setMessage( $message );

	/*
    * @param array $headers
    */
    public function setHeaders( array $headers );
   

    public function send();
    

}

