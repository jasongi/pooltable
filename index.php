<?php

include 'include.php';
#set the timezone
$timezone = "Australia/Perth";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);

#connect to the DB
mysql_connect($mysql_host, $mysql_username, $mysql_password) or die(__LINE__ . ' Invalid connect: ' . mysql_error());
mysql_select_db($mysql_database) or die( "Unable to select database. Run setup first.");

#it costs two dollars AUD for pool
$url = "https://blockchain.info/tobtc?currency=AUD&value=2";
#convert to an int because moeny
$val = intval(file_get_contents($url)*100000000);
#grab the exchange rate from the DB
$sth = mysql_query("SELECT * FROM exchange_rate LIMIT 0,1");
#if our new rate is used, it will expire in 4 hours
$exp = date( 'Y-m-d H:i:s', time() + 4*60*60 );
#grab the data from the query
$row = mysql_fetch_assoc($sth);

#check to see if there actually is something in the table, if not...
if((!$row)) {
    #insert the current exchage rate and expiry time into table
    $sth = mysql_query("INSERT INTO exchange_rate (value,ttd) values ('$val', '$exp')");
} else {
#this grab the values from the query
$value = $row['value'];
$expdate = strtotime($row['ttd']);
    #check if they're expired
    if ( ($expdate) and ($expdate < time()))
    {
        #these are expired so delete and replace with the new values
        $delete = mysql_query("DELETE FROM exchange_rate");
        $sth = mysql_query("INSERT INTO exchange_rate value (value,ttd) values ('$val', '$exp')");
    } else {
        #these are not expired so we can use them now
        $val = $value;
        $exp = date( 'Y-m-d H:i:s', $expdate);
    }

}
    #convert to BTC representation
    $val = doubleval($val)/100000000;
    $str = "The current pool price is $val BTC and expires at $exp";
    echo $str;
?>