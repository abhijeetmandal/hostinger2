<?php
    $custom_css = array('faq.scss');
    $custom_js = array('faq.js');
    $page_title = 'payment';
    require_once('../../files/init.php');
    $payments = new payments($app);
    require_once('../../files/header.php');

$postdata = $_POST;
$msg = '';
if (isset($postdata ['key'])) {
	$key				=   $postdata['key'];
	$salt				=   "o3KLIjmuwg"; //$postdata['salt'];
	$txnid 				= 	$postdata['txnid'];
        $amount      		= 	$postdata['amount'];
	$productInfo  		= 	$postdata['productinfo'];
	$firstname    		= 	$postdata['firstname'];
	$email        		=	$postdata['email'];
	$udf5				=   $postdata['udf5'];
	$mihpayid			=	$postdata['mihpayid'];
	$status				= 	$postdata['status'];
	$resphash			= 	$postdata['hash'];
	//Calculate response hash to verify	
	$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
	$keyArray 	  		= 	explode("|",$keyString);
	$reverseKeyArray 	= 	array_reverse($keyArray);
	$reverseKeyString	=	implode("|",$reverseKeyArray);
	$CalcHashString 	= 	strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString));
	
	
	if ($status == 'success'  && $resphash == $CalcHashString) {
		//$msg = "Transaction Successful and Hash Verified...";
		//Do success order processing here...
                $result = $payments->storePayment($key, $salt,$txnid,$amount,$productInfo,$firstname,$email,$mihpayid,$resphash,$status);
                //$mkey, $salt, $txn, $amount, $product , $fname, $email, $mihpayid, $hash, $status
                if ($result) {
                    $app->utils->message('Transaction Successful', 'good');
                } else {
                    $app->utils->message('Something went wrong');
                }
                echo '<i class="icon-trophy colour-gold"></i> Awarded <a href="/medals.php#vip" class="medal medal-gold">vip</a>';
	}
	else {
		//tampered or failed
		//$msg = "Payment failed for Hash not verified...";
                $app->utils->message('Payment failed for Hash not verified');
	} 
}
//else exit(0);


?>

    <br/>
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
	