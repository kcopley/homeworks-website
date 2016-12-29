<?php
require_once('security.php');
require_once('dbconnect.php');
session_start();
$stamp='';$rate=0.0865;$buffer='';$cancel='';$grandTotal=0;$totalTax='';
if(isset($_SESSION['rate'])){$rate=$_SESSION['rate'];}
if(isset($_SESSION['tax'])){$rate=$_SESSION['tax'];}

$message='Please Fill Out All Fields';$d=date('mdY');
	$t=time();
if(isset($_GET['error'])){$message='Please Fill Out All Fields';}else{$message='';}
if(isset($_GET['stamp'])){$stamp=$_GET['stamp'];
$_SESSION['stamp']=$stamp;
	//build table
	
	$buffer='<table cellpadding="2" cellspacing="0" border="1"><tr><th>Name</th><th>Price</th><th>Quantity</th><th>Tax</th> <th>Total</th></tr>
';$cancel="<a href='cancel.php?stamp=".$stamp."' class='cancel'>CANCEL</a>";
	$name=''; $price=0; $quantity=0; $tax=0; $total=0;$grandTotal=0;
	$result = mysql_query("SELECT * FROM temp_sale where user_id='$stamp'");
	while($row = mysql_fetch_array($result))
	{
	$name=$row['product_name']; $price=number_format($row['sale_price'], 2, '.', ','); $quantity=$row['quantity']; 
	$tax=number_format($row['tax'], 2, '.', ',');
	$total=number_format(($price*$quantity)+$tax, 2, '.', ',');
	$buffer.='<tr><td>'.$name.' <a href="remove.php?id='.$row['product_id'].'" style="color:red;">remove</a></td><td>$'.$price.'</td><td>'.$quantity.'</td><td>$'.$tax.'</td><td>$'.$total.'</td></tr>';
	$grandTotal=number_format($grandTotal+$total, 2, '.', ',');
	$totalTax=$tax+$totalTax;
	}
	$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Grand Total</th> <th>$'.$grandTotal.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th> <th><input type="hidden" value="'.$stamp.'"><input type="submit" value="Finish"></th></tr></table>';
}
else{$stamp=$d.$t.rand(9, 9);}
mysql_close($con);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link rel="stylesheet" type="text/css" href="/main.css">
<title>Sales</title>
<style>
#ccProcess{
position:absolute;
display:none;
height:500px;
width:500px;
top:0; left:500px;
background-color:#CCCCCC;

}
</style>
<style> 
body {
font-family:verdana;
font-size:15px;
}
 
a {color:#333; text-decoration:none}
a:hover {color:#ccc; text-decoration:none}
 
#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
}
  
#boxes .window {
  position:absolute;
  left:0;
  top:0;
  width:160px;
  height:32px;
  display:none;
  z-index:9999;
  padding:20px;
}
 
#boxes #dialog {
  width:160px; 
  height:32px;
  padding:10px;
  background-color:#ffffff;
}
</style>
<script >
var realpay=<?php echo $grandTotal;?>;
function switchMethod(val){
	if(val=='credit')
	{document.getElementById('ccProcess').style.display='block';}
	else
	{document.getElementById('ccProcess').style.display='none';}
}
function checkChange(val){
	
var total=val-realpay;
var diff=Math.round(total * 100) / 100;
	if(diff>0){
	document.getElementById('changeRow').style.display='block';
	document.getElementById('change').innerHTML='$'+diff;
	document.getElementById('chagAmt').value=diff;
	}
	else{
	document.getElementById('changeRow').style.display='none';document.getElementById('change').innerHTML='';
	}
}
</script>
<script>
function creditProcess(payMethod)
{
	var tax='<?php echo $tax;?>';
	var poId='<?php echo $salesId;?>';
	var paymentAmount=document.getElementById('payment_amount').value;
	if(payMethod=='Credit'||payMethod=='Debit')
	{
	document.getElementById('frame').src='credit-card.php?tax=<?php echo $tax;?>&total='+paymentAmount+'&saleType=Job&&stamp=<?php echo $stamp;?>poNum=<?php echo $salesId;?>';
	document.getElementById('popUp').style.display='block';
	}
	if(payMethod=='Account'){
		document.getElementById('frame').src='pay-account.php?tax=<?php echo $tax;?>&total='+paymentAmount+'&saleType=Job&&stamp=<?php echo $stamp;?>poNum=<?php echo $salesId;?>';
		document.getElementById('popUp').style.display='block';
		}
		else{
			document.getElementById('customer_id').value='';
			}
}
</script>
<link rel="stylesheet" type="text/css" href="../public_html/main.css">
</head>
<body><?PHP require_once('navigation.php');?><div id="popUp" style="display:none"><a id="close" onclick="document.getElementById('popUp').style.display='none'" >X</a><div class="clear"></div><iframe frameborder="0" height="300" width="500" scrolling="no" src="#"id="frame"> </iframe></div>

