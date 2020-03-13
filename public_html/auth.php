<?php
    define("PAGE_PUBLIC", true);
    $page_title = 'Authenticate';
    require('init.php');
	
	if ($app->user->loggedIn)
		die(header('Location: /'));
	
    $minifier->add_file('home.scss', 'css');

    require('header.php');
?>
    <section class='row clr home'>
        <div class='col span_12 home-module'>
<?php
    require('elements/widgets/login.php');
?>
        </div>
        <div class='col span_12 home-module'>
<?php
    require('elements/widgets/register.php');
?>
        </div>
    </section>
<?php
    require('footer.php');
?>
