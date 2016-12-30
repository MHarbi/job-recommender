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
<title>Job Recommender - Bookmarks</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="controller/jquery-1.11.1.min.js"></script>
<script src="controller/job-query.js"></script>
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Raleway", Arial, Helvetica, sans-serif}
.mySlides {display:none}
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
      $data = display_bookmarks(); 
  ?>


  <div class="w3-container" style="margin: auto; width: 80%">
    <h4><strong>Bookmarks</strong></h4>
    <?php if($data === 0) { ?>
      <hr>
      <div class="w3-row job-row">
        <div class="w3-row">
          <div class="w3-col w3-text-deep-orange">
            <h6 class="job-title"><strong>
              You have not bookmarked any jobs yet. Search for jobs or browse our recommendations.
            </strong></h6>
          </div>
        </div>
      </div>

    <?php } else { ?>

    <?php foreach ($data as $value) { 

        $location = str_replace('US-', '', $value['loc_formatted']);
        $state = substr($location, 0, 2);
        $city = substr($location, 3);
        $location = $city.','.$state;

      ?>
      <hr>
      <div class="w3-row job-row">
        <div class="w3-row">
          <div class="w3-col w3-text-blue">
            <h5 class="job-title"><strong>
              <a href="job-details.php?jobkey=<?php echo $value['job_key']; ?>" target="_blank"><?php echo $value['job_title']; ?></a>
            </strong></h5>
          </div>
        </div>
        <div class="w3-row job-information">
          <div class="w3-col l8">
            <p class="w3-small employment-info" style="margin-top: 0;"><strong>
              <?php echo $value['employment_type']; ?>
            </strong></p>
            <div class="job-description w3-small">
              <?php echo substr(strip_tags($value['job_desc']),0,300) . "..."; ?>
            </div>
          </div>
          <div class="w3-col l3 w3-center  w3-text-blue">
            <p class="w3-small"><strong>
              <?php echo $value['company']; ?>
            </strong></p>
          </div>
          <div class="w3-col l1">
              <p><strong><?php echo $city . ', ' . $state; ?></strong></p>
          </div>
        </div>
        <div class="w3-row user-action w3-text-blue w3-small">
          <div class="w3-col l2">
            <span id="btn-bk-<?php echo $value['job_key']; ?>" class="<?php if($value['bookmarked'] == 1) echo 'w3-hide'; ?> save-job">
              <a href="javascript:void(0)" onclick="user_interest('bookmark', '<?php echo $value['job_key']; ?>')">
                <span class="fa fa-star-o"></span>
                Bookmark
              </a>
            </span>
            <span id="btn-unbk-<?php echo $value['job_key']; ?>" class="<?php if($value['bookmarked'] == 0) echo 'w3-hide'; ?> saved-job" js-id="">
              <a class="saved-job-text" href="javascript:void(0)" onclick="user_interest('unbookmark', '<?php echo $value['job_key']; ?>')">
                <span class="fa fa-star"></span>
                Bookmarked Job
              </a>
            </span>
          </div>
          <div class="w3-col l1">
            <span id="btn-app-<?php echo $value['job_key']; ?>" class="<?php if($value['applied'] == 1) echo 'w3-hide'; ?> save-job">
              <a href="javascript:void(0)" onclick="user_interest('apply', '<?php echo $value['job_key']; ?>')">
                <span class="fa fa-bookmark-o"></span>
                Apply Now
              </a>
            </span>
            <span id="btn-unapp-<?php echo $value['job_key']; ?>" class="<?php if($value['applied'] == 0) echo 'w3-hide'; ?> saved-job" js-id="">
              <a class="saved-job-text" href="javascript:void(0)" onclick="user_interest('unapply', '<?php echo $value['job_key']; ?>')">
                <span class="fa fa-bookmark"></span>
                Applied Job
              </a>
            </span>
          </div>
        </div>
      </div>


    <?php } }?>

  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script>

</script>

</body>
</html>
