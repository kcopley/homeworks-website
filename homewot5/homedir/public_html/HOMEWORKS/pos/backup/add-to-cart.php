<?php
require_once('dbconnect.php');
$stamp='';$shipping=0.00;
if(isset($_SESSION['stamp']))
{$stamp=$_SESSION['stamp'];

}
else{$d=date('mdY');
	$t=time();
	$stamp=$d.$t.rand(9, 9);
	$_SESSION['stamp']=$stamp; 
	}
	$quantity=1;
	$pid='';
	if(isset($_POST['quantity'])){$quantity=$_POST['quantity'];}
	if(isset($_GET['pid'])){$pid=$_GET['pid'];}
	$user_id=$stamp;
	$product_id='';
	$sale_price=''; 
	$product_name='';
	$sale_price='';
		$result = mysql_query("SELECT id,product_name,price FROM cms_module_products where id='$pid'");
		while($row = mysql_fetch_array($result))
		{
		$product_id=$row['id'];
		$sale_price=$row['price'];
		$product_name=$row['product_name'];
		$tax=$sale_price*0.0865;
		
		}
		
		
	mysql_query	("INSERT INTO temp_sale (product_id, product_name, sale_price, tax, shipping, quantity, user_id  ) 
	VALUES ('$product_id','$product_name','$sale_price','$tax','$shipping','$quantity','$user_id' )");
	header( 'Location: /index.php?page=cart' ) ;
?>