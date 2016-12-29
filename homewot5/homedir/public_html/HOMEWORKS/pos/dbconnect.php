<?php
$con = mysql_connect("localhost","homewot5","Ping!!Pong42");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("homewot5_cms", $con);

function check_input($value)
{
// Stripslashes
if (get_magic_quotes_gpc())
  {
  $value = stripslashes($value);
  }
// Quote if not a number
if (!is_numeric($value))
  {
  $value =mysql_real_escape_string($value);
  }
return $value;
}


?>