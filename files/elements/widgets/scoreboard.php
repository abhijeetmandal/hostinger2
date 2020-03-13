<?php
    $scoreboard = $app->stats->getLeaderboard();
?>

                    <article class="widget scoreboard">
                        <h1><a href='/leaderboards.php'>Scoreboard</a></h1>
                        <ul class='plain'>
<?php
    for ($i = 0; $i < count($scoreboard); $i++):
        $position = $scoreboard[$i];
        $n = $i + 1;

        $joint = ($n > 1 && $position->score == $scoreboard[$i-1]->score);
?>
                            <li class='<?=$n==1?'first':($n==2?'second':($n==3?'third':''));?> clr row fluid'>
                                <span class='position col span_3'><?=$joint?'~':$n;?></span>
                                <span class='col span_14'>
                                    <a href='/user/<?=$position->username;?>'><?=$position->username;?></a>
<?php if ($position->vip): ?>
                                    <div class="medal medal-gold"><a href="/medals.php#vip">vip</a></div>
<?php
    endif;
    if ($n <= 3):
?> 
                                    <img src='<?=$position->image;?>' class='right' alt="<?=$position->username;?>"/>
<?php endif; ?>
                                </span>
                               
                                <span class='score text-right col span_7'><?=number_format($position->score);?> pts</span>
                            </li>
<?php
    endfor;
?>
                       </ul>
                    </article>
