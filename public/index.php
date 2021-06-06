<?php

date_default_timezone_set('Europe/Zagreb'); 

require_once __DIR__ ."/../vendor/autoload.php";
use Sharefile\Sharefile;


$message = '';
$sharefile = new Sharefile();

$token = $sharefile->acquireItem('book01.pdf', $message);

//$sharefile->advanced->emptyDatabase($message);

$sharefile->obtainItem('book01.pdf', $token, $message);

error_log(print_r($message, true));

