<?php

namespace Shadowapp\Sys\Email;

Class Email implements EmailInterface
{
    
   protected $address;

   protected $subject;

   protected $message;

   protected $headers = [];


   /*
    * @param string $to
    */
	public function setAddress( $to ) 
	{
       $this->address = $to;
       return $this;
	}

	/*
    * @param string $subject
    */
	public function setSubject( $subject )
	{
      $this->subject = $subject;
      return $this;
	}
    
    /*
    * @param string $message
    */
	public function setMessage( $message )
	{
	   $this->message = $message;
       return $this;
	}

	/*
    * @param array $headers
    */
    public function setHeaders( array $headers )
    {   
    	$this->headers = $headers;
        return $this;
    }
   

    public function send()
    {
       try{
       	var_dump($this->message);
       return mail($this->address, $this->subject, wordwrap($this->message, 70, "\r\n"));
       }catch(\Exception $e){
         print($e->getMessage());
         exit;
       }
    }
    

}

