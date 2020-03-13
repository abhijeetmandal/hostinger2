<?php
    $sections = $app->gym->getList();
    $lastGroup = '';
    foreach($sections as $key => $section):
?>
        <h3 class='white'><?=$key;?></h3>
        <ul class='challenges-list plain clr'>
<?php
        foreach($section->gym as $challenge):
?>
            <li>
                <a href="gym.php?edit=<?=$challenge->id;?>">
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