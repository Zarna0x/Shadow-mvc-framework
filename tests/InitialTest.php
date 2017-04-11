<?php

Class InitialTest Extends \PHPUnit\Framework\TestCase
{
	public function setUp()
	{
   
	}

	public function testIfTrueAssertsTrue()
	{
	  #var_dump(new \Shadowapp\Sys\Dbmanager);
      self::assertTrue(true);
	}
}