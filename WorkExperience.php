<?php
	//Check if user logged in by checking session status
	session_start();
	if(isset($_SESSION['login_user'])){
?>




<!DOCTYPE html>
<html lang="en">
<head>
<title>W3.CSS Template</title>
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
  <div class="Title"><center><h3>INTERACTIVE TRAINING PLATFORM FOR MOBILE PHONES</h3></center></div>
    <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="HTML5template.php" class="w3-bar-item w3-button w3-padding-large">HOME</a>
    <a href="Education.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">TRAINING</a>
    <a href="WorkExperience.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">TUTORIAL</a>
    <a href="Hobbies.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">COURSES</a>
	<a href="Contact.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small">CONTACT</a>
	<a href="logout.php" style="float:right" class="w3-bar-item w3-button w3-padding-large w3-hide-small">LOGOUT</a>
  </div>
</div>


<!-- Navbar on small screens (remove the onclick attribute if you want the navbar to always show on top of the content when clicking on the links) -->
<div id="navDemo" class="w3-bar-block w3-black w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
  <a href="About.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">ABOUT</a>
  <a href="register.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">SIGNUP</a>
  <a href="Login.php" class="w3-bar-item w3-button w3-padding-large" onclick="myFunction()">SIGNIN</a>
</div>


<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">


		<audio autoplay>
			<source src="welcome4.mp3" type="audio/mpeg">

		</audio>

  
  
<!-- End Page Content -->
</div>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity w3-light-grey w3-xlarge">
  <i class="fa fa-facebook-official w3-hover-opacity"></i>
  <i class="fa fa-instagram w3-hover-opacity"></i>
  <i class="fa fa-snapchat w3-hover-opacity"></i>
  <i class="fa fa-pinterest-p w3-hover-opacity"></i>
  <i class="fa fa-twitter w3-hover-opacity"></i>
  <i class="fa fa-linkedin w3-hover-opacity"></i>
</footer>


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

		
	<?php } else{
		header('location: login.php');
	} ?>
	




	
