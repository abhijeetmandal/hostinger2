<?php
//echo "realpath: ".  realpath("./") ."<br/>";
	if (!isset($_GET['challenge']))
		header('Location: /challenges/');

    $custom_css = array('challenges.scss', 'highlight.css');
    $custom_js = array('challenges.js', 'highlight.js');
    require_once('../../files/init.php');

    //Load challenge
    $group = $_GET['group'];
    if ($group === 'basic ') {
        $group = 'basic+';
    }
    
    $currentChallenge = $app->challenges->getChallenge($group, $_GET['challenge']);
    
    //echo "username: ". $app->user->username."<br/>";
    //echo "avail.gdlink: ". $app->user->gdlink."<br/>";

    if (!$currentChallenge) {
		require_once('../../files/header.php');
		$app->utils->message('Challenge not found, <a href="/challenges">return to index</a>');
		require_once('../../files/footer.php');
		die();
    }

    if (isset($_GET['get-hint'])) {
        if (isset($currentChallenge->data['hint']))
            echo json_encode(array('status'=>true, 'hint' => $app->utils->parse($currentChallenge->data['hint'])));
        else
            echo json_encode(array('status'=>false));
        die();
    }

    
    //echo "form: ". $currentChallenge->data['form'] . "<br/>";
    
    //Check if user completed challenge
    if (isset($currentChallenge->data['form']) && $page = realpath($app->config['path'] . '/files/elements/challenges/'.basename($currentChallenge->data['form']).'_logic.php')) {
        include($page);
        //echo "page: ". $page . "<br/>";
    } else {
        $app->challenges->check($currentChallenge);
        //echo "check challenge: ". $app->challenges->check($currentChallenge) . "<br/>";
    }

	require_once('../../files/header.php');

	$challenge = $currentChallenge;
?>
	<div class='row'>
		<div class='col span_6 challenge-sidebar'>
<?php
    require_once('../../files/elements/challenges/stats.php');
?>
		</div>

		<div class='col span_18 challenge-area'>
<?php

//echo "cn: ".$challenge->name ."<br/>";
    require_once('../../files/elements/challenges/header.php');
    //require_once('../../files/elements/challenges/gdrive.php');
    require_once('../../files/elements/challenges/challenge.php');
    
    $usermodel = "../files/models/".$app->user->username.".json";
    $trainedmodel = "../files/models/".$app->user->username."_trained.json";
    $model="";
    
    if ($challenge->name >= 1 && $challenge->name <= 10 && file_exists($usermodel)) {
        $model="https://crushit.fit/files/models/".basename($usermodel);
        $app->utils->message("Your trained model is being loaded.","good");
        
        require_once('../../files/elements/challenges/predictor.php');
        
    } else if($challenge->name >= 1 && $challenge->name <= 10 && !file_exists($usermodel)){
        $app->utils->message("To take the challenge you must first complete the Plank Level 1. <a href='/levels/plank/1/'>Click here!!!</a>");
    } else if($challenge->name >= 11 && file_exists($trainedmodel)){
        $model = "https://crushit.fit/files/models/model.json";
        //$app->utils->message("Re <a href='/levels/plank/2/'>here.</a>");
        
        require_once('../../files/elements/challenges/predictor.php');
        
    }else {
        $app->utils->message("To take the challenge you must first complete the Plank Level 2. <a href='/levels/plank/2/'>Click here!!!</a>");
    }
?>
		</div>
	</div>
<?php
    require_once('../../files/footer.php');
?>