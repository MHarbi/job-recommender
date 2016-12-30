<?php

include("model/login.php");
$logged = false;

if(isset($_SESSION['user']))
{
    $logged = true;
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender - Log In</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="controller/jquery-1.11.1.min.js"></script>
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Raleway", Arial, Helvetica, sans-serif}
.mySlides {display:none}
</style>
<body class="w3-border-left w3-border-right" style="padding-top: 80px">

<?php include('nav.php'); ?>


<!-- !PAGE CONTENT! -->
<div class="w3-main w3-white" style="">

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:80px"></div>


  <div class="w3-container ">
  <div class="w3-row">
    <div class="w3-card-4" style="margin: auto;width: 300px;">
      <header class="w3-container w3-text-black w3-light-gray">
        <h4><strong>Log In</strong></h4>
      </header>
      <div class="w3-container">
        <form class="w3-form" action="" method="POST">
            <label>Email:</label><br>
            <input class="w3-input" type="text" name="username" size="34" placeholder=" Enter Your Email" required><br>
            <label>Password:</label><br>
            <input class="w3-input" type="password" name="password" size="34" placeholder=" Enter Your Password" required><br>
            <br>
            <input class="w3-btn w3-green w3-padding w3-margin-right w3-third" id="login-button" name="submit" type="submit" value="Login">
            <input class="w3-check" type="checkbox" checked="checked"> remember me<br>
        </form>
        <br>
        <span class="forgot-password">Forgot your <a href="">password</a>?</span><br>
        <br>
        <span class="register-today">Need a JobRec account? <br><a href="register.php">Register Today!</a></span>
        <br>
        <br>
        <span><?php echo $error; ?></span>
        </div>
      </div>
    </div>
    <hr />
  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script>

</script>

</body>
</html>
