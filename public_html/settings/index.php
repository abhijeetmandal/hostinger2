<?php
//echo "realpath ".  realpath("./") ."<br/>";
//echo "templateData: ".  $templateData ."<br/>";
//echo "email: ".  $_POST['email'] ."<br/>";

    $custom_js = array('bootstrap-datepicker.js', 'settings.js');
    $custom_css = array('settings.scss', 'datepicker.scss');
    require_once('../../files/init.php');

    $app->page->title = 'Settings - Profile';

    $profile = new profile($app->user->username);

    $profile->alien = false;
    foreach($profile->medals AS $medal) {
        if ($medal->medal_id == 10) {
            $profile->alien = true;
            break;
        }
    }

    // Check for update
    $updated = false;
    if (isset($_GET['save'])) {
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['gender']) && isset($_POST['dob'])) {
            $changes = array_map('trim', $_POST);
            $changes['websites'] = $_POST['websites'];
            $updated = $app->user->update($changes);
            if ($updated === true) {
                header('location: ?done');
                die();
            }

            // Profile has been updated but not saved. Update object so changes are reflected
            $profile->name = $changes['name'];
            $profile->show_name = isset($changes['display_name']);
            $profile->email = $changes['email'];
            $profile->show_email = isset($changes['display_email']);
            switch($changes['gender']) {
                case 'm': $profile->gender = 'male'; break;
                case 'f': $profile->gender = 'female'; break;
                case 'a': $profile->gender = 'alien';
            }
            $profile->show_gender = isset($changes['display_gender']);
            $profile->dob = strtotime($changes['dob']);
            if ($changes['show_dob'] === '0' || $changes['show_dob'] === '1' || $changes['show_dob'] === '2')
                $profile->show_dob = $changes['show_dob'];
            $profile->about_plain = $changes['about'];

            $profile->link = $changes['websites'];
        } else {
            $updated = 'Invalid request';
        }
    }

    $templateData = array('profile' => $profile);

    if (isset($_GET['done'])) {
        $templateData['goodMsg'] = 'Profile updated';
    } else if ($updated) {
        $templateData['errorMsg'] = $updated;
    }

    require_once('../../files/header.php');
    $tab = 'profile';
    include('../../files/elements/tabs_settings.php');

    echo $app->twig->render('settings_profile.html', $templateData);

    require_once('../../files/footer.php');
?>