<?php
require_once('security.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Barcodes</title>
<style>
body{ margin:0px; padding:0px;}
td{ border:1px solid #ccc;
height:95px; font-size:11px}
.hide{ display:hidden;}
.floatLeft{ width:45%; float:left; padding-left:10px;}
.floatRight{ width:45%; float:right}
.clear{ clear:both; margin:0px; padding:0px; line-height:0px; font-size:0px; height:0px;}
</style>
<style media="print">
td{ border:none }
.noPrint{ display:none}
</style>
<script type="text/javascript" src="update-post.js" language="javascript"></script>
</head>
<body><br />
 <br />

<table width="100%"  cellspacing="0" cellpadding="3">
  <tr>
    <td width="33%" id="1"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('1',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td> 
    <td width="33%" id="2">
    <span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('2',this.value)"  />&nbsp;    </td>
    <td width="0%" >&nbsp;</td>
    <td width="33%" id="3"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('3',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="4"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('4',this.value)"  />&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="33%" id="5"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('5',this.value)"  />&nbsp;</td>
    <td width="0%">&nbsp;</td>
    <td width="33%" id="6"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('6',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="7"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('7',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="33%" id="8"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('8',this.value)"  />&nbsp;</td>
    <td width="0%">&nbsp;</td>
    <td width="33%" id="9"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('9',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="10"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('10',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="33%" id="11"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('11',this.value)"  />&nbsp;</td>
    <td width="0%" >&nbsp;</td>
    <td width="33%" id="12"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('12',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="13"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('13',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="33%" id="14"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('14',this.value)"  />&nbsp;</td>
    <td width="0%" >&nbsp;</td>
    <td width="33%" id="15"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('15',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="16"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('16',this.value)"  />&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="33%" id="17"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('17',this.value)"  />&nbsp;</td>
    <td width="0%">&nbsp;</td>
    <td width="33%" id="18"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('18',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="19"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('19',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="33%" id="20"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('20',this.value)"  />&nbsp;</td>
    <td width="0%">&nbsp;</td>
    <td width="33%" id="21"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('21',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="22"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('22',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="33%" id="23"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('23',this.value)"  />&nbsp;</td>
    <td width="0%" >&nbsp;</td>
    <td width="33%" id="24"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('24',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="25"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('25',this.value)"  />&nbsp;</td>
    <td width="1%" >&nbsp;</td>
    <td width="33%" id="26"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('26',this.value)"  />&nbsp;</td>
    <td width="0%" >&nbsp;</td>
    <td width="33%" id="27"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('27',this.value)"  />&nbsp;</td>
  </tr>
  <tr>
    <td width="33%" id="28"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('28',this.value)"  />&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="33%" id="29"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('29',this.value)"  />&nbsp;</td>
    <td width="0%" >&nbsp;</td>
    <td width="33%" id="30"><span class="noPrint">ID: </span><input type="text" class="noPrint" value="" onblur="updatePost('30',this.value)"  />&nbsp;</td>
  </tr>
</table>
</body>

</html>
