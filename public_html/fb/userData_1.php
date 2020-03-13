<?php
header('Content-Type: application/json');
//Load the database configuration file
include './dbconfig.php';


//echo "inside ajax user.php";
//require_once('../../files/init.php');

//Convert JSON data into PHP variable
$userData = json_decode($_POST['userData']);

$result = array("status"=>false);

if(!empty($userData)){
    $oauth_provider = $_POST['oauth_provider'];
    //Check whether user data already exists in database
    $prevQuery = "SELECT * FROM users_fb WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$userData->id."'";
    $prevResult = $db->query($prevQuery);
    
    if($prevResult->num_rows > 0){ 
        //Update user data if already exists
        //$query = "UPDATE users_fb SET first_name = '".$userData->first_name."', last_name = '".$userData->last_name."', email = '".$userData->email."', gender = '".$userData->gender."', locale = '".$userData->locale."', picture = '".$userData->picture->data->url."', link = '".$userData->link."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$userData->id."'";
        
        $query = "UPDATE users_fb SET first_name = 'A', last_name = 'B', email = 'C', gender = 'D', locale = 'E', picture = 'F', link = 'G', modified = now() WHERE oauth_provider = 'facebook' AND oauth_uid = 'J'";
        
        $update = $db->query($query);
    }else{
        
        
        //Insert user data
        //$query = "INSERT INTO users_fb SET oauth_provider = '".$oauth_provider."', oauth_uid = '".$userData->id."', first_name = '".$userData->first_name."', last_name = '".$userData->last_name."', email = '".$userData->email."', gender = '".$userData->gender."', locale = '".$userData->locale."', picture = '".$userData->picture->data->url."', link = '".$userData->link."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
        
        $query = "INSERT INTO users_fb SET oauth_provider = 'facebook', oauth_uid = 'B', first_name = 'C', last_name = 'D', email = 'E', gender = 'F', locale = 'G', picture = 'H', link = 'I', created = now(), modified = now()";             
        $insert = $db->query($query);
        
    }
    
    $json = json_encode($result);
    // Remove null entries
    echo $json;
}
?>