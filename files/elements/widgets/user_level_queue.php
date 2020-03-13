<?php
    $scoreboard = $app->stats->getUserLevelQueue();
?>

                    <article class="widget scoreboard">
                        <h1><a href='/userlevelqueue.php'>Submitted Queue</a></h1>
                        <ul class='plain'>
<?php
    for ($i = 0; $i < count($scoreboard); $i++):
        $position = $scoreboard[$i];
        $n = $i + 1;

        $joint = ($n > 1 && $position->submitted == $scoreboard[$i-1]->submitted);
?>
                            <li class='<?=$n==1?'first':($n==2?'second':($n==3?'third':''));?> clr row fluid'>
                                <span class='position col span_3'><?=$joint?'~':$n;?></span>
                                <span class='col span_10'>
                                    <a href='/user/<?=$position->username;?>'><?=$position->username;?></a>
<?php if ($position->donator): ?>
                                    <i class='icon-heart'></i>
<?php
    endif;
    if ($n <= 3):
?> 
                                    <img src='<?=$position->image;?>' class='right' alt="<?=$position->username;?>"/>
<?php endif; ?>
                                </span>
                                 <span class='col span_2'><?=$position->name;?></span>
                                <span class='score text-right col span_9'><?=$app->utils->timeSince($position->submitted);?></span>
                            </li>
<?php
    endfor;
?>
                       </ul>
                    </article>
