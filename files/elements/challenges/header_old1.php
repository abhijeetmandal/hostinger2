<div class='challenge-header'>
<?php if (isset($challenge->challenge_before_uri)): ?>
	<a class='left previous-challenge' href='<?=$challenge->challenge_before_uri;?>'><i class='icon-caret-left'></i></a>
<?php else: ?>
	<span class='left previous-challenge dark'><i class='icon-caret-left'></i></span>
<?php 
	endif;
	if (isset($challenge->challenge_after_uri) && (strtolower($challenge->group) != 'main' || $challenge->completed)):
?>
	<a class='right next-challenge' href='<?=$challenge->challenge_after_uri;?>'><i class='icon-caret-right'></i></a>
<?php elseif(isset($challenge->challenge_after_uri) && strtolower($challenge->group) == 'main'): ?>
	<span class='right next-challenge dark hint--left' data-hint="You must complete main challenges in order,&#10;but you can attempt any other challenge."><i class='icon-caret-right'></i></span>
<?php else: ?>
	<span class='right next-challenge dark'><i class='icon-caret-right'></i></span>
<?php endif; ?>

	<h1 class='no-margin'><?=ucwords($challenge->group . " Day " . $challenge->name);?></h1>
<?php if (!($challenge->completed && !$challenge->attempts)): ?>	
	<span class='dark'>Attempts: <?=$challenge->attempts;?>
<?php 	if ($challenge->completed): ?>
		&middot; Duration: <?=$app->utils->timeBetween($challenge->started, $challenge->completed_time);?>
<?php 	endif; ?>
	</span>
	<span class='hint--top' data-hint="This information is not public and only reflects&#10;the first time you completed the challenge."><i class='icon-info'></i></span><br/>
<?php endif; ?>
	<span class='strong <?=$challenge->completed?'green':'red';?>'><?=$challenge->completed?'Completed':'Incomplete';?></span><br/>

<?php if (isset($challenge->online) && $challenge->online): ?>
	<br/><span class='strong <?=$challenge->online == 'online'?'green':'red';?>'>Level <?=$challenge->online;?></span><br/>
<?php endif; ?>

<?php
        if (isset($challenge->data['description']) && (!isset($challenge->attempt) || $challenge->attempt !== true)):
?>
        <div class='info description'>
            <?=$app->utils->parse($challenge->data['description']);?>
        </div>
<?php
        endif;
	if (isset($challenge->attempt)) {
		if ($challenge->attempt === true)
			$app->utils->message('Level complete'.(isset($challenge->challenge_after_uri)?", <a href='$challenge->challenge_after_uri'>next challenge</a>":''), 'good');
		else if (isset($challenge->errorMsg))
			$app->utils->message($challenge->errorMsg);
		else
			$app->utils->message('Invalid details');
	}
?>
</div>
