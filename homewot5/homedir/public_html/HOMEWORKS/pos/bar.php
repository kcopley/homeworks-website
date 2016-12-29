<?php
require_once('dbconnect.php');
$id=$_POST['id'];
$result = mysql_query("SELECT * FROM cms_module_products where id='$id'");
	while($row = mysql_fetch_array($result))
	{
	$product_name=$row['product_name'];
	$price=$row['price'];
	$msrp=$row['msrp'];
	}
	
	echo '<div class="floatLeft"><br />
<strong>Title:</strong> '.$product_name.'<br />
    <strong>MSRP:</strong> $'.$msrp.'<br />
    <strong>PRICE:</strong> $'.$price.'<br />
    </div> 
      <div class="floatRight" ><br />
<img src="/barcodegen/bar128.php?upc='.$id.'"  width="100"></div><div class="clear"></div>';
?>
