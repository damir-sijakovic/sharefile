<?php 

namespace Sharefile\System\ReturnObject;

/*

ok -    true, with data
fail -  false, for example db query search did not found anything  
error - bad type, validation fail...

*/


class ReturnOnce
{       
    
	public static function ok($data=null, $message=null) 
	{
        $returnObject = new ReturnObject();
        $returnObject->status = 'ok';
        $returnObject->message = $message;        
        $returnObject->data = $data;  
        return $returnObject;     
	}
    
	public static function fail($message=null, $data=null) 
	{
        $returnObject = new ReturnObject();
        $returnObject->status = 'fail';
        $returnObject->message = $message;        
        $returnObject->data = $data;  
        return $returnObject;     
	}
    
	public static function error($message=null, $data=null) 
	{
        if (DS_SF_MODE === 'development')
        {
            throw new \Exception($message); 
        }
        else
        {
            error_log('NOTICE: dsijak/sharefile -> ReturnOnce: '. $message);
        }
        
        $returnObject = new ReturnObject();
        $returnObject->status = 'error';
        $returnObject->message = $message;        
        $returnObject->data = $data;  
        return $returnObject;     
	}
       
	public static function isOk($obj, &$message=null) 
	{
        if (isset($obj->message))
        {
            $message = $obj->message;
        }
            
        if ($obj->status !== 'ok')
        {
            return false;
        }
    
        return true;
	}
       
	public static function isFail($obj, &$message=null) 
	{
        if (isset($obj->message))
        {
            $message = $obj->message;
        }
            
        if ($obj->status !== 'fail')
        {
            return false;
        }
    
        return true;
	}
       
	public static function debug($obj) 
	{
        error_log("ReturnOnce->debug(): " .  print_r($obj, true));
        return true;
	}
       

};
