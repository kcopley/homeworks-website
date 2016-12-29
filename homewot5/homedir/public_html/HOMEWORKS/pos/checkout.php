<?php
session_start();
function just_clean($string)
{
// Replace other special chars
$specialCharacters = array(
'#' => '',
'$' => '',
'%' => '',
'&' => '',
'€' => '',
'-' => '',
'+' => '',
'=' => '',
'§' => '',
'/' => '',
'\'' => '',
'"' => '',
);
while (list($character, $replacement) = each($specialCharacters)) {
$string = str_replace($character, '-' . $replacement . '-', $string);
}
$string = strtr($string,
"ÀÁÂÃÄÅ? áâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
);
 
// Remove all remaining other unknown characters
$string = preg_replace('/[^a-zA-Z0-9-]/', ' ', $string);
$string = preg_replace('/^[-]+/', '', $string);
$string = preg_replace('/[-]+$/', '', $string);
$string = preg_replace('/[-]{2,}/', ' ', $string);
 
return $string;
}
$stamp='';
$rate=0.0865;
if(isset($_POST['state_s'])){
	if($_POST['state_s']=='KS'||$_POST['state_s']=='ks'||$_POST['state_s']=='Ks'||$_POST['state_s']=='kS'){}
	else{
		$rate=0;
		}
	}
require_once('dbconnect.php');
if(isset($_SESSION['stamp'])){$stamp=$_SESSION['stamp'];}else{header('Location: ../index.php?page=cart');}

function getQuant($tempBal){
$qua=0;
$result = mysql_query("SELECT quantity FROM cms_module_products where id='$tempBal'");
	while($row = mysql_fetch_array($result))
	{
	$qua=$row['quantity'];
	}
	return $qua;
}
//product info

	$buffer='<table cellpadding="2" cellspacing="0" border="1"><tr><th>Name</th><th>Price</th><th>Quantity</th><th>Tax</th> <th>Total</th></tr>
';$cancel="<a href='cancel.php?stamp=".$stamp."' class='cancel'>CANCEL</a>";
	$name=''; $price=0; $quantity=0; $tax=0; $total=0;$grandTotal=0;$shipping=0;
	$result2 = mysql_query("SELECT * FROM temp_sale where user_id='$stamp'");
	while($row = mysql_fetch_array($result2))
	{
	$name=$row['product_name']; $price=number_format($row['sale_price'], 2, '.', ','); $quantity=$row['quantity']; 
	$tax=number_format(($price*$quantity)*$rate, 2, '.', ',');
	$shipping=0;
	$total=number_format(($price*$quantity)+$tax, 2, '.', ',');
	$buffer.='<tr><td>'.$name.'</td><td>$'.$price.'</td><td>'.$quantity.'</td><td>$'.$tax.'</td><td>$'.$total.'</td></tr>';
	$grandTotal=number_format($grandTotal+$total, 2, '.', ',');
	}
	$grandTotal=number_format($grandTotal+$shipping, 2, '.', ',');
	$shipping=number_format($shipping, 2, '.', ',');
	$buffer.='<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Shipping Total</th> <th>$'.$shipping.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Grand Total</th> <th>$'.$grandTotal.'</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th> <th><input type="hidden" value="'.$stamp.'"></th></tr></table>';



//shippment info
$address_s=just_clean($_POST['address_s']);$first_name_s=just_clean($_POST['first_name_s']);$city_s=just_clean($_POST['city_s']);$zip_s=just_clean($_POST['zip_s']);
if( isset($_POST['address2_s'])){$address2_s=just_clean($_POST['address2_s']);}
$last_name_s=just_clean($_POST['last_name_s']);$phone=just_clean($_POST['phone']);$email=just_clean($_POST['email']);$state_s=just_clean($_POST['state_s']);


