<?php
    $sections = $app->challenges->getList();
    $lastGroup = '';
    foreach($sections as $key => $section):
?>
        <h3 class='white'><?=$key;?></h3>
        <ul class='challenges-list plain clr'>
<?php
        foreach($section->challenges as $challenge):
?>
            <li>
                <a href="challenges.php?edit=<?=$challenge->id;?>">
                    <span class="thumb_title"><?=$challenge->title;?></span>
                </a>
            </li>
<?php
        endforeach;
?>
        </ul>
<?php
    endforeach;
?>