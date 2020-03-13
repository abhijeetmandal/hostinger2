<?php
//echo "filesize: ".$_FILES["jsonUpload"]["size"]."<br>";
//echo "category: ".$_POST["category"]."<br>/";
//echo "realpath: ".  realpath("./") ."<br/>";
	if (!isset($_GET['level']))
		header('Location: /levels/');

    $custom_css = array('levels.scss', 'highlight.css');
    $custom_js = array('levels.js', 'highlight.js');
    require_once('../../files/init.php');

    //Load level
    $group = $_GET['group'];
    if ($group === 'basic ') {
        $group = 'basic+';
    }
    $currentLevel = $app->levels->getLevel($group, $_GET['level']);
    
    //echo "username: ". $app->user->username."<br/>";
    //echo "avail.gdlink: ". $app->user->gdlink."<br/>";

    if (!$currentLevel) {
		require_once('../../files/header.php');
		$app->utils->message('Level not found, <a href="/levels">return to index</a>');
		require_once('../../files/footer.php');
		die();
    }

    if (isset($_GET['get-hint'])) {
        if (isset($currentLevel->data['hint']))
            echo json_encode(array('status'=>true, 'hint' => $app->utils->parse($currentLevel->data['hint'])));
        else
            echo json_encode(array('status'=>false));
        die();
    }

    
    //echo "form: ". $currentLevel->data['form'] . "<br/>";
    
    //Check if user completed level
    if (isset($currentLevel->data['form']) && $page = realpath($app->config['path'] . '/files/elements/levels/'.basename($currentLevel->data['form']).'_logic.php')) {
        include($page);
        //echo "page: ". $page . "<br/>";
    } else if( $group=='plank' && $_GET['level'] == 1){ //a newly added check to incoperate file was loaded.
         if(basename($_FILES["binUpload"]["name"]) == $app->user->username.".weights.bin" && basename($_FILES["jsonUpload"]["name"]) == $app->user->username.".json"){
          $app->levels->check($currentLevel);   
         }
        //echo "check level: ". $app->levels->check($currentLevel) . "<br/>";
    } else if ($group=='plank' && $_GET['level'] == 2){ 
        $app->levels->check($currentLevel);
        //echo "check level: ". $app->levels->check($currentLevel) . "<br/>";
    }

	require_once('../../files/header.php');

	$level = $currentLevel;
?>
	<div class='row'>
		<div class='col span_6 level-sidebar'>
<?php
    require_once('../../files/elements/levels/stats.php');
?>
		</div>

		<div class='col span_18 level-area'>
<?php
    require_once('../../files/elements/levels/header.php');
    //require_once('../../files/elements/levels/gdrive.php');
    require_once('../../files/elements/levels/level.php');
    if($level->name == 1){
        require_once('../../files/elements/levels/predictor.php');
    }
?>
		</div>
	</div>
<?php
    require_once('../../files/footer.php');
?>