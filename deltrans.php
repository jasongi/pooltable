<?php

include 'include.php';

#Connect to the DB
mysql_connect($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysql_error());
mysql_select_db($mysql_database) or die( "Unable to select database. Run setup first.");

#grab the trans_id you want to process
$trans = $_GET['trans'];

#make sure you're not a hacker
if ($_GET['secret'] != $secret) {
  echo 'Invalid Secret';
  return;
}

#move to processed table
$move = mysql_query("INSERT INTO pool_payments_processed (recieve_address,transaction_hash,value,gametime) SELECT recieve_address,transaction_hash,value,gametime FROM pool_payments WHERE transaction_hash='$trans'");
#delete from unprocessed table
$sth = mysql_query("DELETE FROM `pool_payments` WHERE transaction_hash='$trans'");

#only send the signal back IF it is both transferred and deleted to prevent double spending
  if($sth and $move) {
    echo '*ok*';}

?>