<?php
    header('Content-Type: application/json');
    (!isset($_POST['data'])) && die('Error processing response');

    //echo "preview.php";
    //echo "realpath: ".realpath("./");
    require_once('../../../files/init.php');
    echo $app->parse($_POST['data']);
?>