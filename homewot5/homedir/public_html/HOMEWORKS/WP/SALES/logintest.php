<?php
require_once('dbconnect.php');
if(!isset($_SESSION['login'])){ 
	session_start(); }
$password='';
$username='';$passwordtest=NULL;
if(isset($_POST['password'])){
	//error_log("Password was read in.", 0);
	$password=md5($_POST['password']);
	//error_log("Password == $password", 0);
}
else {
	//error_log("Password was not grabbed.", 0);
	header( 'Location: login.php?msg=Please Enter A Password');
}
if(isset($_POST['username'])){
	//error_log("Username was read in.", 0);
	$username=$_POST['username'];
	//error_log("Username== $username", 0);
}
else {
	//error_log("Username was not grabbed.", 0);
	header( 'Location: login.php?msg=Please Enter A Username');
}
$result = mysql_query("SELECT * FROM cms_users where username='$username'");
	while($row = mysql_fetch_array($result))
	{
		$passwordtest=$row['password'];
		//error_log("===Password == $passwordtest", 0);
		//$passwordtest=md5($passwordtest);
		//error_log("===Password == $passwordtest", 0);
	}
	if($passwordtest!=NULL && $passwordtest==$password){
		//error_log("$passwordtest", 0);
		//error_log("$password", 0);
		$_SESSION['login']=true;
		if(isset($_SESSION['login'])){
			if($_SESSION['login']==true){
				//error_log("Session login set to true.", 0);
				header( 'Location: index.php');
			}
			else {
				header( 'Location: login.php' ) ;
				//error_log("Session login was NOT true in security file.", 0);
			}
		}
		//error_log("Session login set to true.", 0);
		//header( 'Location: index.php') ;
	}
	else {
		//error_log("Login was supposed to be incorrect.", 0);
		header( 'Location: login.php?msg=Password-Username Missmatch') ;
	}
?>