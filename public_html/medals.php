<?php
    $custom_css = array('faq.scss');
    require_once('init.php');
    $app->page->title = 'FAQ - Medals';
    require_once('header.php');

    $st = $app->db->prepare('SELECT label, description, medals_colours.colour, `count`, medals_colours.reward, COALESCE(`time`, 0) AS `awarded`
                             FROM medals
                             LEFT JOIN medals_colours
                             ON medals_colours.colour_id = medals.colour_id
                             LEFT JOIN (SELECT medal_id, COUNT(user_id) AS `count` FROM users_medals GROUP BY medal_id) awarded
                             ON awarded.medal_id = medals.medal_id
                             LEFT JOIN users_medals
                             ON users_medals.medal_id = medals.medal_id AND users_medals.user_id = :uid
                             ORDER BY medals.colour_id, `label` ASC');
    $st->bindValue(':uid', $app->user->uid);
    $st->execute();
    $medals = $st->fetchAll();
?>
    <h1><a href='/faq.php'>FAQ</a> - Medals</h1>
    <ul class='medals-list plain'>
<?php
    $last_colour = null;
    foreach($medals AS $medal) {
        if ($medal->colour != $last_colour):
?>
        <li class='clr new-section'><h3><?=ucwords($medal->colour) . ' Medals';?><span class="right"><?=$medal->reward;?> pts</span></h3></li>
<?php
            $last_colour = $medal->colour;
        endif;
?>
        <li class='clr fluid'>
            <div class='col span_1'><?=$medal->awarded?"<i class='icon-tick'></i>":"&nbsp;";?></div>
            <div class='col span_3'><span class="medal medal-<?=$medal->colour;?>"><?=$medal->label;?></span></div>
            <div class='col span_14'><?=$medal->description;?></div>
            <div class='col span_6 dark text-right'><?=number_format($medal->count);?> awarded</div>
        </li>
<?php
    }
?>
    </ul>
<?php
    require_once('footer.php');
?>