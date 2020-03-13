<?php
    class payments {
        private $app;
        private $sizes = array('s', 'm', 'l', 'xl', 'xxl');

        public function __construct($app) {
            $this->app = $app;
        }

        public function getAll() {
            $st = $this->app->db->prepare("SELECT users.username, payments.txn_id, payments.fname, payments.amount, payments.time
                FROM users_payments payments
                LEFT JOIN users
                ON users.user_id = payments.user_id
                ORDER BY `time` DESC");

            $st->execute();
            $result = $st->fetchAll();

            return $result;
        }
        
        public function getCount() {
            $st = $this->app->db->prepare("SELECT count(user_id) AS `count` FROM users_payments");

            $st->execute();
            $result = $st->fetch();

            //return $result;
            //return $result->count;
            return $result ? (int) $result->count : 0;
        }



        public function makeTransaction($amount, $size) {
            
        }

        public function confirmPayment($token, $id) {
            return false;
        }

        public function storePayment($mkey, $salt, $txn, $amount, $product , $fname, $email, $mihpayid, $hash, $status) {
            $st = $this->app->db->prepare('INSERT INTO users_payments (`user_id`,`merchant_key`,`merchant_salt`,`txn_id`, `amount`, `product`,`fname`,`email`,`mihpayid`,`hash`,`status`)
                VALUES (:uid, :mkey, :salt, :txn, :amount, :prod, :fname, :email, :mihpayid, :hash , :status )');
            $result = $st->execute(array(':uid' => $this->app->user->uid, ':mkey' => $mkey , ':salt' => $salt , ':txn' => $txn,':amount' => $amount,  ':prod' => $product , ':fname' => $fname , ':email' => $email , ':mihpayid' => $mihpayid , ':hash' => $hash , ':status' => $status ));
            
            $this->app->user->awardMedal('vip', '3');
            
            return $result;
        }

    }
?>    
