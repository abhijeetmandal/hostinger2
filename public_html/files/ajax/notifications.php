<?php
    header('Content-Type: application/json');
    require_once('../../../files/init.php');

    $userId = $app->user->uid;
    $result = array('status'=>true);

    if (isset($_GET['events'])) {
        $result['items'] = $app->notifications->getEvents(5, 0, false);
        $result['friends'] = $app->notifications->getFriends();
    } else {
        $last = isset($_POST['last'])?$_POST['last']:0;
        // $result['feed'] = $app->feed->get($last); -- feed is now sent via sockets
        $result['counts'] = $app->notifications->getCounts();
    }

    $json = json_encode($result);
    // Remove null entries
    echo preg_replace('/,\s*"\[^"\]+":null|"\[^"\]+":null,?/', '', $json);
?>