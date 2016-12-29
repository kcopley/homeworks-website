<?php
require_once('security.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISSUE REFUND</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body><?PHP require_once('navigation.php');?>
<h1>REFUND/VOID</h1>
<blockquote>
  <form method="post" action="void.php">
    <table><tr><Td>Transaction ID: </Td><td><span id="sprytextfield1">
      <label for="transaction"></label>
      <input type="text" name="transaction" id="transaction" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td></tr>
      <tr>
        <Td>&nbsp;</Td>
        <td><input type="submit" /></td>
      </tr>
    </table>
  </form>
</blockquote>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
</body>
</html>