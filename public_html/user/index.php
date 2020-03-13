<?php
//echo "sss1";
    $custom_css = array('profile.scss', 'highlight.css', 'confirm.css');
    $custom_js = array('jquery.confirm.js', 'highlight.js', 'profile.js');
//echo "realpath ".  realpath("./") ."<br/>";
    require_once('../../files/init.php');
//echo "user: ".$_GET['user']."<br/>";

    $profile = new profile($_GET['user']);

    //echo "uid0.1: ".$profile->uid."<br/>";
    
    if (isset($profile->uid))
        $app->page->title = $profile->username;

    if (isset($_GET['image'])) {
        require('userbar.php');
        die();
    }
//echo "userbar.php <br/>";
    //echo "uid0.2: ".$profile->uid."<br/>";
    
    require_once('../../files/header.php');

//echo "header.php <br/>";
    //echo "uid0.3: ".$profile->uid."<br/>";
    
    if (!isset($profile->uid)):
        $app->utils->message("User not found");
        require_once('../../files/footer.php');
        die();
    endif;
//echo "User not found <br/>";  
    //echo "uid0.4: ".$profile->uid."<br/>";
    
    if (isset($_GET['friends']) && count($profile->friendsList)):

        //echo "uid1: ".$profile->uid."<br/>";
        //echo "uid1: ".$profile->uid."<br/>";
        //echo "uid1: ".$profile->uid."<br/>";
        /* FRIENDS LIST STARTS */
        $profile->getDob = $profile->getDob();
        echo $app->twig->render('profile_friends.html', array('profile' => $profile));

    else:

        //echo "uid2: ".$profile->uid."<br/>";
        /* USERS PROFILE STARTS */
        $profile->getDob = $profile->getDob();
        shuffle($profile->friendsList);
        echo $app->twig->render('profile.html', array('profile' => $profile));

    endif;

    require_once('../../files/footer.php');
?>