<?php echo $_SESSION['error'];?>
<div style="float:left; width:49%">
<form method="post" action="add-temp.php">
<input type="hidden" name="stamp" value="<?php echo $stamp;?>"/>
<table border="1" cellpadding="2"><tr>
  <td><strong>Quantity:</strong></td>
  <td><input type="text" name="quantity" value="1" size="5" /></td>
</tr>
 <tr>
<td><strong>ID:</strong></td> 
    <td><input type="id" id="id" name="id" value=""/></td>
   
  </tr>
  <tr>
  <tr>
<td><strong>ISBN:</strong></td>
    <td><input type="text" name="isbn" value=""/></td>
  </tr>
  <tr>
    <td><strong>ISBN2:</strong></td>
    <td><input type="text" name="isbn2" value="" /></td>
  </tr>
  <tr>
    <td><strong>Tax Rate %</strong></td>
    <td align="right"><input type="text" name="tax" value="<?php echo $rate*100;?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><input type="submit" value="Add" /></td>
  </tr>
</table></form><br />
<br />
</div>
<div style="float:right; width:49%"><h3>Add Credit</h3>
<form method="post" action="add-temp.php">
<table border="1" cellpadding="2">
  <tr>
    <td><strong>Amount:</strong></td>
    <input type="hidden" name="isCredit" value="true" />
    <input type="hidden" name="tax" value="<?php echo $rate*100;?>" />
    <input type="hidden" name="stamp" value="<?php echo $stamp;?>"/>
    <td><input type="text" value="" name="credit" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><input type="submit" value="Submit" /></td>
  </tr>
</table>
</form>
</div>
<div style="clear:both"></div>
<form method="post" action="check-out.php" name="checkout">
<input type="hidden" name="stamp" value="<?php echo $stamp;?>" />
<input type="hidden" name="customer_id" id="customer_id" value="" />
<input type="hidden" name="SaleTaxAmount" value="<?php echo $totalTax;?>" />
<?php echo $buffer;?>
<?php echo $cancel;?>
<table cellpadding="2" cellspacing="0" border="1"><tr><td>Payment Method</td><td> <select name="paymentMethod" id="paymentMethod" onchange="creditProcess(this.value)" >

        <option value="Cash" selected="selected">Cash</option>
        <option value="Credit">Credit</option>
        <option value="Debit">Debit</option>
        <option value="Check">Check</option>
         <option value="Account">Account</option>
         <option value="Mixed">Mixed</option>
      </select> <span id="custname"></span></td></tr>
  <tr>
    <td id="title">Payment Amount</td>
    <input type="hidden" name="realpayment_amount" value="<?php echo $grandTotal;?>" />
    
    <td><input type="text" value="<?php echo $grandTotal;?>" id="payment_amount" name="payment_amount" size="7" onblur="checkChange(this.value)" />
    
    </td>
  </tr>
  <tr id="changeRow" style="display:none">
    <td>Change:</td>
    <td id="change">&nbsp;</td>
     <input type="hidden" name="chagAmt" id="chagAmt" />
  </tr>
</table>
<div id="ccProcess"  ><iframe height="500" width="500" id="iframe" scrolling="no" frameborder="0"></iframe></div>

</form>
<script>document.getElementById('id').focus();</script>
</body>
</html>
