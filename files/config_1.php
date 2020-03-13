<?php
    $config['domain'] = 'crushit.xyz';
    //$config['domain'] = 'localhost';

    // Site variables
    $config['path'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/../');
	//$config['path'] = realpath($_SERVER["DOCUMENT_ROOT"]);
	//$config['path'] = realpath("C:/wamp64/www");

    // Database configuration
    $config['db']['driver'] = 'mysql';
    $config['db']['host'] = 'localhost';
    $config['db']['username'] = 'u280902347_crush';
    $config['db']['password'] = 'GF8J2gZ8cdDj';
    $config['db']['database'] = 'u280902347_crush';

    // SMTP configuration
    $config['smtp']['host'] = 'smtp.hostinger.com';
    $config['smtp']['port'] = '587';
    $config['smtp']['username'] = 'hello@crushit.fit';
    $config['smtp']['password'] = 'sahiyogita1A';

    $config['git'] = 'anystring';
    
    $config['facebook']['secret'] = '';
    $config['facebook']['public'] = '';
    $config['facebook']['token'] = '';

    $config['twitter']['secret'] = '';
    $config['twitter']['public'] = '';

    $config['lastfm']['public'] = '';

    $config['socket']['address'] = '';
    $config['socket']['key'] = '';

    $config['ssga-ua'] = '';
?>