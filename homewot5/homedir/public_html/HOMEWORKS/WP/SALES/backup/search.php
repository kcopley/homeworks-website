<?php

require_once('dbconnect.php');
$product_name='xsfda';
$isbn='xx;';
$buffer='';
if(isset($_POST['product_name'])){$product_name=$_POST['product_name'];}
if(isset($_POST['isbn'])){$isbn=$_POST['isbn'];}
$result = mysql_query("SELECT *  FROM cms_module_products where sku='$isbn' OR alias=$isbn OR product_name='$product_name' AND quantity >0");
		while($row = mysql_fetch_array($result))
		{ 
			$pid=$row['id'];
			$product_name=$row['product_name']; 
			$isbn=$row['sku'].$row['alias'];
			$price=number_format($row['price'],2);
			$buffer.='<tr><td><strong>image-id-'.$id.'</strong></td><td>'.$product_name.'</td><td>'.$isbn.'</td><td>'.$price.'</td><td><a href="/pos/add-to-cart.php?pid='.$pid.'">add to cart</a></td></tr>';
		}
echo '<table cellpadding="2" border="1">';
echo $buffer;
echo '</table>';

?>
