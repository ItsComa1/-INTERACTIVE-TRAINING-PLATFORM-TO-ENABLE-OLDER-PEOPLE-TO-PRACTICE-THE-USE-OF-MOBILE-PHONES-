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

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px">


  <!-- The Band Section -->
  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="band">
    <h2 class="w3-wide">WHAT'S THE SITE FOR?</h2>
    <p class="w3-opacity"><i>Helping those in need</i></p>
    <p class="w3-justify">Elderly people tend to be forgotten about when it comes to technology, and we tend to forget how hard it is for them to navigate and learn new things and there is one area in particular which I think could be improved and that’s mobile phones. Older people want to be able to communicate with relatives but it’s not always possible for them to meet in person, so technology needs to be used and mobile phones are usually the go to. Older people tend to struggle with mobile phones and usually have to keep asking people how to use them which can frustrate whoever their asking and cause them to be put off with using a mobile phone. So I think an application which older people can use to learn the basics would be much better as they don’t need to keep asking and they would be guided through what to do which is much better as actually doing a task over and over again causes you to learn more. </p>
    <div class="w3-row w3-padding-32">
      <div class="w3-third">
        <img src="Old1.jpeg" class="w3-round w3-margin-bottom" alt="Random Name" style="width:80%">
      </div>
      <div class="w3-third">
        <img src="Old2.jpg" class="w3-round w3-margin-bottom" alt="Random Name" style="width:80%">
      </div>
      <div class="w3-third">
        <img src="Old3.jpg" class="w3-round" alt="Random Name" style="width:80%">
      </div>
    </div>
  </div>  
  
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
