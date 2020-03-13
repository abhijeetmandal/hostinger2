var loggedIn = false;

$(function() {
    // Animate stuff
    var delay = 150;
    for (i = 0; i < 4; i++) {
        $($('.features h2')[i]).transition({ opacity: 1, delay: (i*delay) }, 2000);
        $($('.features .circle')[i]).transition({ opacity: 1, scale: 1, delay: (300+i*delay) }, 500, 'snap');
        $($('.features .blurb')[i]).transition({ opacity: 1, delay: (i*delay) }, 2000);
    }


    // Registration
    $('#reg_email').on('blur', function() {
        $(this).mailcheck({
            suggested: function(element, suggestion) {
                $('#reg_email_suggestion').html("<a href='#'>Did you mean <span>"+suggestion.full+"</span>?</a>").fadeIn();
            }, empty: function(element) {
                $('#reg_email_suggestion').fadeOut();
            }
        });
    });

    $('#reg_email_suggestion').on('click', 'a', function (e) {
        e.preventDefault();

        $('#reg_email').mailcheck({
            suggested: function(element, suggestion) {
                $('#reg_email').val(suggestion.full);
            }
        });

        $(this).fadeOut();
    });
    
 
    var tests = {
        user: function (val) {
            return val.length > 3 &&
                   val.length <= 16 &&
                   /^[0-9A-Za-z_-][0-9A-Za-z_.-]*[0-9A-Za-z_-]$/.test(val);
        },

        email: function (val) {
            return /^(?:\w+\.?)*\w+@(?:\w+\.)+\w+$/.test(val);
        },
          
        minLength: function (val, length) {
            return val.length >= length;
        },
          
        maxLength: function (val, length) {
            return val.length <= length;
        },
          
        equal: function (val1, val2) {
            return (val1 == val2);
        }
    };

    $('#login_form').isHappy({
        fields: {
            '#username': {
                required: true
            },
            '#password': {
                required: true
            }
        }
    });

    $('#registration_form').isHappy({
        fields: {
            '#reg_username': {
                required: true,
                test: tests.user
            },
            '#reg_password': {
                required: true
            },
            '#reg_password_2': {
                required: true,
                test: tests.equal,
                arg: function () {
                    return $('#reg_password').val();
                }
            },
            '#reg_email': {
                required: true,
                test: tests.email
            }
        }
    });


    // Landing page forms
    $('.landing .registration > div').on('click', function(e) {
        if (!$(this).hasClass('hidden')) return;
        
        $('.landing .registration > div').toggleClass('hidden');
        $(this).children('.widget').slideToggle();
        $('.landing .registration > div').not(this).children('.widget').slideToggle();
    });
    
    $('.fb-login').on('click', function(e) {
        e.preventDefault();
        //alert("hello3");
        fbLogin();
    });
    
    fbAsyncInit = function() {
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
                //getFbUserData();
            }
        });
    };
    
     // Logout from facebook
    function fbLogout() {
        FB.logout(function() {
            //document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
            //document.getElementById('fbLink').innerHTML = '<img src="fblogin2.png" alt="Connect with facebook"/>';
            //document.getElementById('userData').innerHTML = '';
            //document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';
            console.log("logout");
        });
    }
    
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
                //document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
                console.error("User cancelled login or did not fully authorize.");
            }
        }, {scope: 'public_profile,email'});
    }

    // Save user data to the database

    function getFbUserData(){
       // alert("hello");
        FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture,name'},
        function (response) {
            console.log(JSON.stringify(response));
            // Save user data
            //saveUserData(response);
        $uri='fb/userData.php';
        //$uri='/files/ajax/fboauth.php';
        $.post($uri, {oauth_provider:'facebook',userData: JSON.stringify(response)}, function(data){ 
            console.log(data.status); 
            if (data.status) {
                //console.log(data.facebook);
                //console.log(data.fbusername);
                window.location.replace("?facebook="+data.facebook+"&fbusername="+data.fbusername);
            }
            else{
                console.log(data.status);
            }
        },'json');
        });
    }



    // Navigation forms
    var dropdown = $('.nav-extra-dropdown');

    $('.nav-extra').each(function() {
        if ($(this).parent().hasClass('active')) {
            if ($(this).hasClass('nav-extra-login'))
                $('#nav-extra-login').slideDown(200);
            else
                $('#nav-extra-register').slideDown(200);
            bindCloseNotifications();
            return false;
        }
    });

    $('.nav-extra').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (($(this).hasClass('nav-extra-login') && $('#nav-extra-login').is(":visible")) ||
            $(this).hasClass('nav-extra-register') && $('#nav-extra-register').is(":visible")) {
            closeNotifications();
            return false;
        }
        dropdown.slideUp(200);

        if ($(this).hasClass('nav-extra-login'))
            $('#nav-extra-login').slideDown(200);
        else
            $('#nav-extra-register').slideDown(200);
        $(this).parent().addClass('active');

        bindCloseNotifications();
    });

    function bindCloseNotifications() {
        $(document).bind('click.extra-hide', function(e) {
            if ($(e.target).closest('.nav-extra-dropdown').length != 0 && $(e.target).not('.nav-extra')) return true;
            closeNotifications();
        });
    }

    function closeNotifications() {
        dropdown.slideUp(200);
        $('.nav-extra').parent().removeClass('active');
        $('.nav-extra-dropdown .msg').remove();
    }
});