<?php

    session_save_path('/opt/alt/php72/var/lib/php/session');
    ini_set('session.gc_maxlifetime', 3*60*60);
    ini_set('session.gc_probability',1);
    ini_set('session.gc_divisor', 100);
    
    ini_set('session.cookie_httponly', true);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    
    //echo "hello <br/>";
    
    session_start();
    error_reporting(E_ALL);
    ini_set('display_error', '1');
    
    //session security flag
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies',1);
    ini_set('session.cookie_secure',1);
    
    // Content Security Policy
    
    $csp_rules = "
            default-src 'self' https://crushit.fit https://www.crushit.fit:8080 wss://www.crushit.fit:8080 https://*.citruspay.com https://*.googleapis.com https://themes.googleusercontent.com https://*.facebook.com https://fonts.gstatic.com; 
            script-src 'self' https://crushit.fit https://*.citruspay.com https://cdnjs.cloudflare.com data: 'unsafe-inline' 'unsafe-eval' https://crushit.fit https://www.crushit.fit:8080 https://*.citruspay.com https://*.googleapis.com https://*.google-analytics.com https://cdnjs.cloudflare.com https://*.twitter.com https://*.api.twitter.com https://pagead2.googlesyndication.com *.newrelic.com https://www.google.com https://ssl.gstatic.com https://members.internetdefenseleague.org https://netdna.bootstrapcdn.com https://ajax.aspnetcdn.com/ajax/jquery.validate https://*.gosquared.com cdn.socket.io widget.battleforthenet.com *.newrelic.com;
            style-src 'self' 'unsafe-inline' https://*.googleapis.com https://netdna.bootstrapcdn.com widget.battleforthenet.com https://crushit.fit;
            img-src * data: http://boltiswatching.com;
        object-src 'self' https://*.youtube.com  https://*.ytimg.com;
        frame-src 'self' https://*.citruspay.com https://googleads.g.doubleclick.net https://*.youtube-nocookie.com https://*.vimeo.com https://kiwiirc.com https://www.google.com https://fightforthefuture.github.io;
        report-uri https://crushit.fit/r/d/csp/reportOnly;
                    ";
    @header("Content-Security-Policy: " . trim(preg_replace('/\n/', ' ', $csp_rules)));

    @header("X-Content-Type-Options: nosniff");
    @header("X-Frame-Options: SAMEORIGIN");

    //Set timezone
    date_default_timezone_set("Etc/UTC");
    putenv("TZ=Etc/UTC");
    
    spl_autoload_register(function($class){
        @include_once 'class.'.$class.'.php';
    });
    
    // Setup app
    try{
        $app = new app();
        //$cache = new cache($app);
        //$test = new test($cache);
    }catch(Exception $e){
        die($e->getMessage());
    }
    
    // check if theme has changed
    if (isset($_GET['theme'])) {
        $app->setTheme($_GET['theme']);
    }
    
    

    $minifier = new loader($app, $custom_css, $custom_js, $app->theme);
    
    //echo "2.1.2) init afer loader <br/>";

    if ($app->user->loggedIn) {
        if (defined('PAGE_PRIV') && !$app->user->{PAGE_PRIV.'_priv'}) {
            require_once('error.php');
        }   

        array_push($minifier->custom_js, 'ajax_csrf_token.js');
        array_push($minifier->custom_js, 'notifications.js');
        // array_push($minifier->custom_js, 'chat.js');
        array_push($minifier->custom_js, 'autosuggest.js');
    } else {
        array_push($minifier->custom_js, 'guest.js');
        array_push($minifier->custom_js, 'mailcheck.min.js');
        array_push($minifier->custom_js, 'jquery.transit.min.js');
        array_push($minifier->custom_css, 'guest.scss');

        if (defined('LANDING_PAGE') && LANDING_PAGE) {
            array_push($minifier->custom_css, 'guest_landing.scss');
        }

        if (!defined('PAGE_PUBLIC') || !PAGE_PUBLIC) {
            require_once('error.php');
        }
    }

    if (isset($_GET['view']) && $_GET['view'] == 'app') {
        array_push($minifier->custom_css, 'app.css');
    }
    //echo "2.2) init end <br/>";

?>

