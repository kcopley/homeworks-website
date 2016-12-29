<?php
require_once('pos/dbconnect.php');
$buffer='<h1>Cart is Empty</h1>';
if(isset($_SESSION['stamp'])){$stamp=$_SESSION['stamp'];
	$buffer='<table cellpadding="2" cellspacing="0" border="1"><tr><th>Name</th><th>Price</th><th>Tax</th> <th>Total</th></tr>
';$cancel="<a href='cancel.php?stamp=".$stamp."' class='cancel'>CANCEL</a>";
	$name=''; $price=0; $quantity=0; $tax=0; $total=0;$grandTotal=0;$shipTotal=0;
	$result = mysql_query("SELECT * FROM temp_sale where user_id='$stamp'");
	while($row = mysql_fetch_array($result))
	{
	$shipTotal=number_format($shipTotal+$row['shipping'], 2, '.', ',');
	$name=$row['product_name']; $price=number_format($row['sale_price'], 2, '.', ','); $quantity=$row['quantity']; 
	$tax=number_format($row['tax'], 2, '.', ',');
	$total=number_format(($price*$quantity)+$tax, 2, '.', ',');
	$buffer.='<tr><td>'.$name.'</td><td>$'.$price.'</td><td>$'.$tax.'</td><td>$'.$total.'</td></tr>';
	$grandTotal=number_format($grandTotal+$total+$shipping, 2, '.', ',');
	}
	$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>Shipping</th> <th>$'.$shipTotal.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>Grand Total</th> <th>$'.$grandTotal.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th> <th><input type="hidden" value="'.$stamp.'"><input type="submit" value="Finish" onclick="window.location.href=\'/index.php?page=checkout\'"></th></tr></table>

';
}
echo $buffer;
?>
