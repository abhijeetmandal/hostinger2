<?php
    $custom_js = array('profile.js');
    $custom_css = array('settings.scss', 'profile.scss');
    require_once('../../files/init.php');

    $app->page->title = 'Settings - Forum';

    $profile = new profile($app->user->username);

    $value = array();
    $values['signature'] = isset($profile->forum_signature)?$profile->forum_signature:'';

    if (isset($_GET['save'])) {
        $updated = $app->user->updateForum($_POST['signature'], $_POST['token']);
        if ($updated === true) {
            header('location: ?done');
            die();
        }

        $values['signature'] = $_POST['signature'];
    }

    require_once('../../files/header.php');

    $tab = 'forum';
    include('../../files/elements/tabs_settings.php');
?>
    <h1>Forum</h1>
<?php
    if (isset($updated)) {
        $app->utils->message($updated);
    } else if (isset($_GET['done'])) {
        $app->utils->message("Forum settings updated", "good");
    }
?>
    <form action="?save" method="POST">
        <fieldset>
            <label for="name">Signature:</label><br>
<?php
    $wysiwyg_name = 'signature';
    $wysiwyg_placeholder = 'This will be displayed below any post you make in the forum...';
    $wysiwyg_text = $values['signature'];
    include('../../files/elements/wysiwyg.php');
?>

            <input type="hidden" value="<?=$app->generateCSRFKey("settings");?>" name="token">
            <input type="submit" value="Save Changes" class="button">
        </fieldset>
    </form>

<?php
    require_once('../../files/footer.php');
?>