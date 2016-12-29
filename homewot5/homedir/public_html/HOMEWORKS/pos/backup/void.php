<?php
require_once('security.php');
require_once('dbconnect.php');
$stamp='';
if(isset($_POST['transaction'])){$stamp=$_POST['transaction'];}
function getQuant($prodid){
	$retVal='';;
	$result = mysql_query("SELECT quantity FROM cms_module_products where id='$prodid'");
	while($row = mysql_fetch_array($result))
	{
	$retVal=$row['quantity'];
	}
	return $retVal;
}

function getName($prodid){
	$retVal='';;
	$result = mysql_query("SELECT product_name FROM cms_module_products where id='$prodid'");
	while($row = mysql_fetch_array($result))
	{
	$retVal=$row['product_name'];
	}
	return $retVal;
}

$buffer='<form method="post" action="voidTrans.php"><table cellpadding="2" cellspacing="0" border="1"><tr><th>&nbsp;</th><th>Name</th><th>Price</th><th>Quantity</th><th>Tax</th> <th>Total</th></tr>
';$cancel="<a href='cancel.php?stamp=".$stamp."' class='cancel'>CANCEL</a>";
	$name=''; $price=0; $quantity=0; $tax=0; $total=0;$grandTotal=0;$product_id='';
	
$result = mysql_query("SELECT * FROM Sales_Purchase where stamp='$stamp'");
	while($row = mysql_fetch_array($result))
	{
	$product_id=$row['product_id'];
	$mday=date('Y-m-d');
	$oldQuant=getQuant($product_id);
	$name=getName($product_id); 
	$price=number_format($row['price'], 2, '.', ','); 
	$quantity=$row['quantity']; 
	$tax=number_format($row['tax'], 2, '.', ',');
	$total=number_format(($row['price']*$row['quantity'])+$tax, 2, '.', ',');
	$buffer.='<tr id="p'.$product_id.'"><td><a href="#" onclick="removeItem(\''.$product_id.'\')">remove item</a></td><td>'.$name.'</td><td>$'.$price.'</td><td>'.$quantity.'</td><td>$'.$tax.'</td><td>$'.$total.'</td><input type="hidden" name="'.$product_id.'" id="'.$product_id.'" value="true" class="'.$total.'" ></tr>';
	$grandTotal=number_format($grandTotal+$total, 2, '.', ',');
	$newQuant=$oldQuant+$quant;
	mysql_query("UPDATE cms_module_products SET quantity = '$newQuant' WHERE id = '$product_id'");
	}
	$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Grand Total</th> <th id="gt">$'.$grandTotal.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th> <th><input type="hidden" className="stamp" value="'.$stamp.'" name="transaction"><input type="submit" value="Finish"></th></tr></table></form><br />
<br />
';

//mysql_query("DELETE FROM Sales_Purchase WHERE stamp='$stamp' ");
mysql_close($con);
echo $buffer;
?><script>

function removeItem(itemId){
	var pval='p'+itemId;
	document.getElementById(pval).style.display="none";
	document.getElementById(itemId).value='false';
	var tot= calcTotal();
	document.getElementById('gt').innerHTML='$'+tot;
	}
function calcTotal(){
	var total=0;	
	var inputs=document.getElementsByTagName('input');
	for (x in inputs)
	{
	if(inputs[x].value!='Finish' && inputs[x].value!='false'&& inputs[x].className!='stamp'){
		total=total+((inputs[x].className)*1)
		}
	}
	if(isNaN(total)){total=0;}
	return total;
	}	
</script>