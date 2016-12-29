<?php
require_once('security.php');
require_once('dbconnect.php');
$year=0;$buffer='';
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
	$buffer.='<table border="1" cellpadding="2"><tr><td>Sale Date</td><td>Sale</td><td>Tax Collected</td><td>Total</td></tr>';
		while($row = mysql_fetch_array($result))
		{
			$sale=$row['price']*$row['quantity'];
			$total=$sale+$row['tax'];
			$totalSales=$totalSales+$sale;
			$totalTax=$totalTax+$row['tax'];
			$grandTotal=$grandTotal+$total;
		$buffer.='<tr><td>'.$row['date'].'</td><td>'.$sale.'</td><td>'.$row['tax'].' </td><td>'.$total.'</td></tr>';
		}
		$buffer.='<tr><td><strong>TOTALS</strong></td><td>'.$totalSales.'</td><td>'.$totalTax.'</td><td>'.$grandTotal.'</td></tr>
</table>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sales Tax</title><link rel="stylesheet" type="text/css" href="/SpryAssets/SpryValidationTextField.css"/>
<script src="/SpryAssets/SpryValidationTextField.js"></script>
</head>
<body><?PHP require_once('navigation.php');?>
<h1>Sales Tax Owed</h1>
<form action="salestax.php" method="get">
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

<?php echo $buffer;?>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("startDate", "date", {format:"mm-dd-yyyy", hint:"mm-dd-yyyy", validateOn:["blur", "change"], useCharacterMasking:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("endDate", "date", {format:"mm-dd-yyyy", hint:"mm-dd-yyyy", validateOn:["blur", "change"], useCharacterMasking:true});
</script>

</body>
</html>