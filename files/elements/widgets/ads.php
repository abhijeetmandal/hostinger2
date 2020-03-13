<?php
	$ads = array(
			//array('donate', 'https://crushit.fit/payu/'),
			array('facebook', 'https://www.facebook.com/'),
			array('twitter', 'https://twitter.com/')
		  );


	$id = array_rand($ads);
	$image = $ads[$id][0];
	$link = $ads[$id][1];
?>

                   <article class="widget">
                        <div class="center">
                            <a href='<?=$link;?>' class='hide-external'>
                            	<img src='https://crushit.fit/files/images/sidebar/<?=$image;?>.png'/>
                            </a>
                        </div>
                    </article>
