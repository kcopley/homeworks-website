<?php
session_start();
require_once('dbconnect.php');

	$stamp='';
	$shipping=0.00;
	//error_log("--Shipping.", 0);
	if(isset($_SESSION['stamp'])) {
		$stamp=$_SESSION['stamp'];
		//error_log("Assigning session stamp to variable..", 0);
	} else {
		$d=date('mdY');
		$t=time();
		$stamp=$d.$t.rand(9, 9);
		$_SESSION['stamp']=$stamp; 
		//error_log("Setting new session stamp.", 0);
		//error_log("Stamp: $stamp.", 0);
	}

	$quantity=1;
	$pid='';
	//error_log("--Quantity.", 0);
	if(isset($_POST['quantity'])) {
		$quantity=$_POST['quantity'];
		//error_log("Assigning quantity variable.", 0);
	}
	if(isset($_GET['pid'])) {
		$pid=$_GET['pid'];
		//error_log("Assigning product ID variable.", 0);
	}
	
	$user_id=$stamp;
	$product_id='';
	$sale_price=''; 
	$product_name='';
	$sale_price='';
	$result = mysql_query("SELECT id,product_name,price FROM cms_module_products where id='$pid'");
	//error_log("Initializing variables and getting result query: $result", 0);
	
	while($row = mysql_fetch_array($result))
	{
		$product_id=$row['id'];
		//error_log("Product ID: $product_id.", 0);
		$sale_price=$row['price'];
		//error_log("Sale Price: $sale_price", 0);
		$product_name=$row['product_name'];
		//error_log("Product Name: $product_name", 0);
		$tax=$sale_price*0.0865;
		//error_log("Tax: $tax", 0);
	}
	//error_log("Passed loop w/ result.", 0);
		
	mysql_query	("INSERT INTO temp_sale (product_id, product_name, sale_price, tax, shipping, quantity, user_id  ) 
	VALUES ('$product_id','$product_name','$sale_price','$tax','$shipping','$quantity','$user_id' )");
	
	//error_log("Insertion into temporary sale.", 0);
	//error_log(ini_get('session.name'), 0);
	//error_log(session_id(), 0);
	//error_log(ini_get('session.cookie_domain'), 0);
	session_write_close(); 
	header( 'Location: /index.php?page=cart' ) ;
?>