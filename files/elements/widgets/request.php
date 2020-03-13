<?php
    $reset = false;
    if (isset($_POST['req_username'])):
        $status = $app->user->request($_POST['req_username']);
    else:
        // Lookup request
        $reset = $app->user->checkRequest($_GET['request']);

        if ($reset && isset($_POST['pass']) && isset($_POST['pass2'])) {
            $changed = $app->user->changePassword($_POST['pass'], $_POST['pass2'], $reset->user_id);
        }
    endif;

    if (!$reset):
?>
                    <article class="widget widget-request">
                        <h1>Request details</h1>
<?php
        if (isset($status) && $status) {
            $app->utils->message($status === true?"An email has been sent to the registered address for this account":$status, $status === true?"good":'error');
        }
?>
                        <form id="request_form" action="?request" method="POST">
                            <div class='small'>Enter your username or email address and we will email instructions to you on how to reset your password.</div><br/>
                            <label for="req_username">Email address or username:</label><br/>
                            <input type="text" name="req_username" id="req_username">
                            <input type="hidden" value="<?=$app->generateCSRFKey("requestDetails");?>" name="token">
                            <input type="submit" value="Submit" class="button">
                        </form>
<?php
    else:
?>
                    <article class="widget widget-request">
                        <h1>Change password</h1>
<?php
        if (isset($changed) && $changed):
            $app->utils->message($changed === true?"Password changed":$changed, $changed === true?"good":'error');
        endif;

        if (!isset($changed) || $changed !== true):
?>
                        <form id="request_form" method="POST">
                            <label for="pass">New password:</label><br/>
                            <input type="password" name="pass" id="pass">
                            <label for="pass2">Repeat password:</label><br/>
                            <input type="password" name="pass2" id="pass2">
                            <input type="hidden" value="<?=$app->generateCSRFKey("changePassword");?>" name="token">
                            <input type="submit" value="Submit" class="button">
                        </form>
<?php
        endif;
    endif;
?>
                    </article>