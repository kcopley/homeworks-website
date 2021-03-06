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
<body><?PHP require_once('navigation.php');?><h1>Purchases</h1> 
<form action="update-po.php" method="post">
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
    <td><strong>Department</strong></td>
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
    <td><strong>Price</strong></td>
    <td><span id="sprytextfield5">
      <input type="text" name="price" id="price" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>MRSP</strong></td>
    <td><span id="sprytextfield6">
      <input type="text" name="msrp" id="msrp" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Vendor</strong></td>
    <td><span id="sprytextfield7">
      <input type="text" name="vendor" id="vendor" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Condtion</strong></td>
    <td><span id="sprytextfield8">
      <input type="text" name="condition" id="condition" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Sku</strong></td>
    <td><span id="sprytextfield9">
      <input type="text" name="sku" id="sku" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
  <tr>
    <td><strong>Alt Sku</strong></td>
    <td><span id="sprytextfield10">
      <input type="text" name="alias" id="alias" />
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
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
//-->
</script>
</body>
</html>
