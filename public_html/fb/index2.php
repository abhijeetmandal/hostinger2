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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body>
        <?php
        // put your code here
        ?>
        <!-- Display login status -->
        <div id="status"></div>

        <!-- Facebook login or logout button -->
        <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink"><img src="fblogin2.png" alt="Connect with facebook"/></a>

        <!-- Display user profile data -->
        <div id="userData"></div>
    </body>
    <script>
        window.fbAsyncInit = function() {
            // FB JavaScript SDK configuration and setup
            FB.init({
              appId      : '2420272864969702', // FB App ID
              cookie     : true,  // enable cookies to allow the server to access the session
              xfbml      : true,  // parse social plugins on this page
              version    : 'v3.3' // use graph api version 2.8
            });

            // Check whether the user already logged in
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    //display user data
                    getFbUserData();
                }
            });
        };

        // Load the JavaScript SDK asynchronously
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Facebook login with JavaScript SDK
        function fbLogin() {
            FB.login(function (response) {
                if (response.authResponse) {
                    // Get and display the user profile data
                    getFbUserData();
                } else {
                    document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
                }
            }, {scope: 'public_profile,email'});
        }

        // Fetch the user profile data from facebook
        function getFbUserData(){
            FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture,name'},
            function (response) {
                document.getElementById('fbLink').setAttribute("onclick","fbLogout()");
                document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
                document.getElementById('status').innerHTML = 'Thanks for logging in, ' + response.first_name + '!';
                document.getElementById('userData').innerHTML = '<p><b>FB ID:</b> '+response.id+'</p><p><b>Name:</b> '+response.first_name+' '+response.last_name+'</p><p><b>Email:</b> '+response.email+'</p><p><b>Gender:</b> '+response.gender+'</p><p><b>Locale:</b> '+response.locale+'</p><p><b>username:</b> fb'+response.id+'</p><p><b>name:</b> '+response.name+'</p><p><b>Picture:</b> <img src="'+response.picture.data.url+'"/></p><p><b>FB Profile:</b> <a target="_blank" href="'+response.link+'">click to view profile</a></p>';
            });
        }

        // Logout from facebook
        function fbLogout() {
            FB.logout(function() {
                document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
                document.getElementById('fbLink').innerHTML = '<img src="fblogin2.png" alt="Connect with facebook"/>';
                document.getElementById('userData').innerHTML = '';
                document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';
            });
        }
        
        // Save user data to the database
        function saveUserData(userData){
            $.post('userData.php', {oauth_provider:'facebook',userData: JSON.stringify(userData)}, function(data){ return true; });
        }
        
        function getFbUserData2(){
            FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
            function (response) {
                document.getElementById('fbLink').setAttribute("onclick","fbLogout()");
                document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
                document.getElementById('status').innerHTML = 'Thanks for logging in, ' + response.first_name + '!';
                document.getElementById('userData').innerHTML = '<p><b>FB ID:</b> '+response.id+'</p><p><b>Name:</b> '+response.first_name+' '+response.last_name+'</p><p><b>Email:</b> '+response.email+'</p><p><b>Gender:</b> '+response.gender+'</p><p><b>Locale:</b> '+response.locale+'</p><p><b>Picture:</b> <img src="'+response.picture.data.url+'"/></p><p><b>FB Profile:</b> <a target="_blank" href="'+response.link+'">click to view profile</a></p>';

                // Save user data
                saveUserData(response);
            });
        }
    </script>
</html>
