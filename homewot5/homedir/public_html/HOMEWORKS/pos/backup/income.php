<?php
require_once('security.php');
require_once('dbconnect.php');
function getProductName($prodId){
	$result=mysql_query("SELECT product_name FROM cms_module_products where id='$prodId'");
	$results='';
	while($row = mysql_fetch_array($result))
		{$results=$row['product_name'];}
		return $results;
}
$year=0;$buffer='';$buffer2='';$netTotal=0;$grandTotal=0;$totalPurchases=0;$result='';
$startDate=$_GET['startDate'];
$endDate=$_GET['endDate']; 
$sdatemonth=substr($startDate,0,2);
$sdateday=substr($startDate,3,2);
$sdateyear=substr($startDate,6,4);

$edatemonth=substr($endDate,0,2);
$edateday=substr($endDate,3,2);
$edateyear=substr($endDate,6,4);

$startDate=$sdateyear.'-'.$sdatemonth.'-'.$sdateday;
$endDate=$edateyear.'-'.$edatemonth.'-'.$edateday;




$result = mysql_query("SELECT * FROM Sales_Purchase where pors='Sale' AND date BETWEEN '$startDate' AND '$endDate'");
	$result2 = mysql_query("SELECT * FROM Sales_Purchase where (pors='Purchase' OR pors='Expense') AND date BETWEEN '$startDate' AND '$endDate'");
	$buffer.='<h2>Revenue\'s</h2><table border="1" cellpadding="2"><tr><td>Book Id</td><td>Book Name</td><td>Sale Date</td><td>Sale</td><td>Tax Collected</td><td>Payment Method</td><td>Total</td></tr>';
		while($row = mysql_fetch_array($result))
		{
			$sale=($row['price']*$row['quantity'])-$row['tax'];
			$total=$sale+$row['tax'];
			$totalSales=$totalSales+$sale;
			$totalTax=$totalTax+$row['tax'];
			$grandTotal=$grandTotal+$total;
		$buffer.='<tr><td>'.$row['product_id'].'</td><td>'.getProductName($row['product_id']).'</td><td>'.$row['date'].'</td><td>$'.$sale.'</td><td>$'.number_format($row['tax'], 2, '.', ',').' </td><td>'.$row['transaction'].'</td><td>$'.number_format($total, 2, '.', ',').'</td></tr>';
		}
		$buffer.='<tr><td colspan="3"><strong>TOTALS</strong></td><td><strong>$'.number_format($totalSales, 2, '.', ',').'</strong></td><td><strong>$'.number_format($totalTax, 2, '.', ',').'</strong></td><td>&nbsp;</td><td><strong>$'.number_format($grandTotal, 2, '.', ',').'</strong></td></tr>
</table>';
	
	
	
	$buffer2.='<h2>Expense\'s</h2><table border="1" cellpadding="2"><tr><td>Book ID</td><td>Book Name</td><td>Purchase Date</td><td>Price</td></tr>';
		while($row2 = mysql_fetch_array($result2))
		{
			$purchase=number_format($row2['price']*$row2['quantity'], 2, '.', ',');
			$totalPurchases=number_format($totalPurchases+$purchase, 2, '.', ',');
			$buffer2.='<tr><td>'.$row2['product_id'].'</td><td>'.getProductName($row2['product_id']).'</td><td>'.$row2['date'].'</td><td>$'.$purchase.'</td></tr>';
		}
		$buffer2.='<tr><td colspan="3"><strong>TOTALS</strong></td><td>$'.$totalPurchases.'</td></tr>
</table>';
$netTotal=number_format($totalSales-$totalPurchases, 2, '.', ',');

	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>REVENUES</title><link rel="stylesheet" type="text/css" href="/SpryAssets/SpryValidationTextField.css"/>
<script src="/SpryAssets/SpryValidationTextField.js"></script>
</head>
<body>
<?PHP require_once('navigation.php');?>
<h1>REVENUES</h1>
<form action="income.php" method="get">
  <table width="50%" border="0" cellpadding="2">
     <tr>
       <td><span id="sprytextfield1">
         <label for="startDate"></label>
         Start Date: <input type="text" name="startDate" id="startDate" />
        <span class="textfieldRequiredMsg">Start Date</span></span></td>
        <td><span id="sprytextfield2">
         <label for="endDate"></label>
         End Date: <input type="text" name="endDate" id="endDate" />
        <span class="textfieldRequiredMsg">End Date</span></span></td>
       <td><input type="submit" value="Submit" /></td>
     </tr>
     <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
   </table>
</form>
<br />
<br />
<div style="float:left; width:49%"> <?php echo $buffer;?> </div>
<div style="float:right; width:49%"> <?php echo $buffer2;?> </div>
<div style="clear:both"><strong><br />
  <br />
  NET TOTALS:  $<?php echo $netTotal;?> </strong></div>
  <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("startDate", "date", {format:"mm-dd-yyyy", hint:"mm-dd-yyyy", validateOn:["blur", "change"], useCharacterMasking:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("endDate", "date", {format:"mm-dd-yyyy", hint:"mm-dd-yyyy", validateOn:["blur", "change"], useCharacterMasking:true});

</script>

</body>
</html>