<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class
 *
 * @author abhijeet
 */
class log {
//put your code here
    function __construct($app)
    {
        $this->app=$app;
    }
    
    function add($file,$line){
        $file = preg_replace('/\[^A-Za-z0-9_-\]/','',$file);
        
        $line = '['.date('c').'] '.$_SERVER['REMOTE_ADDR'].' - '.$line."\n";
        
        if(($fp=@fopen('../files/logs/'.$file,'a'))!==false){
            fwrite($fp,$line);
            fclose($fp);
        }
    }
}
?>