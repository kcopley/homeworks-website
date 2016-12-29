<?php
session_start();
require_once('../pos/dbconnect.php');

if(isset($_SESSION['stamp'])) {
	$stamp=$_SESSION['stamp'];
	error_log("Assigning session stamp to variable..", 0);
}

function checkCode($testCode){
	$result = mysql_query("SELECT * FROM shipping_codes WHERE active='Active' AND code='$testCode'");
	$test=false;
	while($row = mysql_fetch_array($result))
	{
		$test=true;
	}
	return $test;
}

$actualCode=checkCode($_POST['code']);

if($actualCode) {
	$_SESSION['error']='';
	mysql_query("UPDATE temp_sale SET shipping = 0 WHERE user_id = '$stamp'");
}
else {
	$_SESSION['error']='<h2>Invalid Discount Code</h2>';
}
	
mysql_close($con);
header( 'Location: /index.php?page=cart' ) ;
?>