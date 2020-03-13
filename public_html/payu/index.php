<?php


function getCallbackUrl()
{
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'response.php';
}

//define('PAGE_PUBLIC', true);
$custom_css = array('levels.scss');
$custom_js = array('levels.js');
require_once('../../files/init.php');
$payments = new payments($app);
$page_title = 'payment';
require_once('../../files/header.php');
//$profile = new profile($app->user);
if(!$app->user->vip)
{    
    $count = $payments->getCount();
?>

<h3 class='level-section'><a href=''>CRUSH IT MEMBERSHIP Offer#1: Available for 1<sup>st</sup> 10 users only.</a></h3>
<ul class='levels-list plain clr'>
        <li>
            <a class="progress-<?= ($count<=10)?2:0 ; ?>" href=<?= ($count<=10)?"javascript:launchBOLT()":"javascript:alert('not&nbsp;active')" ?>>
                <span class="right"><strike>&#8377; 1000</strike> &nbsp;&nbsp; &#8377; 50</span>
                <span class="level-title">1 Year Membership</span>
                <span class="left dark small">get &#8377; 950 discount</span>
                <span class="right dark small">left: <?= ($count<=10)?10 - $count:0 ; ?></span>
            </a>
        </li>
</ul>

<h3 class='level-section'><a href=''>CRUSH IT MEMBERSHIP Offer#2: Available for next 100 users only.</a></h3>
<ul class='levels-list plain clr'>
        <li>
            <a class="progress-<?= ($count>10 && $count<=100)?2:0 ; ?>" href=<?= ($count>10 && $count<=100)? "javascript:launchBOLT()" : "javascript:alert('not&nbsp;active')"; ?>>
                <span class="right"><strike>&#8377; 1000</strike> &nbsp;&nbsp; &#8377; 100</span>
                <span class="level-title">1 Year VIP Membership</span>
                <span class="left dark small">get &#8377; 900 discount</span>
                <span class="right dark small">left: <?= ($count<=10)?100:(($count>10 && $count<=100)?100 - ($count-10):0) ; ?></span>
            </a>
        </li>
</ul>
<h3 class='level-section'><a href=''>CRUSH IT MEMBERSHIP Offer#3: Available for next 1000 users only.</a></h3>
<ul class='levels-list plain clr'>
        <li>
            <a class="progress-<?= ($count>100 && $count<=1000)?2:0 ; ?>" href=<?= ($count>100 && $count<=1000)? "javascript:launchBOLT()" : "javascript:alert('not&nbsp;active')"; ?>>
                <span class="right"><strike>&#8377; 1000</strike> &nbsp;&nbsp; &#8377; 500</span>
                <span class="level-title">1 Year VIP Membership</span>
                <span class="left dark small">get &#8377; 500 discount</span>
                <span class="right dark small">left: <?= ($count<=110)?1000:(($count>100 && $count<=1000)?1000 - ($count-110):0) ; ?></span>
            </a>
        </li>
</ul>
<h3 class='level-section'><a href=''>CRUSH IT MEMBERSHIP</a></h3>
<ul class='levels-list plain clr'>
        <li>
            <a class="progress-<?= ($count>1110)?2:0 ; ?>" href=<?= ($count>1110)? "javascript:launchBOLT()" : "javascript:alert('not&nbsp;active')"; ?>>
                <span class="right">&#8377; 1000</span>
                <span class="level-title">1 Year VIP Membership</span>
                <span class="left dark small"></span>
                <span class="right dark small"></span>
            </a>
        </li>
</ul>




<script type="text/javascript"><!--
    <?php $txn="Txn" . rand(10000,99999999) ?>;
function launchBOLT()
{
	bolt.launch({
	key: 'u055R1WX',
	txnid: '<?= $txn ?>', 
	hash: '<?= hash('sha512', 'u055R1WX|'. $txn .'|50|P50|'.(($app->user->name!='')?$app->user->name:preg_replace('/[0-9_-]*[\.]*/','',$app->user->username)).'|'. $app->user->email .'|||||BOLT_KIT_PHP7||||||o3KLIjmuwg') ?>',
	amount: '50',
	firstname: '<?= ($app->user->name!='')?$app->user->name: preg_replace('/[0-9_-]*[\.]*/','',$app->user->username) ?>',
	email: '<?= $app->user->email ?>',
	phone: '<?= $app->user->mobile ?>',
	productinfo: 'P50',
	udf5: 'BOLT_KIT_PHP7',
	surl : 'https://crushit.fit/payu/response.php',
	furl: 'https://crushit.fit/payu/response.php',
	mode: 'dropout'	
},{ responseHandler: function(BOLT){
	console.log( BOLT.response.txnStatus );		
	if(BOLT.response.txnStatus != 'CANCEL')
	{
		//Salt is passd here for demo purpose only. For practical use keep salt at server side only.
		var fr = '<form action=\"https://crushit.fit/payu/response.php\" method=\"post\">' +
		'<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
		'<input type=\"hidden\" name=\"salt\" value=\"o3KLIjmuwg\" />' +
		'<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
		'<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
		'<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
		'<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
		'<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
		'<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
		'<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
		'<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
		'<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
		'</form>';
		var form = jQuery(fr);
		jQuery('body').append(form);								
		form.submit();
	}
},
	catchException: function(BOLT){
 		alert( BOLT.message );
	}
});
}

//launchBOLT();

//--
</script>	
<?php
}else{
    echo '<i class="icon-trophy colour-gold"></i> Awarded <a href="/medals.php#vip" class="medal medal-gold">vip</a>';
}
?>
<p>
        <h2 class='no-margin'>Recent VIP Members</h2>
        <table class="striped sortable">
            <thead>
                <tr>
                    <th width="20%">Username</th>
                    <th>Name</th>
                    <th>Transaction ID</th>
                    <th>When</th>
                </tr>
            </thead>
            <tbody>
<?php
    $payments = $payments->getAll();
    foreach($payments as $payment):
?>
                <tr>
                    <td><?=($payment->username)?$app->utils->userLink($payment->username):'Anonymous';?></td>
                    <td><?=$payment->fname;?></td>
                    <td><?=$payment->txn_id;?></td>
                    <td><time datetime="<?=date('c', strtotime($payment->time));?>"><?=$app->utils->timeSince($payment->time);?></time></td>
                </tr>
<?php
    endforeach;
?>
            </tbody>
        </table>
    </p>
<?php
    require_once('../../files/footer.php');
?>