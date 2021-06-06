<?php 

namespace Sharefile\System;

class Files 
{    
    public static function fileToBase64($fileName)
    {  
        if (file_exists( DS_SF_FILE_DIR .'/'. $fileName ))
        {
            $blob = file_get_contents(DS_SF_FILE_DIR .'/'. $fileName);
            return base64_encode($blob);
            
        }
        else
        {
            return null;   
        }
    }  
    
    public static function getFullPath($filename)
    {  
        $fullFilename = DS_SF_FILE_DIR .'/'. $filename;
        if (file_exists($fullFilename))
        {            
            return $fullFilename;
        }        
        else
        {
            return null;   
        }
    }  
    
    public static function fileExistsMd5($imageMd5) 
    {
        $imagefiles = glob(DS_SF_FILE_DIR . '/*'); 
        if (count($imagefiles) > 0)
        {
            foreach($imagefiles as $file)
            { 
                if (is_file($file))
                {
                    if (md5_file($file) === $imageMd5)
                    {
                        return true;
                    }
                }
            }  
            return false; 
        }
        else 
        {
            return null; 
        }
    }
    
}
    
    
    
