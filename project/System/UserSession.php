<?php 

namespace Sharefile\System;
use Sharefile\System\Session\Session;

class UserSession extends Session
{    
    public function __construct()
    {           
        parent::__construct('_DSIJAK_SHAREFILE_USER_SESSION_');
    }
   
 
}
    
    
    
