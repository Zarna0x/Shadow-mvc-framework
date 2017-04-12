// <?php

// use Shadowapp\Sys\Db\Query\Builder;

// class QueryBuilderTest Extends \PHPUnit\Framework\TestCase
// {
// 	protected $db;
	
// 	public function setUp()
// 	{
// 		$this->db = new Builder;
// 	}

//    public function testSelectReturnsCorrectInstance()
//    {
//    	  $this->assertInstanceOf(Builder::class,$this->db->select());
//    }

//    public function testSelectReturnsCorrectCount()
//    {
//    	  $selectString = 'id,username,password';
//    	  $this->db->select($selectString);
     
//       $this->assertEquals(['id','username','password'],$this->db->getSelectData());
//    	  $this->assertCount(3,$this->db->getSelectData());
//    }

//    public function testSelectReturnCorrectDataWhenThereIsNoParam()
//    {
//    	  $this->db->select();
//    	  $this->assertEquals(['*'],$this->db->getSelectData());
//    	  $this->assertCount(1,$this->db->getSelectData());
//    }
// }