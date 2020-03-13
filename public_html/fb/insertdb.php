<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'dbconfig.php';

    
    $query = "INSERT INTO users_fb SET oauth_provider = 'facebook', oauth_uid = 'B', first_name = 'C', last_name = 'D', email = 'E', gender = 'F', locale = 'G', picture = 'H', link = 'I', created = now(), modified = now()";
        
        $insert = $db->query($query);
?>
