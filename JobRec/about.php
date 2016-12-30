<?php

include("model/login.php");
$logged = false;

if(isset($_SESSION['user']))
{
    $logged = true;
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender - About</title>
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


  <div class="w3-container" style="margin: auto; width: 75%">
    <h4><strong>About Us</strong></h4>
    <div class="w3-row w3-large">
        <p>Welcome to the job recommendation board! This application is intended to assist you on your job search by tracking your occupational interests as you bookmark and apply for jobs. We consider the job title, job domain, location, educational qualification and employment type of the jobs in which you demonstrate interest to provide you with recommendations as new jobs are posted. We also use this information to re-rank jobs as you search using keywords. Make sure to bookmark and apply for jobs so that we can help you get one step closer to finding your dream job!</p>
    </div>
  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script>

</script>

</body>
</html>
