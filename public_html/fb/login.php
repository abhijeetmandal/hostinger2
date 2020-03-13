<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
        $fb = new Facebook\Facebook([
          'app_id' => '2420272864969702', // Replace {app-id} with your app id
          'app_secret' => 'd2017ff0b90a1e3b730d4315296c3c60',
          'default_graph_version' => 'v3.2',
          ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://crushit.fit/fb/fb-callback.php', $permissions);

        echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
        ?>
    </body>
</html>
