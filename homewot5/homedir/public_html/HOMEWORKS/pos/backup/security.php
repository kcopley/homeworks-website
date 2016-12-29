<?php
if(isset($_SESSION['login'])){
	if($_SESSION['login']==true){
	//do nothing
	}
	else{
		header( 'Location: login.php' ) ;
		}
	}
else{
	header( 'Location: login.php' ) ;
}
?>