<?php

namespace Sharefile;
use Sharefile\System\SharefileAdvanced;

class Sharefile implements SharefileInterface
{
    public $advanced;
    
    public function __construct(\PDO $externalPdo=null, $dbname=null) 
    {  
        if (isset($externalPdo) && !isset($dbname))
        {
            $msg = 'ERROR: dsijak/sharefile -> Constructors second argument, "$dbname", must be set!';
            if (DS_SF_MODE === 'development')
            {                
                error_log($msg);
                die($msg);
            }
            else
            {
                error_log($msg);
            }            
        }
        
        $this->advanced = new SharefileAdvanced($externalPdo, $dbname);
    }
        
    public function acquireItem($filename, &$eMessage=null)
    {  
        return $this->advanced->generateFileKey($filename, $eMessage); 
    }
    
    public function obtainItem($filename, $key, &$eMessage=null)
    {  
        if (!$this->advanced->isKeyValid($filename, $key, $eMessage))
        {
            if ($eMessage === 'Item or key not in database.')
            {
                $this->advanced->generateHeaderMessage(DS_SF_INVALID_KEY_MESSAGE);
            }
            $this->advanced->generateHeaderMessage($eMessage);
        }
                
        return $this->advanced->generateHeaderFile($filename, $eMessage);
    }
    
    
}
