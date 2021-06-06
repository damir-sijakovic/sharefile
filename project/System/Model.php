<?php 

namespace Sharefile\System;
use Sharefile\System\ReturnObject\ReturnOnce;
use Sharefile\System\Datetime;

class Model
{    
    private $pdo;
    
    private function initDatabase()
    {
        if (!file_exists(DS_SF_PATH_DB_FILE))
        {
            error_log('dsijak/sharefile : Creating database!');
            
            $this->pdo = new \PDO('sqlite:' . DS_SF_PATH_DB_FILE);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);       
            $sql = file_get_contents(DS_SF_PATH_SQL_FILE);
            $this->pdo->exec('PRAGMA foreign_keys = ON');            
            $this->pdo->exec($sql);

            $this->pdo->query( " INSERT INTO sharefile ( databaseCreatedAt ) VALUES ( datetime('now') ) ");

        }
        else
        {
            $this->pdo = new \PDO('sqlite:' . DS_SF_PATH_DB_FILE);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);       
        }
    }    
    

    public function __construct()
    {
        $this->initDatabase(); 
    } 
    
  
    public function storeGeneratedFileKey($filename, $key)
    { 
        $data = [
            'filename' => $filename,
            'key' => $key,
            'expiresAt' => strtotime(DS_SF_KEY_EXPIRES_AT),
        ];

        $sql = " INSERT INTO files ( filename , key , expiresAt ) VALUES ( :filename , :key , :expiresAt ) ";
        $stmt = $this->pdo->prepare($sql);  
        
        try 
        {  
            $stmt->execute($data);                 
            return ReturnOnce::ok(intval($this->pdo->lastInsertId()));
        }
        catch (\PDOException $e)
        {           
           return ReturnOnce::error('Model:storeGeneratedFileKey() => ' . $e);
        }  
    } 
    
    public function getGeneratedFileKeyRecord($filename, $key)
    { 
        $data = [
            'filename' => $filename,
            'key' => $key,
        ];

        $sql = " SELECT * FROM files WHERE filename = :filename AND key = :key ";
        $stmt = $this->pdo->prepare($sql);  
        
        try 
        {  
            if ($stmt->execute($data))
            {      
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if ($result)
                {
                    return ReturnOnce::ok($result);
                }
                else
                {
                    return ReturnOnce::fail('Item or key not in database.');
                }
            }
        }
        catch (\PDOException $e)
        {           
           return ReturnOnce::error('Model:getGeneratedFileKeyRecord() => ' . $e);
        }  
    } 
    
    public function cleanUp()
    {        
        $sql = " DELETE FROM files WHERE expiresAt < strftime('%s', 'now') ";
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
            return ReturnOnce::error('Model:cleanUp() => ' . $e); 
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
    
    
    public function emptyDatabase()
    {
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
                   return ReturnOnce::fail('Nothing was removed from db.'); 
                }
            }
        }
        catch (\PDOException $e)
        {           
            return ReturnOnce::error('Model:emptyDatabase() => ' . $e); 
        }  
    } 
    
    
    public function databaseCreatedAt()
    { 
        $sql = " SELECT databaseCreatedAt FROM sharefile WHERE id = 1 ";
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
    
};
