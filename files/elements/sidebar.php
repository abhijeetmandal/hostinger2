<?php
    if (!defined('_SIDEBAR') || _SIDEBAR):
?>
                <div class="sidebar col span_<?=(!$app->user->loggedIn && defined('LANDING_PAGE') && LANDING_PAGE)?'8':'6';?> clr">
<?php
        if ($app->user->loggedIn) {
            include('widgets/dashboard.php');
	    if (!$app->user->loggedIn || !$app->user->donator)
	            include('widgets/ads.php');
            //include('widgets/feed.php');
            include('widgets/scoreboard.php');
            // include('widgets/adverts.php');
            include('widgets/user_level_queue.php');        
            
        } else {
            if (isset($_GET['request'])):
                include('elements/widgets/request.php');
            else:
                // include('widgets/adverts.php');
            endif;

           include('widgets/ads.php');
	   //include('widgets/feed.php');
            include('widgets/scoreboard.php');
            // include('widgets/login.php');
            // include('widgets/register.php');
            include('widgets/user_level_queue.php');
            
        }
?>
                </div>
<?php
    endif;
?>
