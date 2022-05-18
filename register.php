<?php include('server.php'); ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Signup</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {font-family: "Lato", sans-serif}
.mySlides {display: none}
</style>
</head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-black w3-card">
    <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="Home.php" class="w3-bar-item w3-button w3-padding-large">HOME</a>
    <a href="About.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">ABOUT</a>
    <a href="register.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">SIGNUP</a>
    <a href="Login.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">SIGNIN</a>
  </div>
</div>


<!-- Navbar on small screens (remove the onclick attribute if you want the navbar to always show on top of the content when clicking on the links) -->
<div id="navDemo" class="w3-bar-block w3-black w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
  <a href="About.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">ABOUT</a>
  <a href="register.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">SIGNUP</a>
  <a href="Login.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">SIGNIN</a>
</div>

<center>
<form action="server.php" method="post">
</br>
<div class="header"><<h2>SIGNUP FOR MOBILE TRAINING</h2></div>

	
  <div class="imgcontainer">
    <img src="Login.jpg" height="400" width="400" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
	</br>
    <input type="text" name="username">
	</br></br>

    <label for="uid"><b>Email</b></label>
	</br>
    <input type="text" name="email">
	</br></br>
	
    <label for="uid"><b>Phone</b></label>
	</br>
    <input type="text" name="phone">
	</br></br>
        
	<label for="psw"><b>Password</b></label>
	</br>
    <input type="password" name="password">
	</br></br>
	
	<label for="psw"><b>Confirm password</b></label>
	</br>
    <input type="password" name="password2">
	</br></br>
		
    <button type="submit" name="register">Register</button>
	
	<p> Already Signed up? <a href="Login.php">Sign in</a>
	

</form>
</center>


<script>
// Automatic Slideshow - change image every 4 seconds
var myIndex = 0;
carousel();

function carousel() {
  var i;
  var x = document.getElementsByClassName("mySlides");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  myIndex++;
  if (myIndex > x.length) {myIndex = 1}    
  x[myIndex-1].style.display = "block";  
  setTimeout(carousel, 4000);    
}

// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}

// When the user clicks anywhere outside of the modal, close it
var modal = document.getElementById('ticketModal');
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>


</body>
</html>
