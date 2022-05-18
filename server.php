<?php 
	//All this php is for register.php file

	//connect database
	$db = mysqli_connect('192.168.1.18', 'admin', 'the_secure_password', 'usersignup');
	
	//signup button
	if(isset($_POST['register'])){

		$username = mysqli_real_escape_string($db,$_POST['username']);
		$email = mysqli_real_escape_string($db,$_POST['email']);
		$phone = mysqli_real_escape_string($db,$_POST['phone']);
		$password = mysqli_real_escape_string($db,$_POST['password']);
		$password2 = mysqli_real_escape_string($db,$_POST['password2']);
		
		//checks if Password fields filled in
		if (empty($password) || empty($password2)){
			$message = "Passwords not filled in";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if Username field filled in
		if (empty($username)){
			$message = "Username not filled in";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if Username field atleast 2 characters
		if (strlen($username) < 2){
			$message = "Username must be atleast 2 characters";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if Password field atleast 8 characters
		if (strlen($password) < 8){
			$message = "Password must be atleast 8 characters";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if email field filled in
		if (empty($email)){
			$message = "Email not filled in";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		// check if e-mail address is well-formed
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$message = "Email incorrect format";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if phone field filled in
		if (empty($phone)){
			$message = "Phone not filled in";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		if(!preg_match("/^[0-9]{3}[0-9]{2}[0-9]{3}[0-9]{4}$/", $phone)) {
			// $phone is invalid			
			$message = "Phone is invalid";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if passwords match
		if ($password != $password2) {
			$message = "Passwords do not match";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		//checks if email is in database
		$query = mysqli_query($db, "SELECT * FROM `users` WHERE `email` = '".$_POST['email']."'") or exit(mysqli_error($db));

		if (mysqli_num_rows($query) > 0)
		{
			$message = "Email is Already In Use";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}		
		
		//checks if phone is in database
		$query = mysqli_query($db, "SELECT * FROM `users` WHERE `phone` = '+".$_POST['phone']."'") or exit(mysqli_error($db));

		if (mysqli_num_rows($query) > 0)
		{
			$message = "Phone is Already In Use";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}
		
		
		//checks if username is in database
		$query = mysqli_query($db, "SELECT * FROM `users` WHERE `username` = '".$_POST['username']."'") or exit(mysqli_error($db));

		if (mysqli_num_rows($query) > 0)
		{
			$message = "Username is Already In Use";
			echo "<script type='text/javascript'>alert('$message');</script>";
			echo "<script>location.href='register.php'</script>";
			exit();
		}	
		


		
	
		if ($password == $password2) {
			

			
			//Generatte key for email verification
			$vkey = md5(time().$username);
			
			$password = base64_encode($password); //encrypt password md5
			$sql = "INSERT INTO users (username, email, password, phone)
						VALUES ('$username', '$email', '$password', '+$phone')";
						
			if($sql) {
			
				mysqli_query($db, $sql);
				$_SESSION['message'] = "logged in";
				$_SESSION['username'] = $username;
				
				echo "<script>location.href='Login.php'</script>";
				
				//require_once('vender/HTML5template.php');
				

			
			}
		}
		else{
			
			$_SESSION['message'] = "Password mismatch";
				
		}
		
	}
			
?>		
		

		
	
	
	
	
