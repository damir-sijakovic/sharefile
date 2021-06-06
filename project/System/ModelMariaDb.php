<?php 

namespace Sharefile\System;
use Sharefile\System\ReturnObject\ReturnOnce;
use Sharefile\System\Datetime;
use Sharefile\System\Helpers;

class ModelMariaDb
{    
    private $pdo;
    
    private function initDatabase($externalPdo=null, $dbname=null)
    {
   
        if (isset($externalPdo))
        {
            $this->pdo = $externalPdo;
        }
        else
        {     
            $dbname = DS_SF_DBNAME;  
                         
            $dsn = 'mysql:host=' . DS_SF_HOST . ';dbname=' . DS_SF_DBNAME;		
            try
            {
                $this->pdo = new \PDO($dsn, DS_SF_USER, DS_SF_PASS);
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC); 			
            }
            catch (\PDOException $e) 
            {         
                $msg = 'ERROR: dsijak/sharefile -> Database Connection Message: '. $e->getMessage();
                error_log($msg);
                die($msg);
            } 	
        }

        //IS DATABASE EMPTY?
        $stmt = $this->pdo->query('SHOW TABLES FROM '. $dbname .' ;');			
        if (!$stmt->rowCount())
        {
            //IT IS EMPTY
            $sqlFile = file_get_contents(DS_SF_PATH_MARIADB_SQL_FILE);    
            
            try 
            {  
                $this->pdo->query($sqlFile);
                $this->pdo->query( " INSERT INTO sharefile ( `databaseCreatedAt` ) VALUES ( NOW() ) ");
            } 
            catch (\PDOException $e)
            {
            $msg = 'ERROR: dsijak/sharefile -> Database Connection Message: '. $e->getMessage();
            error_log($msg);
            die($msg);
            }
        }
                

    }    
    

    public function __construct($externalPdo=null, $dbname=null)
    {
        $this->initDatabase($externalPdo, $dbname); 
    } 
    
  
    public function storeGeneratedFileKey($filename, $key)
    { 
        $data = [
            'filename' => $filename,
            'key' => $key,
            //'expiresAt' => strtotime(DS_SF_KEY_EXPIRES_AT),
            'expiresAt' => date('Y-m-d H:i:s', strtotime(DS_SF_KEY_EXPIRES_AT)),
        ];
               

        $sql = " INSERT INTO files ( `filename` , `key` , `expiresAt` ) VALUES ( :filename , :key , :expiresAt ) ";
        $stmt = $this->pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);                 
            return ReturnOnce::ok(intval($this->pdo->lastInsertId()));
        }
        catch (\PDOException $e)
        {           
           return ReturnOnce::error('ModelMariaDb:storeGeneratedFileKey() => ' . $e);
        }  
    } 
    
    public function getGeneratedFileKeyRecord($filename, $key)
    { 
        $data = [
            'filename' => $filename,
            'key' => $key,
        ];

        $sql = " SELECT * FROM `files` WHERE `filename` = :filename AND `key` = :key ";
        $stmt = $this->pdo->prepare($sql);  
        
    
        try 
        {  
            if ($stmt->execute($data))
            {      
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                if ($result && isset($result[0]))
                {
                    if (isset($result[0]['expiresAt']))
                    {
                        $validDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $result[0]['expiresAt']);
                        
                        if ($validDatetime)
                        {
                            $result[0]['expiresAt'] = $validDatetime->getTimestamp();
                            return ReturnOnce::ok($result);
                        }
                        
                        return ReturnOnce::fail('Bad time format in database.');  
                    }
                      
                    return ReturnOnce::fail('Double keys in database.');  
                }
                else
                {
                    return ReturnOnce::fail('Item or key not in database.');
                }
            }
        }
        catch (\PDOException $e)
        {           
           return ReturnOnce::error('ModelMariaDb:getGeneratedFileKeyRecord() => ' . $e);
        }  
    } 
    
    public function deleteKey($key)
    { 

        $data = [
            'key' => $key  
        ];

        $sql = " DELETE FROM `files` WHERE `key` = :key ";        
        $stmt = $this->pdo->prepare($sql);  
        
        try 
        {  
            if ($stmt->execute($data))
            {
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0)
                {
                    return ReturnOnce::ok($stmt->rowCount());
                }
                else
                {
                   return ReturnOnce::fail('Nothing was deleted.'); 
                }
            }
        }
        catch (\PDOException $e)
        {           
            return ReturnOnce::error('ModelMariaDb:deleteKey() => ' . $e);
        } 
  
    } 
    
    public function databaseCreatedAt()
    { 
        $sql = " SELECT `databaseCreatedAt` FROM `sharefile` WHERE `id` = 1 ";
        $stmt = $this->pdo->prepare($sql);  
                
        try 
        {  
            if ($stmt->execute())
            {      
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                if ($result && isset($result[0]))
                {
                    if (isset($result[0]['databaseCreatedAt']))
                    {                        
                        $validDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $result[0]['databaseCreatedAt']);
                        
                        if ($validDatetime)
                        {
                            $result[0]['databaseCreatedAt'] = $validDatetime->getTimestamp();
                            return ReturnOnce::ok($result);
                        }
                        
                        return ReturnOnce::ok($result);
                    }                      
                    return ReturnOnce::fail('Double keys in database.');  
                }
                else
                {
                    return ReturnOnce::fail('Item or key not in database.');
                }
            }
        }
        catch (\PDOException $e)
        {           
           return ReturnOnce::error('ModelMariaDb:databaseCreatedAt() => ' . $e);
        }  
    } 
    
  
    public function emptyDatabase()
    {
        //Delete from Student
        $sql = " DELETE FROM files ";
        $stmt = $this->pdo->prepare($sql);  
        
        try 
        {  
            if ($stmt->execute())
            {      
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0)
                {
                    return ReturnOnce::ok($stmt->rowCount());
                }
                else
                {
                   return ReturnOnce::fail('Nothing was deleted.'); 
                }
            }
        }
        catch (\PDOException $e)
        {           
            return ReturnOnce::error('ModelMariaDb:emptyDatabase() => ' . $e); 
        }  
    } 
    
    
    public function cleanUp()
    {        
        $sql = " DELETE FROM files WHERE expiresAt < NOW() ";
        $stmt = $this->pdo->prepare($sql);  
        
        try 
        {  
            if ($stmt->execute())
            {      
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0)
                {
                    return ReturnOnce::ok($stmt->rowCount());
                }
                else
                {
                   return ReturnOnce::fail('Nothing was deleted.'); 
                }
            }
        }
        catch (\PDOException $e)
        {           
            return ReturnOnce::error('ModelMariaDb:cleanUp() => ' . $e); 
        }  
    } 
    
    
};
