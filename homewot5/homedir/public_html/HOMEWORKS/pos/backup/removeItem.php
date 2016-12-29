<?php
require_once('dbconnect.php');
$stamp='';
$product_id=$_GET['id'];
if(isset($_SESSION['stamp'])){$stamp=$_SESSION['stamp'];}
mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp' AND temp_id='$product_id'");
mysql_close($con);
header( 'Location: ../index.php?page=cart' ) ;
?> 