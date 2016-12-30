<?php

include("model/login.php");

$logged = true;
if(!isset($_SESSION['user']))
{
    $logged = false;
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender - Profile</title>
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

  <?php require_once('model/job-query.php'); ?>

<?php
      include("model/db-connect.php");
      $db = Db::getInstance();
      $res = $db->query("SELECT * FROM user_profile WHERE email LIKE '" . $_SESSION['user'] . "'");
      $row = $res->fetch();

      $location = str_replace('US-', '', $row['preferred_location']);
      $state = substr($location, 0, 2);
      $city = substr($location, 3);
      $location = $city.', '.$state;
      $res1=$db->query("SELECT job_title FROM job_case, user_interest where user_interest.email='{$row['email']}' and 
user_interest.job_id=job_case.job_id and job_case.case_type='Onet'");
      $res2=$db->query("SELECT job_title FROM job_case, user_interest where user_interest.email='{$row['email']}' and 
user_interest.job_id=job_case.job_id and job_case.case_type='Resume'");
      $res3=$db->query("SELECT job_title FROM job_case, user_interest where user_interest.email='{$row['email']}' and 
user_interest.job_id=job_case.job_id and job_case.case_type='Resume'");

      
?>

  <div class="w3-container" style="margin: auto; width: 75%">
    <h4><strong>Profile</strong></h4>
    <div class="w3-row w3-large">
      <div class="w3-col s3">
        <p>First Name:</p>
        <p>Last Name:</p>
        <p>Email:</p>
        <p>Prefered Location:</p>
        <p>Job Interests 1:</p>
        <p>Job Interests 2:</p>
        <?php
          $count=0;
          while (true) {
            $row2=$res2->fetch();
            if ($row2!=null) {
              $count++;
              echo "<p>Job Experience ".$count.": </p>";
            }else{
              break;
            }
          }
        ?>
      </div>
      <div class="w3-col s6">
        <p><?php echo $row['first_name']; ?></p>
        <p><?php echo $row['last_name']; ?></p>
        <p><?php echo $row['email']; ?></p>
        <p><?php echo $location; ?>  ,&nbsp;United States</p>
        <?php 
                  $i=0;
                  while ( $i<= 1) {
                    $row1 = $res1->fetch();
                    echo "<p>".$row1[0]."<p/>";
                    $i++;
                  }
        ?>
        <?php
          $count=0;
          while (true) {
            $row3=$res3->fetch();
            if ($row3 != null) {
              $count++;
              echo "<p>".$row3[0]."</p>";
            } else {
              break;
            }
          }
        ?>
      </div>
    </div>
    <form>
        <a class="w3-btn w3-green w3-padding w3-margin-right w3-third" href="edit-profile.php">Edit Profile</a>
    </form>
  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script>

</script>

</body>
</html>
