<?PHP
require_once('dbconnect.php');
$date=date('Y-m-d');
$pors='Purchase';
$status='published';
$product_name='';
$quantity='';
$expense='';
if(isset($_GET['expense'])){$expense=$_GET['expense'];$pors='Expense';}
$price='';
function checkSku($test){
	$ret='';
	$result = mysql_query("SELECT id FROM cms_module_products where sku='$test'");
	while($row = mysql_fetch_array($result))
	{
		$ret=$row['id'];
	}
	return $ret;
}
function checkAlias($test){
	$ret='';
	$result = mysql_query("SELECT id FROM cms_module_products where alias='$test'");
	while($row = mysql_fetch_array($result))
	{
		$ret=$row['id'];
	}
	return $ret;
}
function getQuanty($test){
	$ret='';
	$result = mysql_query("SELECT quantity FROM cms_module_products where id='$test'");
	while($row = mysql_fetch_array($result))
	{
		$ret=$row['quantity'];
	}
	return $ret;
}
//add conditionals for sku and alias
$cost=='';$cost='';$sku='';$alias=='';$condition='';$msrp='';$vendor='';
if(isset($_POST['product_name'])){$product_name= check_input($_POST['product_name']);}//products
if(isset($_POST['quantity'])){$quantity= check_input($_POST['quantity']);}//7
if(isset($_POST['price'])){$price= check_input($_POST['price']);}//products
if(isset($_POST['cost'])){$cost= check_input($_POST['cost']);}//1
if(isset($_POST['sku'])){$sku= check_input($_POST['sku']);}//products
if(isset($_POST['alias'])){$alias= check_input($_POST['alias']);}//products
if(isset($_POST['condition'])){$condition= check_input($_POST['condition']);}//5
if(isset($_POST['msrp'])){$msrp= check_input($_POST['msrp']);}//3
if(isset($_POST['vendor'])){$vendor= check_input($_POST['vendor']);}//6

if(checkAlias($alias)!=''){
	$idval=checkAlias($alias);
		$newquant=$quantity+getQuanty($idval);
	$product_id=$idval;
	mysql_query("UPDATE cms_module_products SET quantity = '$newquant', price='$price', cost='$cost' WHERE id = '$idval' ");
}
elseif(checkSku($sku)!=''){
	$idval=checkSku($sku);
	$newquant=$quantity+getQuanty($idval);
	$product_id=$idval;
	echo  checkSku($sku). 'TEST2'; 
	mysql_query("UPDATE cms_module_products SET quantity = '$newquant', price='$price', cost='$cost' WHERE id = '$idval' ");
	}
else{
mysql_query("INSERT INTO cms_module_products (product_name, price, sku, alias, cost, quantity, details,msrp, vendor ) VALUES ('$product_name', '$price', '$sku','$alias', '$cost','$quantity','$condition','$msrp','$vendor')");
echo  "INSERT INTO cms_module_products (product_name, price, sku, alias, cost, quantity, details,msrp, vendor ) VALUES ('$product_name', '$price', '$sku','$alias', '$cost','$quantity','$condition','$msrp','$vendor')"; 
$product_id=mysql_insert_id ();
}
mysql_query("INSERT INTO Sales_Purchase (product_id, price, quantity, date, pors) VALUES ('$product_id', '$price', '$quantity','$date','$pors')");

mysql_close($con);
?>
<h1>Purchase Updated</h1>
<p><a href="index.php">Return Home</a></p>
