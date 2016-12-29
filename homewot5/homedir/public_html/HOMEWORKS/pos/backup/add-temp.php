<?php
require_once('security.php');
$stamp='';$rate=0.0865;$_SESSION['error']=''; 
if(isset($_POST['stamp'])){$stamp=$_POST['stamp'];}else{header('Location: sales.php?error=true');}
if(isset($_POST['tax'])&&$_POST['tax']>=0){$rate=$_POST['tax']/100; $_SESSION['rate']=$rate;};
$_SESSION['tax']=$rate;
if($_POST['isbn']!=''||$_POST['isnb2']!=''||$_POST['id']!='') 
{
	$isbn='';$val='';
	if(isset($_POST['id']) && $_POST['id']!=''){$isbn=$_POST['id']; $val='id';}
	if(isset($_POST['isbn']) && $_POST['isbn']!=''){$isbn=$_POST['isbn']; $val='sku';}
	if(isset($_POST['isbn2']) && $_POST['isbn2']!=''){$isbn=$_POST['isbn2']; $val='alias';}
	require_once('dbconnect.php');
	$quantity=1;
	if(isset($_POST['quantity'])){$quantity=$_POST['quantity'];}
	$user_id=$stamp;
	$product_id='';
	$sale_price='';
	$product_name='';
	$sale_price='';
		$result = mysql_query("SELECT id,product_name,price FROM cms_module_products where ".$val."='$isbn'");
		while($row = mysql_fetch_array($result))
		{
		$product_id=$row['id'];
		$sale_price=$row['price'];
		$product_name=$row['product_name'];
		}
		$tax=$sale_price*$rate;
		if($product_id!=''&&$product_id!=NULL){
			$_SESSION['error']='';
	mysql_query	("INSERT INTO temp_sale (product_id, product_name, sale_price, tax, quantity, user_id  ) 
	VALUES ('$product_id','$product_name','$sale_price','$tax','$quantity','$user_id' )");
	$lastId=mysql_insert_id();
		}
		else{
			$_SESSION['error']='Book Not Found!';
			}
	mysql_close($con);	
	
	
	header("Location: sales.php?stamp=$user_id");
}
else
{
	
	if($_POST['isCredit']=='true'){
		require_once('dbconnect.php');
		$creditAmount=$_POST['credit']*(-1);
		mysql_query	("INSERT INTO temp_sale (product_id, product_name, sale_price, tax, quantity, user_id  ) 
	VALUES ('999999999','Credit','$creditAmount','0','1','$stamp' )");
	mysql_close($con);	
	header("Location: sales.php?stamp=$stamp");
		}
	else{
		$_SESSION['error']='Please Enter a Value!';
	header('Location: sales.php');
	}
}


?>