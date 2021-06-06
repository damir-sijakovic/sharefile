<?php 

namespace Sharefile\System;


class Datetime 
{    
	public static function getDatetime()
	{			
		return date("Y-m-d H:i:s");            
	} 
	
	public static function sqlDatetimeToUnixTime($a)
	{			
		return strtotime($a);        
	} 
    
}
    
    
    
