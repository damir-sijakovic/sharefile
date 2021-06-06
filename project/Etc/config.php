<?php

//PROJECT NAME
define('DS_SF_PROJECT_NAME', 'MySiteProject');
define('DS_SF_MODE', 'development'); // production | development

//TIMEZONE
define('DS_SF_TIMEZONE', ''); // 'Europe/Zagreb'

//DIRECTORY WITH FILES - SELECT SOMTHING NOT ACCESSABLE FROM URL
define('DS_SF_FILE_DIR', DS_SF_WEBROOT_DIR . '/files' ); 

//NO KEY FOUND OR EXPIRED MESSAGE
define('DS_SF_INVALID_KEY_MESSAGE', 'Invalid file/key.'); 

//CLEANUP ON LOAD KEYS THAT ARE EXPIRED
define('DS_SF_CLEANUP_ON_LOAD', true); 

//DOWNLOAD FILE NAME FORMAT
define('DS_SF_DOWNLOAD_FILENAME', 'timestampname'); // name, timestamp, timestampname 

//KEY EXPIRES AT
define('DS_SF_KEY_EXPIRES_AT', '+30 minutes'); // strtotime syntax
define('DS_SF_KEY_SIZE', 8); 

//MODEL TYPE - sqlite, mariadb
define('DS_SF_MODEL_TYPE', 'sqlite'); 

//MARIADB SERVER CREDENTIALS
define('DS_SF_HOST',   '127.0.0.1'); 
define('DS_SF_USER',   'ds_sharefile');
define('DS_SF_PASS',   'ds_sharefile');
define('DS_SF_DBNAME', 'ds_sharefile');


//ALLOWED TYPES
define('DS_SF_ALLOWED_MIMETYPES', [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    
    'zip'  => 'application/zip',    
    'pdf'  => 'application/pdf',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',  
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',  
    'mp4'  => 'video/mp4',
]);



