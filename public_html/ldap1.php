<?php

$server='ldap.forumsys.com';  
$port='389';
$admin='cn=read-only-admin,dc=example,dc=com';
$passwd='password';

echo "hello1 <br/>";
$ds=ldap_connect($server,$port);  // assuming the LDAP server is on this host
echo "hello2 <br/>";
if ($ds) {
    // bind with appropriate dn to give update access
    $r=ldap_bind($ds, $admin, $passwd);
    if(!$r) die("ldap_bind failed<br>");

    echo "ldap_bind success";
    ldap_close($ds);
} else {
    echo "Unable to connect to LDAP server";
}
?>