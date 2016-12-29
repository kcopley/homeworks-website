<?php
echo $_SESSION['login'];
$msg='';
if(isset($_GET['msg'])){$msg='<h2>'.$_GET['msg'].'</h2>';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
</head>
<body>
<h1>Log In</h1><?php echo $msg;?>
<form method="post" action="logintest.php">
<table width="265" border="1" cellpadding="2">
  <tr>
    <td width="97"><strong>User Name</strong></td>
    <td width="148"><input type="text" name="username" value="" /></td>
  </tr>
  <tr>
    <td><strong>Password</strong></td>
    <td><input type="password" name="password" value="" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><input type="submit" /></td>
  </tr>
</table>
</form>
</body>
</html>