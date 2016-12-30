<?php

include("model/login.php");

$logged = true;
if(!isset($_SESSION['user']))
{
    $logged = false;
    header("location: index.php");
}

$radius = 100;
$perPage = 100;

if($logged)
{
    $location = $_SESSION['location'];
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender - Recommendions</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="theme/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="controller/jquery-1.11.1.min.js"></script>
<script src="controller/job-query.js"></script>
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Raleway", Arial, Helvetica, sans-serif}
a {text-decoration: none;}
</style>
<body class="w3-border-left w3-border-right" style="padding-top: 80px">

<?php include('nav.php'); ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main w3-white" style="">

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:80px"></div>

  <?php 
      require_once('model/job-query.php');
      $data = search('', $radius, $perPage, null, $location, $logged); 
  ?>


  <div class="w3-container" style="margin: auto; width: 80%">
    <h4><strong>Recommendions</strong></h4>

    <?php foreach ($data['ResponseJobSearch']['Results']['JobSearchResult'] as $value) { ?>
      <style type="text/css">
        .job-row:hover {background: #f5f5f5;}
        .job-row {padding-bottom: 5px; padding-left: 5px;}
      </style>
      
      <div class="w3-row job-row">
        <hr>
        <div class="w3-row">
          <div class="w3-col w3-text-blue l11">
            <h5 class="job-title"><strong>
              <a href="job-details.php?jobkey=<?php echo $value['DID']; ?>" target="_blank"><?php echo $value['JobTitle']; ?></a>
            </strong></h5>
          </div>
          <div class="w3-col l1">
            <button class="w3-btn w3-light-gray" data-toggle="popover" title="Explanation" data-html="true" data-placement="left" data-content="<b>Location:</b> <?php echo round($value['locSim']*100, 2) . '%'; ?><br/>
              <b>Job Domain:</b> <?php echo round($value['jobSocSim']*100, 2) . '%'; ?><br/>
              <b>Required Education:</b> <?php echo round($value['eduSim']*100, 2) . '%'; ?><br/>
              <b>Job Title:</b> <?php echo round($value['jobTitleSim']*100, 2) . '%'; ?><br/>
              <b>Employment Type:</b> <?php echo round($value['empSim']*100, 2) . '%'; ?>"><?php echo round($value['rankScore']*100, 2) . '%'; ?></button>
          </div>
        </div>
        <div class="w3-row job-information">
          <div class="w3-col l8">
            <p class="w3-small employment-info" style="margin-top: 0;"><strong>
              <?php echo $value['EmploymentType']; ?> | Pay: <?php echo $value['Pay']; ?>
            </strong></p>
            <div class="job-description w3-small">
              <?php echo $value['DescriptionTeaser']; ?>
            </div>
          </div>
          <div class="w3-col l3 w3-center  w3-text-blue">
            <p class="w3-small"><strong>
              <a href="http://www.careerbuilder.com/company/monroe-staffing-services/<?php echo $value['CompanyDID']; ?>"><?php echo $value['Company']; ?></a>
            </strong></p>
          </div>
          <div class="w3-col l1">
              <p><strong><?php echo $value['City'] . ', ' . $value['State']; ?></strong></p>
          </div>
        </div>
        <div class="w3-row user-action w3-text-blue w3-small">
          <div class="w3-col l2">
            <span id="btn-bk-<?php echo $value['DID']; ?>" class="<?php if($value['bookmarked'] == 1) echo 'w3-hide'; ?> save-job">
              <a href="javascript:void(0)" onclick="user_interest('bookmark', '<?php echo $value['DID']; ?>')">
                <span class="fa fa-star-o"></span>
                Bookmark
              </a>
            </span>
            <span id="btn-unbk-<?php echo $value['DID']; ?>" class="<?php if($value['bookmarked'] == 0) echo 'w3-hide'; ?> saved-job" js-id="">
              <a class="saved-job-text" href="javascript:void(0)" onclick="user_interest('unbookmark', '<?php echo $value['DID']; ?>')">
                <span class="fa fa-star"></span>
                Bookmarked Job
              </a>
            </span>
          </div>
          <div class="w3-col l1">
            <span id="btn-app-<?php echo $value['DID']; ?>" class="<?php if($value['applied'] == 1) echo 'w3-hide'; ?> save-job">
              <a href="javascript:void(0)" onclick="user_interest('apply', '<?php echo $value['DID']; ?>')">
                <span class="fa fa-bookmark-o"></span>
                Apply Now
              </a>
            </span>
            <span id="btn-unapp-<?php echo $value['DID']; ?>" class="<?php if($value['applied'] == 0) echo 'w3-hide'; ?> saved-job" js-id="">
              <a class="saved-job-text" href="javascript:void(0)" onclick="user_interest('unapply', '<?php echo $value['DID']; ?>')">
                <span class="fa fa-bookmark"></span>
                Applied Job
              </a>
            </span>
          </div>
        </div>
      </div>


    <?php } ?>

  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script src="controller/bootstrap.min.js"></script>
<script>
  $(function () {
    $('[data-toggle="popover"]').popover()
  })
</script>

</body>
</html>
