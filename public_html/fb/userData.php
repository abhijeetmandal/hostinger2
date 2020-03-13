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
    //$prevQuery = "SELECT * FROM users_fb WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$userData->id."'";
    //$prevResult = $db->query($prevQuery);
    
    $prevQuery = "SELECT u.user_id, IFNULL(priv.site_priv, 1) as site_priv,u.username
                FROM users_oauth oauth
                INNER JOIN users u
                ON oauth.id = u.oauth_id
                LEFT JOIN users_priv priv
                ON u.user_id = priv.user_id
                WHERE oauth.uid = '".$userData->id."' AND oauth.provider = '".$oauth_provider."' LIMIT 1";

    $prevResult = $db->query($prevQuery);
    
    if($prevResult->num_rows > 0){ 
        //Update user data if already exists
        //$query = "UPDATE users_fb SET first_name = '".$userData->first_name."', last_name = '".$userData->last_name."', email = '".$userData->email."', gender = '".$userData->gender."', locale = '".$userData->locale."', picture = '".$userData->picture->data->url."', link = '".$userData->link."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$userData->id."'";
        
        //$query = "UPDATE users_fb SET first_name = 'A', last_name = 'B', email = 'C', gender = 'D', locale = 'E', picture = 'F', link = 'G', modified = now() WHERE oauth_provider = 'facebook' AND oauth_uid = 'J'";
        
        //$update = $db->query($query);
        while($row = $prevResult->fetch_assoc()) {
            $result['status']=true;
            $result['facebook'] = $row["user_id"];
            $result['fbusername'] = $row["username"];
        }
       
        
    }else{
        
        
        //Insert user data
        //$query = "INSERT INTO users_fb SET oauth_provider = '".$oauth_provider."', oauth_uid = '".$userData->id."', first_name = '".$userData->first_name."', last_name = '".$userData->last_name."', email = '".$userData->email."', gender = '".$userData->gender."', locale = '".$userData->locale."', picture = '".$userData->picture->data->url."', link = '".$userData->link."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
        
        //$query = "INSERT INTO users_fb SET oauth_provider = 'facebook', oauth_uid = 'B', first_name = 'C', last_name = 'D', email = 'E', gender = 'F', locale = 'G', picture = 'H', link = 'I', created = now(), modified = now()";             
        //$insert = $db->query($query);
        
        $query = "INSERT INTO users_oauth (`uid`, `provider`) VALUES ('".$userData->id."', '".$oauth_provider."')";
        $insert = $db->query($query);
        if (!$insert) {
            $result['status']='OAuth ID has already been registered';
            //return $result;
        }
        $oauth_id = $db->insert_id;
        
        $query2 = "INSERT INTO users (`username`, `oauth_id`, `email`, `verified`) VALUES ('". $userData->first_name ."-".$oauth_id."', '".$oauth_id."', '".$userData->email."', 1)";
        $insert2 = $db->query($query2);
        if (!$insert2) {
                $result['status'] = 'Username or email already registered';
                //return $result;
        }
        $uid = $db->insert_id;
        
        $query3 = "INSERT INTO users_profile (`user_id`, `name`, `show_name`, `gender`) VALUES ('". $uid ."','".$userData->name."', 0, NULL)";
        $insert3 = $db->query($query3);
        if(!$insert3){
            $result['status']='Error registring';
            //return $result;
        }
        
        $result['status']=true;
        $result['facebook'] = $uid;
        $result['fbusername'] = $userData->first_name ."-".$oauth_id;
        
    }
    
    $json = json_encode($result);
    // Remove null entries
    echo $json;
}
?>