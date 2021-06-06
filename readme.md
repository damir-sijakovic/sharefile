# Sharefile

## Share files protected with access token.

Before use configure `vendor/dsijak/sharefile/project/Etc/config.php`.   
Setting `DS_SF_MODE` mode to `production` will disable browser error log output.   


### Install

            composer require dsijak/sharefile

### Usage

First set directory with files in `config.php => DS_SF_FILE_DIR`. This    
directory should be not accessable via url.     

Acquire file item:

            $accessToken = $sharefile->acquireItem('vid.mp4');

File must exist and allowed filetype must be specified in `config.php`.     
`acquireItem` method will store file, key and keyExpiration data into     
sqlite/mariadb database. `acquireItem` method returns access token or key.    
After this, you can send this key/token to whomever you want to have access     
to acquired file.     

Obtain file item:
 
            $sharefile->obtainItem('vid.mp4', 'f8b32a778acfaaaa');

This will generate new file with header inside your browser.     
`obtainItem` method must be executed from new route or after redirected.     
If key has expired, only text message will be generated.    

### What is this useful for?

For example, when you are selling digital items. When someone buys your     
photography or ebook, you can send them file key. Key will be valid    
only for amount of time you specify in the `config.php`.   

For example, you can mail link `yoursite.com/obtainItem?filename=vid.mp4&key=f8b32a778acfaaaa`
Then you can take file and key params:  

            $sharefile->obtainItem($filename, $key);

This will generate acquired document or text document with message.    

### Error messages

Both `acquireItem` and `obtainItem` methods accept additional reference     
argument to pass back error message.    

            $errorMessage = '';
            $accessToken = $sharefile->acquireItem('ebook.pdf', $errorMessage);

            if (!$accessToken)
            {
                error_log($errorMessage);
            }

### Short usage example 

            use Sharefile\Sharefile;
            
            $sharefile = new Sharefile();
            $errorMessage = '';
            $accessToken = $sharefile->acquireItem('vid.mp4', $errorMessage);
            if (!$accessToken)
            {
                error_log($errorMessage);
            }

            $sharefile->obtainItem('vid.mp4', $accessToken);
            //renders file


### MariaDB Usage

Run this query:

            CREATE DATABASE ds_sharefile; 
            CREATE USER 'ds_sharefile'@'localhost' IDENTIFIED BY 'ds_sharefile';
            GRANT ALL ON ds_sharefile.* TO 'ds_sharefile'@'localhost';
            USE ds_sharefile;

Edit: `vendor/dsijak/sharefile/project/Etc/config.php`.    
Set `DS_SF_MODEL_TYPE` to `mariadb`.   
Database user/pass are also set there.   
To use existing PDO object, pass it to constructor together with database name:  

            $sharefile = new Sharefile($pdoObj, 'myDatabaseName');

### Advanced methods

These methods returns data/null or true/false.   
You can get advanced methods through `advanced` property:

            $x = $sharefile->advanced->databaseCreatedAt($errorMessage);

#### Methods:

**fileExists($filename, &$eMessage=null)**    
Checks if file exists.
  
**emptyDatabase(&$eMessage=null)**    
Removes all aquired items from database.
  
**databaseCreatedAt(&$eMessage=null)**   
Returns creation date.
   
**generateFileKey($filename, &$eMessage=null)**     
Generates key for filename and stores everything.

**deleteKey($key, &$eMessage=null)**     
Deletes key, if exists.
   
**getGeneratedFileKeyRecord($filename, $key, &$eMessage=null)**    
Returns key/filename, if exists.
 
**isKeyValid($filename, $key, &$eMessage=null)**    
Checks if key/token is valid.
    
**generateBase64ImageFile($filename, &$eMessage=null)**   
Generates html img element with base64 image. This is ment to be used with images.
  
**generateHeaderFile($filename)**       
Generates file as HTTP respose.

**generateHeaderMessage($message)**     
Generates text file as HTTP respose with $message.


### Have Fun
`Developed and tested under 5.4.118-1-manjaro/xfce/docker/portainer`
