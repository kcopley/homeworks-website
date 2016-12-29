<?php
require_once('security.php');
require_once('dbconnect.php');
$stamp='';$date=date('Y-m-d');$creditAmt=0;
$cashAmt=0;$chng='';
if(isset($_POST['chagAmt'])){$chng='<tr><td colspan=4>CHANGE DUE</td><td>$'.$_POST['chagAmt'].'</td></tr>';}
if($_POST['payment_amount']!=$_POST['realpayment_amount']&&$_POST['payment_amount']<$_POST['realpayment_amount']){
	$creditAmt=$_POST['realpayment_amount']-$_POST['payment_amount'];
    $cashAmt=$_POST['payment_amount'];
	}

$removeStatement='';
function getQuant($prodid){
	$retVal='';;
	$result = mysql_query("SELECT quantity FROM cms_module_products where id='$prodid'");
	while($row = mysql_fetch_array($result))
	{
	$retVal=$row['quantity'];
	}
	return $retVal;
}
function getBal($custId){
	$retVal='';;
	$result = mysql_query("SELECT balance FROM customers where customer_id='$custId'");
	while($row = mysql_fetch_array($result))
	{
	$retVal=$row['balance'];
	}
	return $retVal;
}
$transaction=$_POST['paymentMethod'];
if(isset($_POST['stamp'])){$stamp=$_POST['stamp'];}else{header('Location: sales.php?error=true');}
$buffer='<table cellpadding="2" cellspacing="0" border="1"><tr><th>Name</th><th>Price</th><th>Quantity</th><th>Tax</th> <th>Total</th></tr>
';$cancel="<a href='cancel.php?stamp=".$stamp."' class='cancel'>CANCEL</a>";
	$name=''; $price=0; $quantity=0; $tax=0; $total=0;$grandTotal=0;$product_id='';
	$result = mysql_query("SELECT * FROM temp_sale where user_id='$stamp'");
	while($row = mysql_fetch_array($result))
	{$product_id=$row['product_id'];
	$name=$row['product_name']; $price=number_format($row['sale_price'], 2, '.', ','); $quantity=$row['quantity']; 
	$tax=number_format($row['tax'], 2, '.', ',');
	$total=number_format(($price*$quantity)+$tax, 2, '.', ',');
	$buffer.='<tr><td>'.$name.'</td><td>$'.$price.'</td><td>'.$quantity.'</td><td>$'.$tax.'</td><td>$'.$total.'</td></tr>';
	
	$grandTotal=number_format($grandTotal+$total, 2, '.', ',');
	// ADD TO SALES
	mysql_query("INSERT INTO Sales_Purchase (product_id, tax, price, quantity, date, pors, stamp, transaction)
VALUES ('$product_id','$tax','$total','$quantity', '$date', 'Sale', '$stamp', '$transaction')");

	//remove from inventory
	$newQuant= getQuant($product_id)-$quantity;
	mysql_query("UPDATE cms_module_products SET quantity = '$newQuant' WHERE id = '$product_id'");
	}
	$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Grand Total</th> <th>$'.$grandTotal.'</th></tr>';
	if($creditAmt!=0){
		$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Cash Payment Amount</th> <th>$'.$cashAmt.'</th></tr>';
		$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Credit Payment Amount</th> <th>$'.$creditAmt.'</th></tr>';
		}
		$buffer.=$chng;
	$buffer.='</table>';
	if($_POST['customer_id']!=''&&$_POST['paymentMethod']=='Account'){
		//get Balance
		$customer_id=$_POST['customer_id'];
		$newBal=getBal($_POST['customer_id'])+$grandTotal;
		//update balance
		mysql_query("UPDATE customers SET balance = '$newBal' WHERE customer_id = '$customer_id'");
		echo "UPDATE customers SET balance = '$newBal' WHERE customer_id = '$customer_id'";
		}
	//remove from temp
	mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp' ");
	
mysql_close($con);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Check Out</title>
</head>

<body><div style="width:400px;" align="center">
<h1>HomeWorks</h1>
<h2>
Sales Receipt
</h2>



TRANSACTION <br />
<img src="/barcodegen/bar128.php?upc=<?php echo $stamp;?>" height="40"><br /><br />


<?PHP echo $buffer;?>
<input type="button" value="Print"  onclick="window.print();"/>  |  <input type="button" value="Home" onclick="window.location.href='index.php'" /><br />
<br /><br /><br />
<hr />
<h3>Customer Signature</h3>
<div style="break-after:always"></div></div>
</body>
</html>
