<?php
    header('Content-Type: application/json');
    require_once('../../../files/init.php');

    //$userId = $app->user->uid;
    $result = array('status'=>true);


    $json = json_encode($result);
    // Remove null entries
    echo preg_replace('/,\s*"\[^"\]+":null|"\[^"\]+":null,?/', '', $json);
?>