<?php
    require_once('init.php');
    $app->page->title = 'Leaderboards';
    $minifier->add_file('faq.scss', 'css');

    $scoreboard = $app->stats->getUserLevelQueue(25);

    require_once('header.php');
?>
    <div class='row'>
        <div class='col span_24'>
            <h2>In Queue (Processing happening at night)</h2>
            <ul class='plain main-scoreboard'>
<?php
    for ($i = 0; $i < count($scoreboard); $i++):
        $position = $scoreboard[$i];
        $n = $i + 1;

        $joint = ($n > 1 && $position->submitted == $scoreboard[$i-1]->submitted);
?>
                <li class='<?=$n==1?'first':($n==2?'second':($n==3?'third':''));?> clr row fluid <?=isset($position->highlight)?'highlight':'';?> <?=isset($position->extra)?'extra':'';?>'>
                    <span class='position col span_2'><?=isset($position->extra)?$position->position+1:($joint?'~':$n);?></span>
                    <span class='col span_4'><a href='/levels/<?=$position->group;?>/<?=$position->name;?>'><?=strtoupper($position->group)." LEVEL".$position->name?></a></span>
                    <span class='col span_8'>
                        <img src='<?=$position->image;?>'/>
                        <a href='/user/<?=$position->username;?>'><?=$position->username;?></a>
                        <?php if ($position->vip) echo '<div class="medal medal-gold"><a href="/medals.php#vip">vip</a></div>'; ?>
                    </span>
                    <span class='col span_3'><?=($n==1)?"in queue":(($position->completed>0)?"Reviewed":"Submitted");?></span>
                    <span class='score text-right col span_7'><?=$app->utils->timeSince2($position->submitted);?></span>
                </li>
<?php
    endfor;
?>
            </ul>
        </div>
    </div>
<?php  
    require_once('footer.php');
?>