<?php

include("model/login.php");
$logged = false;

if(isset($_SESSION['user']))
{
    $logged = true;
    // header("location: dashboard.php");
}

$homePage = true;
$key = '';
if(isset($_POST['search-keys']) && !empty($_POST['search-keys']))
{
    $key       = $_POST['search-keys'];
    $homePage  = false;
    $radius    = '';
    $perPage   = 100;
    $location  = '';

    if($logged)
    {
        $radius    = 100;
        $location = $_SESSION['location'];
    
        // default importance weights
        require_once('model/sim.php');
        $weights = getWeights();

        $onet      = $weights['onet']*100;
        $loc       = $weights['loc']*100;
        $edu       = $weights['edu']*100;
        $job_title = $weights['job_title']*100;
        $emp_type  = $weights['emp_type']*100;

        $weights = array('onet' => $onet,
                    'loc'       => $loc,
                    'edu'       => $edu,
                    'job_title' => $job_title,
                    'emp_type'  => $emp_type);
    }
    
    require_once('model/job-query.php');
    $data = search($key, $radius, $perPage, null, $location, $logged);
    // echo '<pre>';
    // var_dump($data);
    // echo '</pre>';
}

if(isset($_POST['filter-submit']) && $logged)
{
    $key       = $_POST['key'];
    $homePage  = false;
    $radius    = $_POST['radius'];
    $perPage   = 100;
    // default importance weights
    require_once('model/sim.php');
    $total_weights = $_POST['onet']+$_POST['loc']+$_POST['edu']+$_POST['job_title']+$_POST['emp_type'];

    $onet      = $_POST['onet'];
    $loc       = $_POST['loc'];
    $edu       = $_POST['edu'];
    $job_title = $_POST['job_title'];
    $emp_type  = $_POST['emp_type'];

    $location  = '';
    $location = $_SESSION['location'];


    $weights = array('onet' => $onet/$total_weights,
                'loc'       => $loc/$total_weights,
                'edu'       => $edu/$total_weights,
                'job_title' => $job_title/$total_weights,
                'emp_type'  => $emp_type/$total_weights);
    
    require_once('model/job-query.php');
    $data = search($key, $radius, $perPage, $weights, $location, $logged);
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender</title>
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

<?php if(!$homePage && $logged) { ?>

<!-- Sidenav/menu -->
  <nav class="w3-sidenav w3-light-grey w3-collapse w3-top" style="z-index:3;width:260px;padding-top: 45px" id="mySidenav">
    <div class="w3-container w3-padding-8">
        <div class="w3-row w3-light-grey">
            <div class="w3-col w3-padding-xxlarge">
            </div>
        </div>
      <hr>
      <h5><strong> Search Filters</strong></h5>
      <hr>

      <form action="" method="post">
        <!-- 5, 10, 20, 30, 50, 100, or 150 -->
        <ul class="w3-ul w3-border">
            <li><h6><i class="fa fa-location-arrow" aria-hidden="true"></i> Distance</h6></li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="5" <?php if($radius == 5) echo 'checked'; ?>>
                <label class="w3-validate">5 miles</label>
            </li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="10" <?php if($radius == 10) echo 'checked'; ?>>
                <label class="w3-validate">10 miles</label>
            </li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="20" <?php if($radius == 20) echo 'checked'; ?>>
                <label class="w3-validate">20 miles</label>
            </li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="30" <?php if($radius == 30) echo 'checked'; ?>>
                <label class="w3-validate">30 miles</label>
            </li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="50" <?php if($radius == 50) echo 'checked'; ?>>
                <label class="w3-validate">50 miles</label>
            </li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="100" <?php if($radius == 100) echo 'checked'; ?>>
                 <label class="w3-validate">100 miles</label>
            </li>
            <li>
                <input class="w3-radio w3-margin-0" type="radio" name="radius" value="150" <?php if($radius == 150) echo 'checked'; ?>>
                <label class="w3-validate">150 miles</label>
            </li>
        </ul>
        <hr>
         <h5><strong><i class="fa fa-list-ol" aria-hidden="true"></i> Importance</strong></h5>
         <hr>
         <p><label><i class="fa fa-location-arrow" aria-hidden="true"></i> Location</label></p>
         <input
            type="text"
            name="loc"
            data-provide="slider"
            data-slider-min="1"
            data-slider-max="100"
            data-slider-step="1"
            data-slider-value="<?php echo $loc; ?>"
            data-slider-tooltip="show"
            precision="2"
         >
         <hr style="margin:0; margin-top: 16px">
         <p><label><i class="fa fa-circle-thin" aria-hidden="true"></i> Job Domain</label></p>
         <input
            type="text"
            name="onet"
            data-provide="slider"
            data-slider-min="1"
            data-slider-max="100"
            data-slider-step="1"
            data-slider-value="<?php echo $onet; ?>"
            data-slider-tooltip="show"
            precision="2"
         >
         <hr style="margin:0; margin-top: 16px">
         <p><label><i class="fa fa-circle-thin" aria-hidden="true"></i> Required Education</label></p>
         <input
            type="text"
            name="edu"
            data-provide="slider"
            data-slider-min="1"
            data-slider-max="100"
            data-slider-step="1"
            data-slider-value="<?php echo $edu; ?>"
            data-slider-tooltip="show"
            precision="2"
         >
         <hr style="margin:0; margin-top: 16px">
         <p><label><i class="fa fa-circle-thin" aria-hidden="true"></i> Job Title</label></p>
         <input
            type="text"
            name="job_title"
            data-provide="slider"
            data-slider-min="1"
            data-slider-max="100"
            data-slider-step="1"
            data-slider-value="<?php echo $job_title; ?>"
            data-slider-tooltip="show"
            precision="2"
         >
         <hr style="margin:0; margin-top: 16px">
         <p><label><i class="fa fa-circle-thin" aria-hidden="true"></i> Employment Type</label></p>
         <input
            type="text"
            name="emp_type"
            data-provide="slider"
            data-slider-min="1"
            data-slider-max="100"
            data-slider-step="1"
            data-slider-value="<?php echo $emp_type; ?>"
            data-slider-tooltip="show"
            precision="2"
         >
         <hr>
         <input type="hidden" name="key" value="<?php echo $key; ?>">
        <p><button name="filter-submit" class="w3-btn-block w3-green w3-padding w3-left-align" type="submit"><i class="fa fa-search w3-margin-right"></i> Update </button></p>
      </form>
  </div>
  
  <a href="javascript:void(0)" class="w3-padding" onclick="document.getElementById(\'subscribe\').style.display=\'block\'"><i class="fa fa-rss"></i> Subscribe</a>
  <a href="#contact" class="w3-padding-16"><i class="fa fa-envelope"></i> Contact</a>
</nav>

  <!-- !PAGE CONTENT! -->
  <div class="w3-main w3-white" style="margin-left:260px">
<?php } else { ?>
    <!-- !PAGE CONTENT! -->
    <div class="w3-main w3-white" style="">
<?php } ?>

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:80px"></div>


  <div class="w3-container">
    <form id="registe-form" role="form" action="" method="post">
        <div class="w3-row w3-light-grey">
            <div class="w3-col w3-padding-xxlarge">
                <div class="w3-row w3-large" style="margin: auto; width: 700px;">
                    <div class="w3-col">
                        <input id="search-input" class="w3-input w3-border w3-twothird" type="search" name="search-keys" size="34" placeholder="      search by job title or skills" style="padding-bottom: 6px;" required>
                        <button id="search-button" class="w3-btn w3-green w3-padding w3-left-align" type="submit">
                            <i class="fa fa-search w3-margin-right"></i> Find Jobs
                        </button>
                    </div>
                </div>
            </div> 
        </div>
    </form>
    <?php if(!$homePage) { ?>
    <hr>
    <h4><strong><?php echo ucwords($key); ?> Jobs <span class="w3-large w3-right"><span class="w3-badge"><?php echo count($data['ResponseJobSearch']['Results']['JobSearchResult']); ?></span> job(s) returned</span></strong></h4>
    
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
          <?php if($logged) { ?>
          <div class="w3-col l1">
              <button class="w3-btn w3-light-gray" data-toggle="popover" title="Explanation" data-html="true" data-placement="left" data-content="<b>Location:</b> <?php echo round($value['locSim']*100, 2) . '%'; ?><br/>
              <b>Job Domain:</b> <?php echo round($value['jobSocSim']*100, 2) . '%'; ?><br/>
              <b>Required Education:</b> <?php echo round($value['eduSim']*100, 2) . '%'; ?><br/>
              <b>Job Title:</b> <?php echo round($value['jobTitleSim']*100, 2) . '%'; ?><br/>
              <b>Employment Type:</b> <?php echo round($value['empSim']*100, 2) . '%'; ?>"><?php echo round($value['rankScore']*100, 2) . '%'; ?></button>
          </div>
          <?php } ?>
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
    
    <?php } else { ?>
    <div class="w3-row">
        <div class="w3-col">
          <div class="w3-container w3-center">
          <hr>
              <img src="theme/newJob.jpg" class="w3-round w3-opacity-min w3-card-4" alt="">
          </div>
        </div>
    </div>
    <?php } ?>
  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>
<?php if(!$homePage) { ?>
<link href="theme/bootstrap-slider.css" rel="stylesheet">
<script src="controller/bootstrap.min.js"></script>
<script type="text/javascript" src="controller/modernizr.js"></script>
<script type="text/javascript" src="controller/bootstrap-slider.js"></script>
<style type="text/css">
    input.slider .slider-selection {
    background: #BABABA;
}
</style>
<script>
    $(function () {
    $('[data-toggle="popover"]').popover()
  })
</script>
<?php } ?>

</body>
</html>
