<?php

namespace Sharefile;

interface SharefileInterface
{        
    public function acquireItem($filename, &$eMessage=null);    
    public function obtainItem($filename, $key, &$eMessage=null);
}
