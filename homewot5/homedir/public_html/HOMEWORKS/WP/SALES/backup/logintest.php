<?php
require_once('dbconnect.php');
$password='';
$username='';$passwordtest=NULL;
if(isset($_POST['password'])){$password=$_POST['password'];}
else{header( 'Location: login.php?msg=Please Enter A Password') ;}
if(isset($_POST['username'])){$username=$_POST['username'];}
else{header( 'Location: login.php?msg=Please Enter A Username');}
$result = mysql_query("SELECT * FROM cms_users where username='$username'");
	while($row = mysql_fetch_array($result))
	{
		$passwordtest=md5($row['password']);
	}
	if($passwordtest!=NULL && $passwordtest=$password){
		$_SESSION['login']=true;
		header( 'Location: index.php') ;
		}
	else{
		header( 'Location: login.php?msg=Password-Username Missmatch') ;
		}
?>