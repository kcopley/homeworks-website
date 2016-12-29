<?php
require_once('security.php');
require_once('dbconnect.php');
$url="https://webservices.primerchants.com/creditcard.asmx/CCSale";
$MerchantID="61740";$RegKey="MB9Y59YD45HYKHH3";$Amount=$_POST['Amount'];
$TrackData=$_POST['TrackData'];$SaleTaxAmount=$_POST['SaleTaxAmount'];
$PONumber=$_POST['PONumber'];$REFID=$_POST['REFID'];$fields_string='';$stamp=$_POST['stamp'];
$CardNumber=$_POST['CardNumber'];$Expiration=$_POST['Expiration'];
$CardHolderName=$_POST['CardHolderName']; $CVV2=$_POST['CVV2']; ;
$TaxIndicator="1";$Address=" ";$ZipCode=" ";$PaymentDesc=" ";$UserID=" ";
$MerchZip=" ";$MerchCustPNum=" ";$MCC=" ";$InstallmentOf=" ";$InstallmentNum=" ";
$POSInd=" ";$POSConditionCode=" ";$EComInd=" ";$AuthCharInd=" ";$CardCertData=" ";$CAVVData=" ";

$fields=array(
			'MerchantID'=>$MerchantID,
            'RegKey'=>$RegKey,
			'Amount'=>$Amount,
			'TrackData'=>urlencode($TrackData),
			'SaleTaxAmount'=>$SaleTaxAmount,
			'PONumber'=>$PONumber,
			'RefID'=>$REFID,
			'CardNumber'=>$CardNumber,
			'Expiration'=>$Expiration,
			'CardHolderName'=>$CardHolderName,
			'CVV2'=>$CVV2,
			'TaxIndicator'=>$TaxIndicator,
			'Address'=>$Address,
			'ZipCode'=>$ZipCode,
			'PaymentDesc'=>$PaymentDesc,
			'UserID'=>$UserID,
			'MerchZip'=>$MerchZip,
			'MerchCustPNum'=>$MerchCustPNum,
			'MCC'=>$MCC,
			'InstallmentOf'=>$InstallmentOf,
			'InstallmentNum'=>$InstallmentNum,
			'POSInd'=>$POSInd,
			'POSConditionCode'=>$POSConditionCode,
			'EComInd'=>$EComInd,
			'AuthCharInd'=>$AuthCharInd,
			'CardCertData'=>$CardCertData,
			'CAVVData'=>$CAVVData
);

foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//execute post
$result = curl_exec($ch);
$status=substr($result,strpos($result,'<Status>')+8,strpos($result,'</Status>')-8- strpos($result,'<Status>'));
if(strpos($result,'<Message>')>0){ $message=substr($result,strpos($result,'<Message>')+9,strpos($result,'</Message>')-9- strpos($result,'<Message>'));}
$trans=substr($result,strpos($result,'<TransID>')+9,strpos($result,'</TransID>')-9- strpos($result,'<TransID>'));
echo "<H1>".$status." - ".$message."</h1>";
if(curl_errno($ch))
{
    echo 'Curl error: ' . curl_error($ch);
}
//close connection
curl_close($ch);
function getQuant($tempBal){
$qua=0;
$result = mysql_query("SELECT value FROM cms_module_products where id='$tempBal'");
	while($row = mysql_fetch_array($result))
	{
	$qua=$row['quantity'];
	}
	return $qua;
}

if($status=='Apporved'){
$buffer='<table cellpadding="2" cellspacing="0" border="1"><tr><th>Name</th><th>Price</th><th>Quantity</th><th>Tax</th> <th>Total</th></tr>
';$cancel="<a href='cancel.php?stamp=".$stamp."' class='cancel'>CANCEL</a>";
	$name=''; $price=0; $quantity=0; $tax=0; $total=0;$grandTotal=0;
	$result = mysql_query("SELECT * FROM temp_sale where user_id='$stamp'");
	while($row = mysql_fetch_array($result))
	{
	
	$product_id=$row['product_id'];$mday=date('Y-m-d');$oldQuant=getQuant($product_id);
	$name=$row['product_name']; $price=number_format($row['sale_price'], 2, '.', ','); $quantity=$row['quantity']; 
	$tax=number_format(($price*$quantity)*$rate, 2, '.', ',');
	$total=number_format(($price*$quantity)+$tax, 2, '.', ',');
	$buffer.='<tr><td>'.$name.'</td><td>$'.$price.'</td><td>'.$quantity.'</td><td>$'.$tax.'</td><td>$'.$total.'</td></tr>';
	$grandTotal=number_format($grandTotal+$total, 2, '.', ',');
	mysql_query("INSERT INTO Sales_Purchase (product_id, price, quantity, tax, date, pors) VALUES ('$product_id', '$price', '$quantity', '$tax', '$mday','Sale')");
	$newQuant=$oldQuant-$quant;
	mysql_query("UPDATE cms_module_products SET quantity = '$newQuant' WHERE id = '$product_id'");
	}
	$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Grand Total</th> <th>$'.$grandTotal.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th> <th><input type="hidden" value="'.$stamp.'"><input type="submit" value="Finish"></th></tr></table>';


mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp'");



}

mysql_close($con);
?>
<?PHP if($status=='Apporved'){?>
<link rel="stylesheet" type="text/css" href="main.css">
<table border="1" cellpadding="2" cellspacing="0">
  <tr>
    <td>LOGO</td>
    <td><?php echo $date('m-d-Y');?></td>
  </tr>
  <tr>
    <td>TRANSACTION</td>
    <td><?php echo $trans;?></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $buffer;?></td>
  </tr>
</table>

<br />
<br />
<br />



<hr />
Customer Signature<br />
<br />
<br />
<br />
<table border="1" cellpadding="2" cellspacing="0">
  <tr>
    <td>LOGO</td>
    <td><?php echo $date('m-d-Y');?></td>
  </tr>
  <tr>
    <td>TRANSACTION</td>
    <td><?php echo $trans;?></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo $buffer;?></td>
  </tr>
</table>

<br />
<br />
<br />



<hr />
Customer Signature
<?PHP }?>
<DIV style="page-break-after:always"></DIV>