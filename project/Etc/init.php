<?php

if (!extension_loaded('sqlite3')) {
    $msg = 'ERROR: dsijak/sharefile -> Sqlite3 extension not loaded!';
    error_log($msg);
    die($msg);
}

/*
if (!extension_loaded('pdo_mysql')) {
    $msg = 'ERROR: dsijak/sharefile -> pdo_mysql extension not loaded!';
    error_log($msg);
    die($msg);
}

if (!extension_loaded('pdo_sqlite')) {
    $msg = 'ERROR: dsijak/sharefile -> pdo_sqlite extension not loaded!';
    error_log($msg);
    die($msg);
}
*/

require_once(__DIR__ . '/consts.php');
require_once(__DIR__ . '/config.php');

if (DS_SF_TIMEZONE !== '')
{
    date_default_timezone_set(DS_SF_TIMEZONE); 
}

if (DS_SF_MODE === 'development')
{
    error_log('NOTICE: dsijak/sharefile -> You are running in development mode! Change DS_SF_MODE in "Sharefile/Etc/config.php".');
    
    ini_set('display_errors', true); 
    ini_set('log_errors', true); 
    ini_set('error_reporting', E_ALL); 
}

if (DS_SF_FILE_DIR === '') 
{
    $msg = 'ERROR: dsijak/sharefile -> Set valid work directory DS_SF_FILE_DIR constant in "Sharefile/Etc/config.php" file!';
    error_log($msg);
    die($msg);
}

if (!file_exists(DS_SF_FILE_DIR)) {
    $msg = 'ERROR: dsijak/sharefile -> Set valid work directory DS_SF_FILE_DIR constant in "Sharefile/Etc/config.php" file!';
    error_log($msg);
    die($msg);
}

