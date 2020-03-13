<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class
 *
 * @author abhijeet
 */
class geolocations {
//put your code here
    private $app;
    
    public function __construct($app) {
        $this->app = $app;
    }
    
    public function setLocation($uid,$latitude,$logitude){
        $st = $this->app->db->prepare('UPDATE users_geolocations SET latitude=:lat,longitude=:lon,time=now() where user_id=:uid');
        $result = $st->execute(array(':lat' => $latitude, ':lon' => $logitude, ':uid' => $uid));

        return $result;
    }
    
    
    public function getLocation($uid){
        
        // Get event count
        $st = $this->app->db->prepare("SELECT users.username,users_geolocations.latitude,users_geolocations.longitude,users_geolocations.time
            FROM users_geolocations
            JOIN users ON users.user_id=users_geolocations.user_id
            WHERE users_geolocations.user_id = :user_id");
        $st->execute(array(':user_id' => $uid));
        $result = $st->fetch();
        
        return $result;
    }
    
    public function getNearbyTrainers($uid=null,$distance=0){
        
        if($uid==null){
            $uid=$app->user->uid;
        }
//            $sql="SELECT * FROM (
//	SELECT trainer.user_id,trainer.username,trainer.email,trainer.mobile,trainer.latitude,trainer.longitude,trainer.time,
//	   111.111 *
//		DEGREES(ACOS(LEAST(COS(RADIANS(user.Latitude))
//			 * COS(RADIANS(trainer.Latitude))
//			 * COS(RADIANS(user.Longitude - trainer.Longitude))
//			 + SIN(RADIANS(user.Latitude))
//			 * SIN(RADIANS(trainer.Latitude)), 1.0))) AS distance_in_km
//	  FROM (SELECT geolocations.*
//	FROM users
//	LEFT JOIN users_geolocations as geolocations ON geolocations.user_id=users.user_id) AS user
//	  JOIN (SELECT users.username,users.email,users.mobile, geolocations.*
//	FROM users
//	LEFT JOIN users_profile as profile ON users.user_id = profile.user_id
//	LEFT JOIN users_geolocations as geolocations ON geolocations.user_id=users.user_id
//	JOIN users_medals ON users_medals.user_id=users.user_id AND users_medals.medal_id = (SELECT medal_id FROM medals WHERE label = 'vip')
//	) AS trainer ON user.user_id <> trainer.user_id
//	 WHERE user.user_id = :user_id
//) VIEW1";
        
                    $sql="SELECT * FROM (
	SELECT trainer.user_id,trainer.username,trainer.email,trainer.mobile,trainer.latitude,trainer.longitude,trainer.time,
	   111.111 *
		DEGREES(ACOS(LEAST(COS(RADIANS(user.Latitude))
			 * COS(RADIANS(trainer.Latitude))
			 * COS(RADIANS(user.Longitude - trainer.Longitude))
			 + SIN(RADIANS(user.Latitude))
			 * SIN(RADIANS(trainer.Latitude)), 1.0))) AS distance_in_km
	  FROM (SELECT geolocations.*
	FROM users
	LEFT JOIN users_geolocations as geolocations ON geolocations.user_id=users.user_id) AS user
	  JOIN (SELECT users.username,users.email,users.mobile, geolocations.*
	FROM users
	LEFT JOIN users_profile as profile ON users.user_id = profile.user_id
	LEFT JOIN users_geolocations as geolocations ON geolocations.user_id=users.user_id
	JOIN users_medals ON users_medals.user_id=users.user_id AND users_medals.medal_id = (SELECT medal_id FROM medals WHERE label = 'vip')
	) AS trainer
	 WHERE user.user_id = :user_id
) VIEW1";
        
        if($distance>0){
            $sql .=" WHERE distance_in_km <= :distance";
        }
        
        // Get event count
        $st = $this->app->db->prepare($sql);
        
        //$st->execute(array(':user_id' => 8));
        $st->execute(array(':user_id' => $uid,':distance' => $distance));
        $result = $st->fetchAll();
        
        return $result;
    }
    
}
