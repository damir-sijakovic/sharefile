<?php

namespace Sharefile\System;
use Sharefile\System\ReturnObject\ReturnOnce;
use Sharefile\System\SharefileAdvancedValidate;
use Sharefile\System\Model;
use Sharefile\System\ModelMariaDb;
use Sharefile\System\Files;
use Sharefile\System\Mimetype;


class SharefileAdvanced  
{    
    public $validate;
    public $model;
    
    public function __construct($externalPdo=null, $dbname=null) 
    {
        $this->validate = new SharefileAdvancedValidate();
        
        if (DS_SF_MODEL_TYPE === 'mariadb')
        {
            $this->model = new ModelMariaDb($externalPdo, $dbname);
        }
        else if (DS_SF_MODEL_TYPE === 'sqlite')
        {
            $this->model = new Model();       
        }
            
        if (DS_SF_CLEANUP_ON_LOAD)
        {            
            $this->model->cleanUp();
        }
    }
    
    public function fileExists($filename, &$eMessage=null)
    {             
        $returnObj = $this->validate->isFilenameValid($filename);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {            
            return false;
        } 
        
        if (file_exists(DS_SF_FILE_DIR .'/'. $filename))
        {
            return true;
        }
        else
        {
            $eMessage = 'File not found.';
            return false;
        }
        
    }
    
    public function emptyDatabase(&$eMessage=null)
    {             
        $returnObj = $this->model->emptyDatabase();
        $eMessage = $returnObj->message;
        if (ReturnOnce::isOk($returnObj))
        {            
            return true;
        } 
        else
        {
            return false;
        }
        
    }
    
    public function databaseCreatedAt(&$eMessage=null)
    {             
        $returnObj = $this->model->databaseCreatedAt();
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {            
            return null;
        } 
        else
        {
            if (isset($returnObj->data) && isset($returnObj->data[0]) && isset($returnObj->data[0]['databaseCreatedAt']))
            {
                return $returnObj->data[0]['databaseCreatedAt'];
            }
            
            $eMessage = '"databaseCreatedAt" value is not set in database.';
            return null;
        }
        
    }
    
    
    public function generateFileKey($filename, &$eMessage=null)
    {     
         
        $returnObj = $this->validate->generateFileKey($filename);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {   
            
            return null;
        }
       
        if (file_exists(DS_SF_FILE_DIR .'/'. $filename))
        {            
            $key = bin2hex(openssl_random_pseudo_bytes(DS_SF_KEY_SIZE));
            $returnObj = $this->model->storeGeneratedFileKey($filename, $key);
            $eMessage = $returnObj->message;
            
            if (ReturnOnce::isOk($returnObj))
            {           
                return $key;
            }
            
            return null;
        }
        else
        {
            $eMessage = 'File not found.';
            return null;
        }
        
    }
    
    public function deleteKey($key, &$eMessage=null)
    {  
        $returnObj = $this->validate->isKeySizeOk($key);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {               
            return false;
        }
       
        $returnObj = $this->model->deleteKey($key);
        $eMessage = $returnObj->message;
        
        if (ReturnOnce::isOk($returnObj))
        {           
            return true;
        }
        else
        {
            return false;
        }
        
    }
    
    public function getGeneratedFileKeyRecord($filename, $key, &$eMessage=null)
    {     
        
        $returnObj = $this->validate->getGeneratedFileKeyRecord($filename, $key);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {           
            return false;
        }
                
        $returnObj = $this->model->getGeneratedFileKeyRecord($filename, $key);
        $eMessage = $returnObj->message;
        
        if (ReturnOnce::isOk($returnObj))
        {           
            return $returnObj->data;
        }
        else
        {
            return false;
        }
        
    }
 
    
    public function isKeyValid($filename, $key, &$eMessage=null)
    {     
        
        $returnObj = $this->validate->getGeneratedFileKeyRecord($filename, $key);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {           
            return false;
        }
                
        $returnObj = $this->model->getGeneratedFileKeyRecord($filename, $key);
        $eMessage = $returnObj->message;
        
        if (ReturnOnce::isOk($returnObj))
        {           
            if (isset($returnObj->data) && isset($returnObj->data[0]) && isset($returnObj->data[0]['expiresAt']))
            {
                if (intval($returnObj->data[0]['expiresAt']) < time())
                {
                    $eMessage = 'Key has expired.';
                    return false;
                }
                
                if ($returnObj->data[0]['key'] === $key)
                {
                    return true;
                }
                else
                {
                    $eMessage = 'Key is not valid.';
                    return false;
                }
            }
                
        }
        else
        {
            return false;
        }
        
    }
 
    
    public function generateBase64ImageFile($filename, &$eMessage=null)
    {
        if (!$this->fileExists($filename))
        {
            $eMessage = 'File not found!';
            return false;
        }
        
        $returnObj = $this->validate->isFilenameValid($filename);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {            
            return false;
        } 
           
        $filetype = pathinfo($filename, PATHINFO_EXTENSION); 
        $base64 = Files::fileToBase64($filename);
        $mimetype = Mimetype::getMimetype($filetype);

        return '<img id="sharefile-output-file" src="data:'. $mimetype .';base64, '. $base64 .'" alt="'.$filename.'" /> ;';         
    }
    
    
    public function generateHeaderFile($filename)
    {
        if (!$this->fileExists($filename))
        {
            $this->generateHeaderMessage('File not found!');
            return;
        }
        
        $returnObj = $this->validate->isFilenameValid($filename);
        $eMessage = $returnObj->message;
        if (!ReturnOnce::isOk($returnObj))
        {        
            $this->generateHeaderMessage('Invalid filename.');    
            return;
        } 
        
        $filetype = pathinfo($filename, PATHINFO_EXTENSION); 
        $mimetype = Mimetype::getMimetype($filetype);
        
        
        if (DS_SF_DOWNLOAD_FILENAME === 'timestamp')
        {
            $headerFilename = strval(time()) . '.' . $filetype;
        }
        elseif (DS_SF_DOWNLOAD_FILENAME === 'timestampname')
        {
            $headerFilename = strval(time()) .'_'. $filename;
        }
        else
        {
            $headerFilename = $filename;
        }

        header('Content-Disposition: inline; filename="'. $headerFilename .'"');        
        
        header('Last-Modified: '. gmdate('D, d M Y H:i:s', filemtime(Files::getFullPath($filename))).' GMT', true, 200);
        header('Content-Length: '.filesize(Files::getFullPath($filename))); 
        header('Content-Type: '. $mimetype); 
        readfile(Files::getFullPath($filename));        
        
        die();
    }
    
   
    public function generateHeaderMessage($message)
    {        
        header("Content-Type: text/plain"); 
        echo $message;               
        die();
    }
    
   
    
}
