<?php
    if (isset($challenge->attempt) && $challenge->attempt === true) :
        if (isset($challenge->data['solution'])):
?>
        <div class='info solution'>
            <?=$app->utils->parse($challenge->data['solution']);?>
        </div>
        <br/>
<?php
        endif;
?>
        <div class='info solution'>
            Want to share your solution? <a href='/forum/challenge-discussion/<?=strtolower($currentChallenge->group);?>-challenges/<?=strtolower($currentChallenge->group);?>-challenge-<?=$currentChallenge->name;?>/solutions'>Visit the solutions section</a> in the forum.
        </div>
<?php
    elseif (isset($challenge->data['form'])):
?>

            <div class='challenge-form'>
<?php
        if (isset($currentChallenge) && isset($currentChallenge->data['code']->pos3)) {
            echo '                '.$currentChallenge->data['code']->pos3 . "\n";
        }
        if ($form = json_decode($challenge->data['form'])):
?>
                <form <?=isset($form->method)?'method="'.strtoupper($form->method).'"':'';?>>
                    <fieldset>
<?php       foreach($form->fields AS $field): ?>
                        <label <?=isset($field->name)?"for='{$field->name}'":""?>><?=$field->label;?>:</label>
                        <input type='<?=isset($field->type)?"{$field->type}":'text';?>' autocomplete="off" <?=isset($field->name)?"id='{$field->name}' name='{$field->name}'":'';?>><br>
<?php       endforeach; ?>
                        <input type="submit" class="button" value="Submit">
                    </fieldset>
                </form>            
<?php
        elseif ($page = realpath($app->config('path') . '/files/elements/challenges/'.basename($challenge->data['form']).'.php')):
            include($page);
        else:
            echo $challenge->data['form'];
        endif;
?>
            </div>
<?php
        if (isset($currentChallenge) && isset($currentChallenge->data['code']->pos4)) {
            echo '            '.$currentChallenge->data['code']->pos4 . "\n";
        }
    endif;
?>
