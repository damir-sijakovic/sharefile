<?php

namespace Sharefile\System;
use Sharefile\System\ReturnObject\ReturnOnce;
use Sharefile\System\Validate\Validate;
use Sharefile\System\Helpers;

class SharefileAdvancedValidate extends Validate
{   
    public function isFilenameValid($filename)
    {
        if (!$this->isString($filename))
        {
            return ReturnOnce::fail('Argument is not of string type.');
        }
  
        if ($this->invalidChars($filename, '|\'\\?*&<";:>+[]=/'))
        {
            return ReturnOnce::fail('Argument is not valid filename.');
        }      
        
        if (!$this->validSize($filename, 255))
        {
            return ReturnOnce::fail('Argument filename string is too long.');
        }
        
        $allowedTypesKeys = array_keys(DS_SF_ALLOWED_MIMETYPES);
        if (!in_array(pathinfo($filename, PATHINFO_EXTENSION), $allowedTypesKeys))
        {
            
            return ReturnOnce::fail('Bad or missing file extension!');
        }

        return ReturnOnce::ok();    
    }
    
    
    
    public function fileExists($filename)
    {
        if (!$this->isString($filename))
        {
            return ReturnOnce::fail('Argument is not of string type.');
        }
  
        if ($this->invalidChars($filename, '|\'\\?*&<";:>+[]=/'))
        {
            return ReturnOnce::fail('Argument is not valid filename.');
        }      
        
        if (!$this->validSize($filename, 255))
        {
            return ReturnOnce::fail('Argument filename string is too long.');
        }

        return ReturnOnce::ok();    
    }
    
    public function generateFileKey($filename)
    {
        if (!$this->isString($filename))
        {
            return ReturnOnce::fail('Argument is not of string type.');
        }
  
        if ($this->invalidChars($filename, '|\'\\?*&<";:>+[]=/'))
        {
            return ReturnOnce::fail('Argument is not valid filename.');
        }      
        
        if (!$this->validSize($filename, 255))
        {
            return ReturnOnce::fail('Argument filename string is too long.');
        }
                
        $allowedTypesKeys = array_keys(DS_SF_ALLOWED_MIMETYPES);
        if (!in_array(pathinfo($filename, PATHINFO_EXTENSION), $allowedTypesKeys))
        {
            
            return ReturnOnce::fail('Bad or missing file extension!');
        }
        
        return ReturnOnce::ok();    
    }

    public function getGeneratedFileKeyRecord($filename, $key)
    {
        if (!$this->isString($filename))
        {
            return ReturnOnce::fail('Argument "filename" is not of string type.');
        }
  
        if ($this->invalidChars($filename, '|\'\\?*&<";:>+[]=/'))
        {
            return ReturnOnce::fail('Argument "filename" is not valid filename.');
        }      
        
        if (!$this->validSize($filename, 255))
        {
            return ReturnOnce::fail('Argument "filename" string is too long.');
        }

        $keysize = DS_SF_KEY_SIZE * 2;

        if (!(strval(strlen($key)) !== $keysize))
        {
            return ReturnOnce::fail('Argument "key" length is invalid.');
        }

        return ReturnOnce::ok();    
    }
    
    public function isKeySizeOk($key)
    {
        $configKeyLength = DS_SF_KEY_SIZE * 2;
        $keyLength = strlen($key);
        
        if ($keyLength !== $configKeyLength)
        {
            return ReturnOnce::fail('Key string length is invalid.');
        }
        
        return ReturnOnce::ok();  
    }


}
