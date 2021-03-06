                </article>
<?php include("elements/sidebar.php"); ?>
            </section>
        </div>
      </div>
        <div id="page-footer">
            <footer>
                <div class="container row">
                    <div class='col span_24 online'>
<?php
    if ($app->user->loggedIn) {
        $list = $app->stats->getOnlineList();
        $list_count = count($list);
        echo $app->twig->render('footer_online.html', array('count' => $list_count, 'users' => $list));
    }
?>
                    </div>
                </div>
                <div class="container row">
                    <div class='col span_15'>
                        <h3>Disclaimer</h3>
                        The owner of this site does not accept responsibility for the actions of any users of this site.
                        Users are solely responsible for any content that they place on this site. This site does not encourage or condone any illegal activity,
                        or attempts to hack into any network where they do not have authority to do so.
                    </div>
                    <div class='col span_3'>
                        <ul class='plain'>
                            <li><h3>Links</h3></li>
                            <li><a href='/articles'>Articles</a></li>
                            <li><a href='/forum'>Forum</a></li>
                            <li><a href='/contact'>Contact Us</a></li>
                            <!-- <li><a href='https://status.crushit.fit'>Status</a></li> -->
                            <li><a href='https://status.crushit.fit'>partner with us</a></li>
                        </ul>
                    </div>
                    <div class='col span_3'>
                        <ul class='plain'>
                            <li><h3>Legal</h3></li>
                            <li><a href='/privacy'>Privacy</a></li>
                            <li><a href='/terms'>Terms of Use</a></li>
                            <li><a href='/conduct'>Code of Conduct</a></li>
                            <li><a href='/policy'>Cancellation & Refund</a></li>
                        </ul>
                    </div>
                    <div class='col span_3'>
                        <ul class='plain'>
                            <li><h3>Connect</h3></li>
                            <li><a href='https://www.facebook.com/CrushIt'><i class='icon-facebook'></i> Facebook</a></li>
                            <li><a href='https://twitter.com/CrushIt'><i class='icon-twitter'></i> Twitter</a></li>
                            <!--<li><a href='https://github.com/CrushIt'><i class='icon-github'></i> GitHub</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="container row">
                    <div class='center version'>
                        Current Version: <a href='/git.php#<?=trim($app->version);?>'><?=trim($app->version);?></a><br/>
                        Copyright © 2008 - <?=date('Y');?> <a href='//www.crushit.fit'>crushit.fit</a>
                    </div>
                </div>
            </footer>
        </div>

        <?= $minifier->load("js"); ?>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-34026704-1', 'crushit.fit');
          ga('send', 'pageview');

        </script>
<?php
        if (isset($currentLevel) && isset($currentLevel->data['code']->pos5)) {
            echo '        '.$currentLevel->data['code']->pos5 . "\n";
        }
?>
        
        <script>
            window.intergramId = "807175143";
            window.intergramCustomizations = {
              closedChatAvatarUrl: '/files/images/abhijeet.jpg',
              introMessage: 'Hello! How can I help you?',
              closedStyle: 'button', // button or chat
              titleClosed: 'Click to chat!',
              titleOpen: 'Have any question?',
              introMessage: 'Hello! How can I help you?',
              autoResponse: 'Currently replying in 20 minutes',
              autoNoResponse: 'We are still checking...',

              // Can be any css supported color 'red', 'rgb(255,87,34)', etc
              mainColor: "#aaa",
              //mainColor: url("https://ankit.crushit.fit/assets/images/avatar.jpeg"),
              alwaysUseFloatingButton: false // use the mobile floating button also on large screens
            };

        </script>
        <script id="intergram" type="text/javascript" src="/files/js/intergram_widget_1.js"></script>
    </body>
</html>
