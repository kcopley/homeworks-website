<?php
session_start();
if(isset($_SESSION['login'])){
	if($_SESSION['login']==true){
	//do nothing
	}
	else{
		header( 'Location: login.php' ) ;
		//error_log("Session login was NOT true in security file.", 0);
		}
	}
else{
	//error_log("Session login was NOT even set in security file.", 0);
	header( 'Location: login.php' ) ;
}
?>