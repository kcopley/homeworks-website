<?php
require_once('security.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Purchases</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Expenses</h1> 
<form action="update-po.php?expense=true" method="post">
<table  border="1" cellspacing="0" cellpadding="2">
  <tr>
    <td width="28%"><strong>Name</strong></td>
    <td width="72%"><span id="sprytextfield1">
      <input type="text" name="product_name" id="name" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Quantity</strong></td>
    <td><span id="sprytextfield2">
      <input type="text" name="quantity" id="quantity" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Expense Type</strong></td>
    <td><span id="sprytextfield3">
      <input type="text" name="details" id="details" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Cost</strong></td>
    <td><span id="sprytextfield4">
      <input type="text" name="cost" id="cost" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Vendor</strong></td>
    <td><span id="sprytextfield7">
      <input type="text" name="vendor" id="vendor" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr align="right">
    <td>&nbsp;</td>
    <td><input type="submit" /></td>
  </tr>
</table>

</form>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
//-->
</script>
</body>
</html>
