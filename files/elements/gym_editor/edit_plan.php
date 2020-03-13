<?php
    $challenge = null;
    $updated = null;

    // Get challenge details if not new
    if (is_numeric($_GET['edit'])) {
        $challenge = $app->gym->getChallengeFromID($_GET['edit']);
        if (!$challenge) {
            $app->utils->message('Challenge not found');
            die();
        } else {
            if (isset($_POST['save'])) {
                $updated = $app->gym->editChallenge($_GET['edit']);

                if ($updated) {
                   /* if (!isset($_GET['done']))
                        header('Location: '.$_SERVER[REQUEST_URI].'&done');
                    else
                        header('Location: '.$_SERVER[REQUEST_URI]); */

                    $app->utils->message('Challenge updated', 'good');
                    $challenge = $app->gym->getChallengeFromID($_GET['edit']);
                }
            }
        }
    } else if ($_GET['edit'] === 'new') {
        if (isset($_POST['save'])) {
            $id = $app->gym->newChallenge();

            if ($id !== false) {
                $app->utils->message('Challenge created', 'good');
                $challenge = $app->gym->getChallengeFromID($id);
            } else {
                $app->utils->message('Error creating challenge');
            }
            die();
        }
    } else {
        $app->utils->message('Challenge not found');
        die();
    }
?>

<a class='button right' href='?edit=<?=$_GET['edit'];?>&form'>Edit form</a>
<h2><?=$challenge?$challenge->title:'New challenge';?></h2>

<?php if (isset($_GET['done'])) $app->utils->message('Challenge updated', 'good'); ?>

<form class='challenge-edit' method="POST">
    <div class='clr'>
<?php if (!$challenge): ?>
        <div class='col span_4'>
            <label for="name">Name:</label><br/>
            <input name="name" value="<?=isset($challenge->data['reward'])?$challenge->data['reward']:0;?>"/>
        </div>
<?php endif; ?>
        <div class='col span_6'>
            <label>Category:</label><br/>
            <div class='select-menu' data-id="category" data-value="">
                <label><?=isset($challenge->group)?htmlentities($challenge->group):'Category';?></label>
                <ul>
<?php
    $groups = $app->gym->getGroups();
    foreach($groups AS $group):
?>
                    <li><?=$group->title;?></li>
<?php
    endforeach;
?>
                </ul>
            </div>
        </div>
        <div class='col span_3'>
            <label for="reward">Reward:</label><br/>
            <input name="reward" value="<?=isset($challenge->data['reward'])?$challenge->data['reward']:0;?>"/>
        </div>
        <div class='col span_9'>
            <label for="uptime">Uptime:</label><br/>
            <input name="uptime" value="<?=isset($challenge->data['uptime'])?$challenge->data['uptime']:'';?>"/>
        </div>
    </div>

    <div class='clr'>
        <label>Description:</label><br/>
<?php
    $wysiwyg_enter = false;
    $wysiwyg_name = "description";
    $wysiwyg_text = isset($challenge->data['description'])?$challenge->data['description']:'';
    include('../../files/elements/wysiwyg.php');
?>
    </div>

    <div class='clr'>
        <div class='col span_12'>
            <label>Hint:</label>
<?php
//echo "edit_challenge.php realpath: ". realpath("./") ."<br/>";
    $wysiwyg_lite = true;
    $wysiwyg_name = "hint";
    $wysiwyg_text = isset($challenge->data['hint'])?$challenge->data['hint']:'';
    include('../../files/elements/wysiwyg.php');
?>
        </div>
        <div class='col span_12'>
            <label>Solution message:</label>
<?php
    $wysiwyg_name = "solution";
    $wysiwyg_text = isset($challenge->data['solution'])?$challenge->data['solution']:'';
    include('../../files/elements/wysiwyg.php');
?>
        </div>
    </div>
    <input type="hidden" name="save"/>
    <input type="hidden" value="<?=$app->generateCSRFKey("challenge-editor");?>" name="token">
    <input type="submit" class="button" value="Save"/>
</form>
