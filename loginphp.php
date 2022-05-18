<?php 



	//connect database
	$db = mysqli_connect('192.168.1.18', 'admin', 'the_secure_password', 'usersignup');

	//if signup button pressed
	if(isset($_POST['Login'])){
		
		
		
		//get values from login.php 
		$username = mysqli_real_escape_string($db,$_POST['username']);
		$password = mysqli_real_escape_string($db,$_POST['password']);		

		
		//prevent mysql injection
		$username = stripcslashes($username); //Clear back slashes
		$username = strip_tags($username); //Clear tags etc <h1></h1>
		$password = stripcslashes($password); //Clear back slashes
		$username = mysqli_real_escape_string($db,$username);
		$password = mysqli_real_escape_string($db,$password);		
		
		

		
			
		//checks if Password fields filled in
		if (empty($password)){
			$message = "Password not filled in";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='Login.php'</script>";
			exit();
		}
		
		//puts encryption in when user types password
		$password = base64_encode($password);
			
		//checks if Username field filled in
		if (empty($username)){
			$message = "Username not filled in";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='Login.php'</script>";
			exit();
		}
		
		
		
		
		
		//checks if username is in database
		$query = mysqli_query($db, "SELECT * FROM `users` WHERE `username` = '$username' and `password` = '$password'") or exit(mysqli_error($db));

		$row = mysqli_fetch_array($query);
		
		$user = mysqli_fetch_array($row);
		
		//if username and password entered match whats in database you may log in
		if ($row['username'] == $username && $row['password'] == $password ) 
		{
			
				$escapedPW = mysqli_real_escape_string($db,$_REQUEST['password']);
				
				//if remember is ticked remember user
				if( isset($_REQUEST['remember']) ) 
				{
					$escapedRemember = mysqli_real_escape_string($db,$_REQUEST['remember']);
					
					$cookie_time = 60 * 60 * 24 * 30; // 30 days
					
					$cookie_time_Onset = $cookie_time+ time();
				}
				
				//user information to remember
				if(isset($escapedRemember))
				{
					
					setcookie('username', $username, $cookie_time_Onset);
					
					$password = base64_decode($password);
					
					setcookie('password', $password, $cookie_time_Onset);
						
				}
				
				//if they dont tick remember clear cookie values for username and password
				else
				{
					
					$cookie_time_fromoffset=time() -$cookie_time;
					setcookie("username", '',$cookie_time_fromOffset);
					setcookie("password", '',$cookie_time_fromOffset);
					
				}
		
				//Starts user session when user logs in
				session_start();
				$_SESSION['username'] = $username;
				
				//initialise session for user
				$_SESSION['login_user'] = $username;	
				$message = ('Hello ' . $_SESSION['login_user']);
				echo "<script type='text/javascript'>alert('$message');</script>";
				echo "<script>location.href='WorkExperience.php'</script>";
			
		}
		
		//if username and password entered don't match whats in database you display error message and redirect them to login page
		else
		{
			$message = ('Invalid user');
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='Login.php'</script>";
			exit();
		}
		

		

		
	}
					
?>		
		

		
	
	
	
	
