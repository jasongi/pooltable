<?php

include 'include.php';
#callback for blockchain.info
#Connect to DB
mysql_connect($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysql_error());
mysql_select_db($mysql_database) or die( "Unable to select database. Run setup first.");

#grab trasaction details
$inputaddress = $_GET['input_address'];
$transaction_hash = $_GET['transaction_hash'];
$value_in_sat = $_GET['value'];

#make sure you aren't a hacker (you'll be free)
if ($_GET['secret'] != $secret) {
  echo 'Invalid Secret';
  return;
}

#refresh the exchange rate (dirty hack, could fix later)
    $url = "http://"."$mysite_root";
    $val = file_get_contents($url);
#get the exchange rate
    $costquery = mysql_query("SELECT * FROM exchange_rate LIMIT 0,1");
    $row = mysql_fetch_assoc($costquery);
    $cost = $row['value'];
#check to see if it's within 1000 satoshi's (about a cent)
if ($_GET['value'] >= $cost-1000) {
#add to database (if it wasn't enough, it's a charitable ComSSA donation)
    $gametime = date( 'Y-m-d H:i:s', time());
  $result = mysql_query("insert INTO pool_payments (recieve_address, transaction_hash, value, gametime) values('$inputaddress', '$transaction_hash', $value_in_sat, $gametime)");
  if($result) {
echo "*ok*";
  }
} else {
   #blockchain ignores this.
   echo "error";
}

?>