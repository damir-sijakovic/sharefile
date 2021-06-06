<?php 

namespace Sharefile\System\Validate;

class Validate
{
    protected function validSize($data, $limit)
	{		
		$dataLen = strlen($data); 
		if ($dataLen > $limit)
		{
			return false;
		}
        
        return true;
	}

	protected function isString($data)
	{
		if (!is_string($data))
		{
			return false;
		}
        
        return true;
	}

    protected function allowedChars($string, $allowedChars)
	{
		for ($i=0; $i<strlen($string); $i++)
		{
			$checkPass = false;
			for ($j=0; $j<strlen($allowedChars); $j++)
			{
				if ($string[$i] === $allowedChars[$j])
				{
					$checkPass = true;
					break;
				}
			}
			
			if (!$checkPass)
			{
				return false;
			}
		}	
		return true;
	}	    
    
    
    
    protected function invalidChars($string, $invalidCharacters)
	{		
        if (false !== strpbrk($string, $invalidCharacters)) 
        {
            return true;
        }
        
        return false;
	}	    
    
};
