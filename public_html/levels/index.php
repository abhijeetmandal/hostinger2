<?php
//echo "realpath ".  realpath("./") ."<br/>";
    define('PAGE_PUBLIC', true);
    $custom_css = array('levels.scss');
    $custom_js = array('levels.js');
    $page_title = 'Levels';
    require_once('../../files/header.php');

    $filter = null;
    if (isset($_GET['category'])) {
        $filter = strtolower($_GET['category']);
    }
    
    //echo "levels/index group: ". $_GET['group'] ."<br/>";
    //echo "levels/index filter: $filter <br/>";

    $sections = $app->levels->getList(null, $filter);
    
    //echo "sections: $sections <br/>";
    

    echo $app->twig->render('levels_list.html', array('sections' => $sections, 'filter' => $filter));

    require_once('../../files/footer.php');
?>