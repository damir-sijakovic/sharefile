<?php 

namespace Sharefile\System;

class Mimetype
{       
    public static function isFiletypeAllowed($type)
    {
        $allowedTypesKeys = array_keys(DS_SF_ALLOWED_MIMETYPES);
        if (!in_array($type, $allowedTypesKeys))
        {
            return false;
        }
        return true;
    }	
    
    public static function isFileTypeAllowedByFileName($type, $filename)
    {
        $allowedTypesKeys = array_keys(DS_SF_ALLOWED_MIMETYPES);
        
        if (!in_array($type, $allowedTypesKeys))
        {
            return false;
        }
        
        $length = strlen($type);
        
        if ( $length > 0 ? substr($filename, -$length) === $type : true )
        {
            return true;
        }
        else
        {
            return false;
        }
    }	
    
    
    
    public static function getMimetype($type)
    {
        if (!self::isFiletypeAllowed($type))
        {
            return null;
        }
        
        return DS_SF_ALLOWED_MIMETYPES[$type];
    }	
    
    
   
};
