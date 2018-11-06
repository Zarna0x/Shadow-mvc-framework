<?php 

namespace Shadowapp\Sys\View;

/*
 * Shadow Template Engine Compiler
 */
class Compiler
{

  private $allowedKeywords = [
   'foreach(','endforeach','if(','endif'
  ];
  /*
  * @desc list of assigned values of view vars
  * @array 
  */
  public $assignedValues = [];

  /*
  * @string
  */
  public $template;

  /*
  * @string
  */
  public $filename;

  public function __construct($filePath)
  {
    
    
    if( !file_exists($filePath) )
    {
        throw new \Shadowapp\Sys\Exceptions\View\ViewNotFoundException("View  does not exists", 1);
    }

      $this->template = file_get_contents($filePath);
      $this->filename = $filePath;
    
  }


  public function assign( array $setParams = [] )
  {
  	
      if(empty($setParams)) return;
       
      foreach($setParams as $key => $value) {
         $this->assignedValues[$key] = $value;
      }
  }



  public function run()
  {
     try 
  	 {
         /*
          * Set Rules 
          */
         // $this->parse()
         //      ->compile();
     //   var_dump($this->template);


        $matchedVariables = [];
        preg_match_all('/(?<=\^)([A-z|\w]+)?(?=\^)/i',$this->template,$matchedVariables);
        


         if (count(shcol(0,$matchedVariables))) {
           $this->parseTemplateVariables(shcol(0,$matchedVariables));
         }  



    die;

         $cacheDir = basePath().'sh_cache';
         $cache_file_name = md5($this->filename).".php";
         $cache_file = fopen($cacheDir.'/'.$cache_file_name,'w+');
  	     fwrite($cache_file,$this->template);
  	     fclose($cache_file);

  	     return $cacheDir.'/'.$cache_file_name;
 

  	 }catch(Exception $e){
        echo $e->getMessage();   
  	 }
  }

 public function parseTemplateVariables ( array $variables ) 
 {
    if ( !count($this->assignedValues) || !count($variables) ) {
       return ;
    }

    foreach ( $this->cleanVars( $variables ) as $variable ) {
      if (!array_key_exists($variable, $this->assignedValues)) continue;

      $this->parseVar( $variable );
    }
 }  


protected function parseVar ( string $variable ) 
{ 

  var_dump($username);
  switch ($this->assignedValues[$variable]) {
     case is_string($this->assignedValues[$variable]):
       $this->template = preg_replace('/\^username\^/', '<?php echo ; ?>', $this->template);
       break;
     
     default:
       # code...
       break;
   }

//var_dump();
   var_dump($this->template);
}


protected function cleanVars ( array $dirtyArray ) 
{
   return array_map(function ($vr) {
     return ( strpos($vr, '[') !== false ) ?
          substr($vr, 0, strpos($vr, '[')) :
          $vr;
     
   }, $dirtyArray);


}



  private function compile ()
  {
      foreach ($this->assignedValues as $key => $value) { //value == array
          
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
}


  protected function parse() 
  {
    $wholeString = explode('@',$this->template);

    foreach ( $wholeString as $key => $maybeKeyword ) {
      $contains = $this->stringContainsKeyword($maybeKeyword);
      if ( shcol('allowed',$contains) != true ) continue;
      
      $wholeString[$key] = ( shcol('isOpening',$contains) == true) 
              ? "<?php ".$maybeKeyword.": ?>" 
              :  "<?php ".$maybeKeyword."; ?>";
    }
       
    $this->template =  implode(' ',$wholeString);
    
    return $this;      
  }

  protected function stringContainsKeyword ( $maybeString ) 
  {
     if ( !is_string( $maybeString ) || empty( $maybeString ) ) return ['allowed' => false];
    
     foreach ( $this->allowedKeywords as $keyword ) {
        if (strpos($maybeString, $keyword) !== false) {
            $isOpening = (substr($keyword, strlen($keyword) - 1) == '(') ? true : false;
            return [
              'allowed' => true,
              'isOpening' => $isOpening 
            ];
        }
     }

     return ['allowed' => false];

  }

  protected function ruleExists($rule)
  {
     return (strpos($this->template, $rule) != false)
          ? true : false ;
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