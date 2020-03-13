<?php
    define("PAGE_PUBLIC", true);

    $custom_css = array('faq.scss');
    require_once('init.php');
    $app->page->title = 'More';
    require_once('header.php');
?>
<h1>AI</h1>
<ul>
<?php
if ($app->user->loggedIn):
?>
    <li><a href='/challenges/'>Challenges (Access Camera)</a></li>
    <li><a href='/levels/'>Levels (Access Camera)</a></li>
<?php
endif;

?>
</ul>
<?php  
    require_once('footer.php');
?>
