<?php
session_start();
require_once('security.php');
require_once('dbconnect.php');
$stamp='';
if(isset($_GET['$stamp'])){$stamp=$_GET['$stamp'];}
mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp'");
mysql_close($con);	

header("Location: index.php");
?>