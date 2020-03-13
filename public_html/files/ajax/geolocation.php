<?php
    header('Content-Type: application/json');
    require_once('../../../files/init.php');

    $userId = $app->user->uid;
    $result = array('status'=>false);

    
    if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
        //send request and receive json data by latitude and longitude
        $latitude = trim($_POST['latitude']);
        $longitude = trim($_POST['longitude']);
        $result['status'] = true;
        //$result['items'] = $app->geolocations->setLocation($userId,$latitude,$latitude);
        $result['items'] = $app->geolocations->setLocation(17,10,20);
    }

    $json = json_encode($result);
    // Remove null entries
    echo preg_replace('/,\s*"[^"]+":null|"\[^"\]+":null,?/', '', $json);
?>