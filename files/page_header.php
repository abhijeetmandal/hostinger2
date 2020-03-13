<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
        <meta charset="utf-8">
        <meta name="google-site-verification" content="m4v-uym0kvossRf84KplRxpVif4pQMYYTXhYQDrmS-I" />
        
       

<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)): ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?php endif; ?>       
        
        <title><?=isset($app->page->title) && $app->page->title?$app->page->title. ' - CrushIt!!':'CrushIt!! - The Next Generation of Fitness Training';?></title>
        <meta name="description" content="<?=isset($app->page->desc) && $app->page->desc?$app->page->desc:'Want to burn fat and build muscle?. Try our training challenges or join our community to discuss the latest exercises.';?>">
        <meta name="keywords" content="Crush it, athletes training, training challenges, training forums, trainers, exercises, how to exersice body, how to exercise, articles, yoga">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        
        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>

        <link href="/favicon.png" rel="icon" id="basic-favicon" type="images/png" />
        <!-- <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" /> --> 
        <!-- <link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon" /> -->

<?php
    echo isset($app->page->canonical)?"        <link rel='canonical' href='{$app->page->canonical}'/>\n":'';
    echo isset($app->page->prev)?"        <link rel='prev' href='{$app->page->prev}'/>\n":'';
    echo isset($app->page->next)?"        <link rel='next' href='{$app->page->next}'/>\n":'';

    if (count($app->page->meta)) {
        foreach($app->page->meta AS $id=>$content) {
            echo "        <meta name='{$id}' content='{$content}'>\n";
        }
    }
?>
        <meta property="fb:app_id" content="" />
        <meta name='twitter:site' content='@crushit'>
        <meta property='og:site_name' content='CrushIt!!'>

        <link href='https://fonts.googleapis.com/css?family=<?= urlencode('Orbitron|Lato:400,700') ?>' rel='stylesheet' type='text/css'>

        <?= $minifier->load("css"); ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!-- <script src="https://cdn.socket.io/socket.io-1.2.1.js"></script> -->
         <!-- this meta viewport is required for BOLT //-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
<!-- BOLT Sandbox/test //-->
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>
<!-- BOLT Production/Live //-->
<!--// <script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script> //-->
<?php
    if (isset($currentLevel) && isset($currentLevel->data['code']->pos1)) {
        echo '        '.$currentLevel->data['code']->pos1."\n";
    }
?>




<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.8.0/p5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.8.0/addons/p5.dom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.8.0/addons/p5.sound.min.js"></script>
<script src="https://unpkg.com/ml5@0.3.1/dist/ml5.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://crushit.fit/files/css/demo_canvas.css">

        <script src="https://d3t63m1rxnixd2.cloudfront.net/files/js/modernizr-2.6.2.min.js"></script>
        <!--[if lt IE 9]>
            <script src="https://d3t63m1rxnixd2.cloudfront.net/files/js/respond.min.js"></script>
            <script src="https://d3t63m1rxnixd2.cloudfront.net/files/js/html5shiv.js"></script>
        <![endif]-->

    </head>
    <body class='theme-<?php echo $app->theme; ?>' <?php if ($app->user) echo "data-username='{$app->user->username}' data-key='".$app->user->csrf_basic."'";?>>
<?php
    if (!isset($_GET['view']) || $_GET['view'] != 'app'):
        if (!isset($_COOKIE["member"]) || !$_COOKIE["member"]):
?>

    <div class='cookies container'>
        <h3>Privacy &amp; Cookies</h3>
        This website uses cookies. By continuing to use this site you are agreeing to our use of cookies.
    </div>
        
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KLRSTBG" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

<?php
        endif;    

        if ($app->user->loggedIn || !(defined('LANDING_PAGE') && LANDING_PAGE)):
?>
    <div class="page-wrap">
<?php
            if (isset($currentLevel) && isset($currentLevel->data['code']->pos2)) {
                echo '        '.$currentLevel->data['code']->pos2 . "\n";
            }
?>
        <div id="header-wrap" class="container clr">
            <header>
                <div class="col span_11 banner">
                    <a href='/'>&nbsp;</a>
                </div>
<?php
            //hard coding to show no ads
            if ((!$app->user->loggedIn || !$app->user->donator) && FALSE):
                $ads = array(
                    array('nullsec.png', 'http://www.nullsecurity.net'),
                    array('walker.png', 'http://www.walkerlocksmiths.co.uk/')
                );

                $id = array_rand($ads);
                $image = $ads[$id][0];
                $link = $ads[$id][1];
                //echo "real pah:".realpath("./");
?>
                <div class="col span_13 advert">
                    <a href='<?=$link;?>' target='_blank' class='hide-external'>
                        <img src='https://crushit.fit/files/images/header/banner_<?=$image;?>'/>
                    </a>
                </div>
<?php
            endif;
?>
            </header>
        </div>
<?php
            include('elements/navigation.php');
        else:
?>
    <div class="page-wrap">
<?php
        endif;
    endif;

    //Calculate document width
/*    if (!$app->user->loggedIn && defined('LANDING_PAGE') && LANDING_PAGE)
        $span = '16';
    else */ if (!defined('_SIDEBAR') || _SIDEBAR)
        $span = '18';
    else
        $span = '24';
?>
        <div id="body-wrap" class="container row">
            <section id="content-wrap" class="row">
                <article id="main-content" class="col span_<?=$span;?> clr">
