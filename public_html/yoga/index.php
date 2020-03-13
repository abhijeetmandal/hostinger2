<?php
//echo "realpath ".  realpath("./") ."<br/>";
    define('PAGE_PUBLIC', true);
    $custom_css = array('yoga.scss');
    $custom_js = array('yoga.js');
    $page_title = 'Yoga';
    require_once('../../files/header.php');

    $filter = null;
    if (isset($_GET['category'])) {
        $filter = strtolower($_GET['category']);
    }
    
    //echo "yoga/index group: ". $_GET['group'] ."<br/>";
    //echo "yoga/index filter: $filter <br/>";

    $sections = $app->yoga->getList(null, $filter);
    
    //echo "sections: $sections <br/>";
    

    echo $app->twig->render('yoga_list.html', array('sections' => $sections, 'filter' => $filter));

    require_once('../../files/footer.php');
?>