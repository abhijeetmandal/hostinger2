<?php
    //echo "1) index start <br/>";
    define("PAGE_PUBLIC", true);
    define("LANDING_PAGE", true);

    require_once('init.php');
    $minifier->add_file('home.scss', 'css');
	//echo $minifier

    // Set canonical link
    $app->page->canonical = "https://crushit.fit'";

    if (isset($_GET['api']) && isset($_GET['key'])) {
        header("Content-type: text/plain");
        
        $log= new log($this);
        $log->add("users.log", "index.php REQUEST GET api: ".$_GET['api']);
        $log->add("users.log", "index.php REQUEST GET key: ".$_GET['key']);
        $log->add("users.log", "index.php REQUEST POST action: ".$_POST['action']);
        // Make call to api
        // Abhijeet: looks like related to IRC chat
        try {
            $api = new api($app, $_GET['key']);
            $api->handleRequest($_POST['action'], null);
        } catch (Exception $e) {
            $log->add("users.log", "index.php ERROR MSG ".$e->getMessage());
            echo $e->getMessage();
        }

        die();
    }

    if ($app->user->loggedIn) {
        require_once("home.php");
    } else {
        define("_SIDEBAR", false);

        require_once('header.php');
?>
                <div class='row header'>
                    <img src="../files/images/logo_landing.png" alt="Crush It!! - The Next Generation of Models and Stars to shine">
                </div>
<?php
        if (isset($_GET['deleted'])) {
            $app->utils->message('Your account has been successfully deleted. Painful though parting be, I bow to you as I see you off to distant clouds. ', 'info');
        }
?>
                <div class='row landing_blurb'>
                    <h1>Want to do Physical Training , GYM Exercise or Yoga? Discover how you can Training with us to get the best in you and get your body in a shape you always desired with CrushIt!!</h1>
                </div>
                <div class='row landing'>
                    <div class='col span_15'>
                        <section class='row fluid features'>
                            <a href='/levels' class='clr'>
                                <div class='col span_5'>
                                    <div class='circle'><i class='icon-flag'></i></div>
                                </div>
                                <div class='col span_19'>
                                    <h2>Challenges</h2>
                                    <span class='blurb'>
                                        <strong class='white'>Test your fitness with 50+ training levels, covering all aspects of Health.</strong><br/>Each level is hand crafted with help available at every stage.
                                    </span>
                                </div>
                            </a>
                            <a href='/forum' class='clr'>
                                <div class='col span_5'>
                                    <div class='circle'><i class='icon-domain2'></i></div>
                                </div>
                                <div class='col span_19'>
                                    <h2>Community</h2>
                                    <span class='blurb'>
                                        <strong class='white'>Join in the discussion with 250,000+ like-minded members.</strong><br/>
                                        Need a suggestion? Want to talk about the latest way to exercise?
                                    </span>
                                </div>
                            </a>
                            <a href='/articles' class='clr'>
                                <div class='col span_5'>
                                    <div class='circle'><i class='icon-insertpictureleft'></i></div>
                                </div>
                                <div class='col span_19'>
                                    <h2>Articles</h2>
                                    <span class='blurb'>
                                        <strong class='white'>Learn from our online collection of articles.</strong><br/>
                                        Learn from our collection of articles covering all aspects of fitness and health.
                                    </span>
                                </div>
                            </a>
                        </section>

                        <?php include('elements/home_articles.php'); ?>
                    </div>

<?php
        $visible = true;
        if (isset($_COOKIE["member"]) && $_COOKIE["member"])
            $visible = false;
        if (isset($_GET['login']))
            $visible = false;
        if (isset($_GET['register']))
            $visible = true;
?>
                    <div class='col span_9 registration'>
<?php
        if (isset($_GET['request'])):
?>
                        <div class='row'>
                            <?php include('elements/widgets/request.php'); ?>
                        </div>
<?php
        endif;
?>
                        <div class='row'>
                            <h2>Login</h2>
                            <?php
                                if(isset($_SESSION['g_auth'])) {
                                    include('elements/widgets/login_google_auth.php');
                                } else {
                                    include('elements/widgets/login.php');
                                    //include('elements/widgets/login_google_auth.php');
                                }
                                ?>
                        </div>
                        <div class='row'>
                            <h2>Register</h2>
                            <?php include('elements/widgets/register.php'); ?>
                        </div>
                    </div>
                </div>
<?php

    }
    require_once('footer.php');
    //echo "2) index end <br/>";
    //$log= new log($this);
    //$log->add("users", "end print");
?>