//cc info
$first_name=just_clean($_POST['first_name']);
$last_name=just_clean($_POST['last_name']);
$address=just_clean($_POST['address']);
if( isset($_POST['address2'])){$address2=just_clean($_POST['address2']);}
$city=just_clean($_POST['city']);$state=just_clean($_POST['state']);
$cc_number=just_clean($_POST['cc_number']);
$exp=just_clean($_POST['month']);
$cvv=just_clean($_POST['cvv']);
//process cc**********************************
$url="https://webservices.primerchants.com/creditcard.asmx/CCSale";
$MerchantID="61740";$RegKey="MB9Y59YD45HYKHH3";$Amount=$grandTotal;
$TrackData=' ';$SaleTaxAmount=$grandTotal;
$PONumber=' ';$REFID=$stamp;$fields_string='';
$CardNumber=$_POST['cc_number'];$Expiration=$exp;
$CardHolderName=$first_name.' '.$last_name; $CVV2=$_POST['cvv']; ;
$TaxIndicator="1";$Address=$_POST['address'].$address2;$ZipCode=$_POST['zip'];$PaymentDesc=" ";$UserID=" ";
$MerchZip=" ";$MerchCustPNum=" ";$MCC=" ";$InstallmentOf=" ";$InstallmentNum=" ";
$POSInd=" ";$POSConditionCode=" ";$EComInd=" ";$AuthCharInd=" ";$CardCertData=" ";$CAVVData=" ";

$fields=array(
			'MerchantID'=>$MerchantID,
            'RegKey'=>$RegKey,
			'Amount'=>$Amount,
			'TrackData'=>$TrackData,
			'SaleTaxAmount'=>'0',
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
//echo $fields_string;
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
if(curl_errno($ch))
{
    //echo 'Curl error: ' . curl_error($ch);
}
else{
	
	}
	if($status=='Authorized'){
		 
//email julie homeworksforbooks@sbcglobal.net
$to = "homeworksforbooks@sbcglobal.net";
$subject = "Order Confirmation From Homeworksforbooks.com";
$from='info@homeworksforbooks.com';
$random_hash = md5(date('r', time())); 
$headers = "From: $from\r\nReply-To: $to";
$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\""; 
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
</head><body><h1>Order Confirmation</h1><table border="1" cellpadding="2">
  <tr valign="top">
    <td>Ship To:</td>
    <td>'.$first_name_s.' '.$last_name_s.' <br>
'.$address_s.'<br>'.$address2_s.'<br>
'.$city_s.', '.$state_s.'  '.$zip_s.'<br>
<br>
 </td>
  </tr>
</table>
<h2>Items:'.$buffer.'</h2></body></html>';
$mydomain = "homeworksforbooks.com";
$titlesubject = $subject;
$addtionalBody=$body;
$addtionalBody.='<br><br>Email:'.$email.'<br>';
$addtionalBody.='Phone:'.$phone.'<br>';
$addtionalBody.='Transaction:'.$stamp.'<br>';
$messagebody = $mymessage;
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=us-ascii' . "\r\n";
$headers .= 'From: noreply@'. $mydomain . "\r\n";
$headers .= 'Reply-To: noreply@'. $mydomain . "\r\n";
$headers .= '1\r\nX-MSMail-Priority: High' . "\r\n";



mail($email, $titlesubject, $body, $headers);

mail($to, $titlesubject, $addtionalBody, $headers);




//mail($to, $subject, $body, $headers);
//add Email Phone Sales Tax Shipping	
//email user
//mail($email, $subject, $body, $headers);
//update tables
	$result3 = mysql_query("SELECT * FROM temp_sale where user_id='$stamp'");
while($row3 = mysql_fetch_array($result3))
	{
	$product_id=$row3['product_id'];$mday=date('Y-m-d');$oldQuant=getQuant($product_id);
	$name=$row3['product_name']; $price=number_format($row3['sale_price'], 2, '.', ','); $quantity=$row3['quantity']; 
	$tax=number_format(($price*$quantity)*$rate, 2, '.', ',');
	$total=number_format(($price*$quantity)+$tax, 2, '.', ',');
	$grandTotal=number_format($grandTotal+$total, 2, '.', ',');
	mysql_query("INSERT INTO Sales_Purchase (product_id, price, quantity, tax, date, pors) VALUES ('$product_id', '$price', '$quantity', '$tax', '$mday','Sale')");
	
	$newQuant=$oldQuant-$quant;
	mysql_query("UPDATE cms_module_products SET quantity = '$newQuant' WHERE id = '$product_id'");

	}
mysql_query("DELETE FROM temp_sale WHERE user_id='$stamp'");


//display confirm
echo $body;
echo '<div align="center"> <h2><a href="/index.php">Return Home</a></h2></div>';
}
		else{
			echo'<h1>There was an error processing your card, Please go back and validate the information provided</h1>';
			}
mysql_close($con);

?>
