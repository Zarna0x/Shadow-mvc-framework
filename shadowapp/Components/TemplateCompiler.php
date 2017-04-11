<?php


namespace Shadowapp\Components;


/*
 * Shadow Template Engine Compiler
 */
class TemplateCompiler
{
  
  public $assignedValues = [];
  public $template;
  public $filename;

  public function __construct($filePath)
  {
    if(file_exists($filePath))
    {
    	$this->template = file_get_contents($filePath);
        $this->filename = $filePath;
    
    }else
    {
    	echo "File Does not Exists";
    }
  }


  public function assign($setParams = [])
  {
  	
     if(!empty($setParams))
     {
     	
         foreach($setParams as $key => $value) {
             $this->assignedValues[$key] = $value;
         }
         
     }
     
  }



  public function run()
  {
  	 try 
  	 {
  	 	if(count($this->assignedValues) > 0) 
  	{
       foreach ($this->assignedValues as $key => $value) { //value == array
         	//var_dump($value);
         	switch ($value) {
         		case is_array($value):
                   
                   /*
                    * check if array is mutlidimmensional
                    */
                   foreach($value as $s_key => $s_val) 
                   {
                        if (is_array($s_val)) {
                          
                          foreach($s_val as $th_key => $th_val ) {
                          
                          	$this->template = str_replace('^'.$key."[$s_key][$th_key]".'^',"<?php echo $".$key."['$s_key']['$th_key']"."; ?>",$this->template);
                            $multdim = true;
                          }   
                        }
                         
                         
                             $this->template = str_replace('^'.$key."[$s_key]".'^',"<?php echo "."$".$key."['$s_key']"."; ?>",$this->template); 
                        
                        
                       
                   }    
         	
         	break;

         	case is_object($value):
         		  
         		  foreach($value as $sobj_key => $sobj_val ) {
                      
                      
                      $this->template = str_replace("^".$key.".$sobj_key"."^", "<?php echo $".$key."->$sobj_key; ?>", $this->template);
         		  }

         		break;

            
         		
         		default:
         			$this->template = str_replace('^'.$key.'^',"<?php echo $".$key."; ?>" ,$this->template);
         	        
         		break;
         	}


         	
       }

                 /*
                  * Set Rules 
                  */
                 $this->template = $this->setRules($this->template);
         //return;

         $cache_file_name = md5($this->filename).".php";
         $cache_file = fopen(CACHE_DIR.'/'.$cache_file_name,'w+');
  	     fwrite($cache_file,$this->template);
  	     fclose($cache_file);

  	     return CACHE_DIR.'/'.$cache_file_name;
  	} 

  	 }catch(Exception $e){
        echo $e->getMessage();   
  	 }
  }


 /*
  * Set Compilator Rules
  */
  protected function setRules($temp) 
  {
      
      
        $statement    = $this->getStringBetween($temp,"@",'@');
      
        $temp = str_replace("@".$statement."@", "<?php ".$statement."; ?>", $temp);
       
       return $temp;
      
  }



  protected function ruleExists($rule)
  {
     if(strpos($this->template, $rule) != false)
      {
        return true;
      }

       return false;
  }


 
  protected function getStringBetween($string, $start, $end)
  {
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);   
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
  }


}

?>