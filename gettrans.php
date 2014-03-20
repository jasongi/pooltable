<?php

include 'include.php';
#get an unprocessed transaction

#connect to the DB
mysql_connect($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysql_error());
mysql_select_db($mysql_database) or die( "Unable to select database. Run setup first.");

#check the secret to make sure you aren't a hacker
if ($_GET['secret'] != $secret) {
  echo 'error';
  return;
}

#grab an unprocessed transaction
$sth = mysql_query("SELECT * FROM `pool_payments` LIMIT 0,1");

#check that there was one available
if($sth) {
#encode the transaction data and output it
$r = mysql_fetch_assoc($sth);
print json_encode($r);
}
else {
        #the python script ignores this
        echo 'error';
}


?>