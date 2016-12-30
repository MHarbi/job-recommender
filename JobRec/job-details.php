<?php

include("model/session.php");
$logged = true;

if(!isset($_SESSION['user']))
{
    $logged = false;
    header("location: index.php");
}

if(isset($_GET['jobkey']) and !empty($_GET['jobkey']))
{
    $jobkey = $_GET['jobkey'];
}
else
{
    exit;
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender - Job Details</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="controller/jquery-1.11.1.min.js"></script>
<script src="controller/job-query.js"></script>
<script>fetch_job_details("<?php echo $jobkey; ?>");</script>
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
    <h4><strong></strong></h4>
    <div class="w3-row">
        <div id="job-details" class="tabcontent">
            <div class="w3-container w3-right">
                <span id="apply-button"></span> 
                <span id="bookmark-button"></span>
            </div>
            <h3 id="job-title">Job Details: </h3>
            <div id="details">
                <span id="company"><b>Company: </b><br></span>
                <br>
                <span id="location"><b>Location: </b><br></span>
                <br>
                <span id="categories"><b>Job Categories: </b><br></span>
                <br>
                <span id="job-desc"><b>Job Description: </b><br></span>
                <br>
                <span id="job-reqs"><b>Job Requirements: </b><br></span>
                <br>
                <span id="degree"><b>Degree Required: </b><br></span>
                <br>
                <span id="experience"><b>Experience Required: </b><br></span>
                <br>
                <span id="emp-type"><b>Employment Type: </b><br></span>
                <br>
                <span id="pay"><b>Pay: </b><br></span>
                <br>
                <span id="post-date"><b>Date Posted: </b><br></span>
                <br>
            </div>
        </div>
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