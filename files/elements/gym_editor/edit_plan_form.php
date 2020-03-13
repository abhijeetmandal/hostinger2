<?php
    $challenge = null;
    $updated = null;

    // Get challenge details
    if (is_numeric($_GET['edit'])) {
        $challenge = $app->gym->getChallengeFromID($_GET['edit']);
        if (!$challenge) {
            $app->utils->message('Challenge not found');
            die();
        } else {
            if (isset($_POST['save'])) {
                $updated = $app->gym->editChallengeForm($_GET['edit']);

                if ($updated) {
                    // if (!isset($_GET['done']))
                    //     header('Location: '.$_SERVER[REQUEST_URI].'&done');
                    // else
                    //     header('Location: '.$_SERVER[REQUEST_URI]);
                    // die();

                    $app->utils->message('Challenge updated', 'good');
                    $challenge = $app->gym->getChallengeFromID($_GET['edit']);
                }
            }
        }
    } else {
        $app->utils->message('Challenge not found');
        die();
    }

    $form_type = 'html';
    if ($_GET['form'] == 'html')
        $form_type = 'html';
    else if ($_GET['form'] == 'json')
        $form_type = 'json';
    else if ($_GET['form'] == 'file')
        $form_type = 'file';
    else if (isset($challenge->data['form']) && json_decode($challenge->data['form']))
        $form_type = 'json';
    else if (isset($challenge->data['form']) && realpath($app->config('path') . '/files/elements/gym/'.basename($challenge->data['form']).'.php'))
        $form_type = 'file';
?>

<a class='button right' href='?edit=<?=$_GET['edit'];?>'>Edit data</a>
<h2><a href='?edit=<?=$_GET['edit'];?>'><?=$challenge->title;?></a> - Form</h2>

<?php if (isset($_GET['done'])) $app->utils->message('Challenge updated', 'good'); ?>

<a class='button left' href='?edit=<?=$_GET['edit'];?>&form=json'>JSON</a> <a class='button left' href='?edit=<?=$_GET['edit'];?>&form=file'>File</a> <a class='button left' href='?edit=<?=$_GET['edit'];?>&form=html'>HTML</a>
<br/><br/>

<form method="POST">
<?php
    if ($form_type == 'json'):
?>
        <label>Method:</label><br>
        <select name="form_method" class='tiny' style='width: auto'>
            <option>POST</option>
            <option>GET</option>
        </select><br/>
        <label>Fields:</label><br>
<?php
        if (isset($challenge->data['form']) && $form = json_decode($challenge->data['form'])):
            foreach($form->fields AS $field):
?>
        <div class='clr'>
            <select name='form_type[]' class='tiny' style='width: auto'>
                <option <?=isset($field->type) && strtolower($field->type) == 'text'?'selected':'';?>>Text</option>
                <option <?=isset($field->type) && strtolower($field->type) == 'password'?'selected':'';?>>Password</option>
            </select>
            <input name='form_label[]' type="text" class="span_6" placeholder="Label" value="<?=htmlentities($field->label);?>"/>
            <input name='form_name[]' type="text" class="span_6" placeholder="Name" value="<?=htmlentities($field->name);?>"/>
        </div>
<?php
            endforeach;
        endif;
?>
        <div class='clr'>
            <select name='form_type[]' class='tiny' style='width: auto'>
                <option>Text</option>
                <option>Password</option>
            </select>
            <input name='form_label[]' type="text" class="span_6" placeholder="Label" />
            <input name='form_name[]' type="text" class="span_6" placeholder="Name" />
        </div>
        <a href='#' class='add-field'>+ Add field</a><br/><br/>

<?php
    elseif ($form_type == 'file'):
?>
    <div class='clr'>
        <label>File name:</label><br/>
        <input name="form" type="text" class="span_11" value="<?=isset($challenge->data['form']) && realpath($app->config('path') . '/files/elements/gym/'.basename($challenge->data['form']).'.php')?basename($challenge->data['form']):'';?>" />
    </div>
<?php
    else:
?>
    <label>Form:</label><br/>
    <textarea name="form"><?=isset($challenge->data['form'])?htmlentities($challenge->data['form']):'';?></textarea>
<?php
    endif;
?>


    <label>Answer:</label><br>
<?php
        if (isset($challenge->data['answer']) && $answers = json_decode($challenge->data['answer'])):
            foreach($answers AS $answer):
?>
    <div class='clr'>
        <select name='answer_method[]' class='tiny' style='width: auto'>
            <option <?=isset($answer->method) && strtolower($answer->method) == 'post'?'selected':'';?>>POST</option>
            <option <?=isset($answer->method) && strtolower($answer->method) == 'get'?'selected':'';?>>GET</option>
        </select>
        <input name='answer_name[]' type="text" class="span_6" placeholder="Name" value="<?=htmlentities($answer->name);?>"/>
        <input name='answer_value[]' type="text" class="span_6" placeholder="Value" value="<?=htmlentities($answer->value);?>"/>
    </div>
<?php
            endforeach;
        endif;
?>
    <div class='clr'>
        <select name='answer_method[]' class='tiny' style='width: auto'>
            <option>GET</option>
            <option>POST</option>
        </select>
        <input name='answer_name[]' type="text" class="span_6" placeholder="Name"/>
        <input name='answer_value[]' type="text" class="span_6" placeholder="Value"/>
    </div>
    <a href='#' class='add-answer'>+ Add answer</a>

    <input type="hidden" name="save"/>
    <input type="hidden" value="<?=$app->generateCSRFKey("challenge-editor");?>" name="token">
    <input type="submit" class="button" value="Save"/>
</form>
