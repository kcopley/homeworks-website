<?php
require_once('security.php');
require_once('dbconnect.php');
$stamp='';$product_id=$_GET['id'];$quantity=0;
if(isset($_SESSION['stamp'])){$stamp=$_SESSION['stamp'];}
$result = mysql_query("Select * FROM temp_sale WHERE user_id='$stamp' AND product_id='$product_id'");
$counter=0;
while($row = mysql_fetch_array($result))
{
$quantity=$quantity	+($row['quantity']*1);
$counter++;
}

if($counter>1)
{
mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp' AND product_id='$product_id' LIMIT 1");
}
else
{
	if($quantity>1)
	{echo $quantity; 
		$coutner2=0;
		$result2 = mysql_query("Select * FROM temp_sale WHERE user_id='$stamp' AND product_id='$product_id'");
		while($row2 = mysql_fetch_array($result2))
		{
			$coutner2++;
			if($coutner2=1)
			{
			mysql_query("UPDATE temp_sale SET quantity = quantity-1 WHERE user_id = '$stamp'");
			}
		}
	}
	else{mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp' AND product_id='$product_id' LIMIT 1");}
}

mysql_close($con);
header( "Location: sales.php?stamp=$stamp") ;

?>