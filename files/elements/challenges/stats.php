                   <article>
                        <h1 class='title'>Stats</h1>
						<table>
							<tbody>
<?php if (isset($challenge->data['author'])): ?>
								<tr>
                                    <td class="white">Author</td>
                                    <td><a href='/user/<?=$challenge->data['author'];?>'><?=$challenge->data['author'];?></a>
                                    </td>
                                </tr>
<?php endif; ?>
								<tr>
                                    <td class="white">Reward</td>
                                    <td><?=number_format($challenge->data['reward']);?> pts</td>
                                </tr>
<?php if (isset($challenge->count)): ?>
								<tr>
                                    <td class="white">Completed</td>
                                    <td><?=number_format($challenge->count);?></td>
                                </tr>
<?php
    endif;
    if (isset($challenge->last_completed)):
?>
								<tr>
                                    <td class="white">Latest</td>
                                    <td><time datetime="<?=date('c', strtotime($challenge->last_completed));?>"><?=$app->utils->timeSince($challenge->last_completed);?></time><br>
                                        <a href="/user/<?=$challenge->last_user;?>"><?=$challenge->last_user;?></a>
                                    </td>
                                </tr>
<?php
    endif;
    if (isset($challenge->first_completed)):
?>
								<tr>
                                    <td class="white">First</td>
                                    <td><time datetime="<?=date('c', strtotime($challenge->first_completed));?>"><?=$app->utils->timeSince($challenge->first_completed);?></time><br>
                                        <a href="/user/<?=$challenge->first_user;?>"><?=$challenge->first_user;?></a>
                                    </td>
                                </tr>
<?php endif; ?>
							</tbody>
						</table>
                    </article>

<?php
    if (!isset($challenge->attempt) || $challenge->attempt !== true):
?>
                    <article>
                        <h1 class='title'>Help</h1>
<?php
        if (isset($currentChallenge->data['hint'])):
?>
                        <a class='left button challenge-hint' href='#'>Show hint</a>
<?php
        endif;
?>
                        <a class='left button' href='/forum/challenge-discussion/<?=$app->utils->generateSlug($currentChallenge->group);?>-challenges/<?=$app->utils->generateSlug($currentChallenge->group);?>-challenge-<?=$currentChallenge->name;?>'>Forum</a>
                    </article>
<?php
    endif;
?>
