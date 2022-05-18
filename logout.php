<?php

session_start();
//if signout button pressed
if(isset($_SESSION['login_user']))
{
	
	//end session thats creatted when user logs in
	session_destroy();
	
	//send user to login page
	header("location:Login.php");	
		
}
		
?>