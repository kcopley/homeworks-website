<?php
require_once('security.php');
require_once('dbconnect.php');
$stamp='';
if(isset($_POST['transaction'])){$stamp=$_POST['transaction'];}
foreach ($_POST as $key => $value) 
	{
	if($value=='true'){
		$KeyVal=$key;
		mysql_query("DELETE FROM Sales_Purchase WHERE stamp='$stamp' AND product_id='$KeyVal' ")
		;}
  	}
mysql_close($con);
?>
<h1>Transaction Voided </h1>
<br />
<br />
if this was a credit transaction <a href="https://www.oc2net.net/billing/login.asp" target="_blank">click here</a>
<br />
<br />
<a href="index.php">Return Home</a>