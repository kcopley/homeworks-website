<?php
require_once('security.php');
require_once('dbconnect.php');

$result = mysql_query("SELECT * FROM customers");

$buffer='';
	while($row = mysql_fetch_array($result))
	{
		$customer_id=$row['customer_id'];
		$customer_name=$row['customer_name'];
		$buffer.='<option value="'.$customer_id.'" onChange="setCust(this.id, \''.$customer_name.'\')">'.$customer_name.'</option>';
	}
mysql_close($con);	
?>
<script>
var custName='<?php echo $customer_name;?>';
var custId='<?php echo $customer_id;?>';
function setCust(id, name){
	custName=name;
	custId=id;
	}
function finishParent(){
	//update DB
	window.parent.document.getElementById('customer_id').value=custId;
	window.parent.document.getElementById('custname').innerHTML=custName;
	window.parent.document.getElementById('popUp').style.display='none';
	}
</script>
<table border="1" cellpadding="2">
  <tr>
    <td>Choose Account</td>
    <td><select id="custacct"><?php echo $buffer;?></select> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="button" value="Done" onClick="finishParent();" ></td>
  </tr>
</table>

