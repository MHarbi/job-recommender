<?php
	ob_start();
	// session_start();

	if(isset($_SESSION['user'])!="" ){
		header("Location: index.php");
	}
	include_once 'model/db-connect.php';
	$db = Db::getInstance();

	$result = $db->query("SELECT soc_code, job_title FROM onet WHERE soc_group LIKE 'major'");
	foreach ($result->fetchAll() as $row) { 
		$majorList[] = $row;
	}
	if ( isset($_POST['btn-signup']) ) {
		
		
	}
?>
<?php

include("model/login.php");
$logged = false;

if(isset($_SESSION['user']))
{
    $logged = true;
    //header("location: dashboard.php");
}

?>
<!DOCTYPE html>
<html>
<title>Job Recommender - Sign Up</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="theme/bootstrap.min.css" type="text/css" />
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


  <div class="w3-container">
  
<div class="stepwizard col-md-offset-3">
    <div class="stepwizard-row setup-panel">
      <div class="stepwizard-step">
        <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
        <p>User Profile</p>
      </div>
      <div class="stepwizard-step">
        <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
        <p>Prefered Location</p>
      </div>
      <div class="stepwizard-step">
        <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
        <p>Job Interests</p>
      </div>
      <div class="stepwizard-step">
        <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
        <p>Experiences</p>
      </div>
      <div class="stepwizard-step">
        <a id="a-step-5" href="#step-5" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
        <p>Complete </p>
      </div>
    </div>
  </div>
  
  <form id="register-form" role="form" action="model/register-action.php" method="post" novalidate>
    <div class="row setup-content" id="step-1">
      <div class="col-xs-6 col-md-offset-3">
        <div class="col-md-12">
          <h3> User Profile</h3>
          <div class="form-group">
            <label class="control-label">First Name</label>
            <input  maxlength="100" type="text" name="first_name" required="required" class="form-control" placeholder="Enter First Name"  />
          </div>
          <div class="form-group">
            <label class="control-label">Last Name</label>
            <input maxlength="100" type="text" name="last_name" required="required" class="form-control" placeholder="Enter Last Name" />
          </div>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input maxlength="40" type="email" name="email" required="required" class="form-control" placeholder="Enter Your Email" />
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input maxlength="40" type="password" id="pass" name="pass" required="required" class="form-control" placeholder="Enter Your Password" />
          </div>
          <div class="form-group">
            <label class="control-label">Password Confirmation</label>
            <input maxlength="40" type="password" id="repass" name="repass" required="required" class="form-control" placeholder="Re-Enter Your Password" />
          </div>
          <button class="w3-btn w3-blue w3-padding w3-quarter nextBtn pull-right" type="button" >Next</button>
        </div>
      </div>
    </div>
    <div class="row setup-content" id="step-2">
      <div class="col-xs-6 col-md-offset-3">
        <div class="col-md-12">
          <h3> Prefered Location</h3>
          <div class="form-group">
            <label class="control-label">Country</label>
            <p class="form-control-static mb-0">United States</p>
            <input maxlength="200" type="hidden" name="country" class="form-control" required="required" value="United States" placeholder="United States" maxlength="40" />
          </div>
          <div class="form-group">
            <label class="control-label">State</label>
            <select name="state" required="required" class="form-control">
        <option value="AL">Alabama</option>
        <option value="AK">Alaska</option>
        <option value="AZ">Arizona</option>
        <option value="AR">Arkansas</option>
        <option value="CA">California</option>
        <option value="CO">Colorado</option>
        <option value="CT">Connecticut</option>
        <option value="DE">Delaware</option>
        <option value="DC">District Of Columbia</option>
        <option value="FL">Florida</option>
        <option value="GA">Georgia</option>
        <option value="HI">Hawaii</option>
        <option value="ID">Idaho</option>
        <option value="IL">Illinois</option>
        <option value="IN">Indiana</option>
        <option value="IA">Iowa</option>
        <option value="KS">Kansas</option>
        <option value="KY">Kentucky</option>
        <option value="LA">Louisiana</option>
        <option value="ME">Maine</option>
        <option value="MD">Maryland</option>
        <option value="MA">Massachusetts</option>
        <option value="MI">Michigan</option>
        <option value="MN">Minnesota</option>
        <option value="MS">Mississippi</option>
        <option value="MO">Missouri</option>
        <option value="MT">Montana</option>
        <option value="NE">Nebraska</option>
        <option value="NV">Nevada</option>
        <option value="NH">New Hampshire</option>
        <option value="NJ">New Jersey</option>
        <option value="NM">New Mexico</option>
        <option value="NY">New York</option>
        <option value="NC">North Carolina</option>
        <option value="ND">North Dakota</option>
        <option value="OH">Ohio</option>
        <option value="OK">Oklahoma</option>
        <option value="OR">Oregon</option>
        <option value="PA">Pennsylvania</option>
        <option value="RI">Rhode Island</option>
        <option value="SC">South Carolina</option>
        <option value="SD">South Dakota</option>
        <option value="TN">Tennessee</option>
        <option value="TX">Texas</option>
        <option value="UT">Utah</option>
        <option value="VT">Vermont</option>
        <option value="VA">Virginia</option>
        <option value="WA">Washington</option>
        <option value="WV">West Virginia</option>
        <option value="WI">Wisconsin</option>
        <option value="WY">Wyoming</option>
      </select>
          </div>
          <div class="form-group">
            <label class="control-label">City</label>
            <input maxlength="200" type="text" name="city" class="form-control" required="required" placeholder="Enter Your City" value="" />
          </div>
          <button class="w3-btn w3-blue w3-padding w3-quarter nextBtn pull-right" type="button" >Next</button>
        </div>
      </div>
    </div>
    <div class="row setup-content" id="step-3">
      <div class="col-xs-6 col-md-offset-3">
        <div class="col-md-12">
          <h3> Job Interests</h3>
          <p>Please explore the job groups and select two job titles that are of interest.</p>
          <h4> Job #1</h4>
          <div class="form-group">
            <label class="control-label">Major Title</label>
            <select id="major1" name="major1" class="form-control" required="required">
        <option value="">None</option>
        <?php 
          foreach ($majorList as $row) { 
              echo '<option ';
              /*if($major1 == $row['soc_code']) 
                echo "selected";*/
              echo ' value="'.$row['soc_code'].'">'.$row['job_title'].'</option>';  
          }  
        ?>
      </select>
          </div>
          <div class="form-group">
            <label class="control-label">Minor Title</label>
            <select id="minor1" name="minor1" class="form-control" required="required">
          
      </select>
          </div>
          <div class="form-group">
            <label class="control-label">Detailed Title</label>
            <select id="detailed1" name="detailed1" class="form-control" required="required">
          
      </select>
          </div>
          <h4> Job #2</h4>
          <div class="form-group">
            <label class="control-label">Major Title</label>
            <select id="major2" name="major2" class="form-control" required="required">
        <option value="">None</option>
        <?php 
          foreach ($majorList as $row) { 
              echo '<option ';
              /*if($major2 == $row['soc_code']) 
                echo "selected";*/
              echo ' value="'.$row['soc_code'].'">'.$row['job_title'].'</option>';  
          }  
        ?>
      </select>
          </div>
          <div class="form-group">
            <label class="control-label">Minor Title</label>
            <select id="minor2" name="minor2" class="form-control" required="required">
          
      </select>
          </div>
          <div class="form-group">
            <label class="control-label">Detailed Title</label>
            <select id="detailed2" name="detailed2" class="form-control" required="required">
          
      </select>
          </div>
          <button class="w3-btn w3-blue w3-padding w3-quarter nextBtn pull-right" type="button" >Next</button>
        </div>
      </div>
    </div>
    <div class="row setup-content" id="step-4">
      <div class="col-xs-6 col-md-offset-3">
        <div class="col-md-12">
          <h3> Job Experiences</h3>
          <p>Specify the two most recent job titles that you have worked in.</p>
          <div class="form-group">
            <label class="control-label" style="display: block;">Job 1</label>
            <input maxlength="200" type="text" name="job1" class="form-control job" placeholder="Enter a Job" />
          </div>
          <div class="form-group">
            <label class="control-label" style="display: block;">Job 2</label>
            <input maxlength="200" type="text" name="job2" class="form-control job" placeholder="Enter a Job" />
          </div>
          <button class="w3-btn w3-green w3-padding w3-quarter pull-right" type="submit">Submit</button>
        </div>
      </div>
    </div>
    <div class="row setup-content" id="step-5">
      <div class="col-xs-6 col-md-offset-3">
        <div class="col-md-12">
          <h3> Complete</h3>
          <div class="messages"></div>
          <a href="index.php">Visit your JobRec dashboard now!</a>
        </div>
      </div>
    </div>
  </form>
  </div>
  <hr>
  
  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script src="controller/bootstrap.min.js"></script>
<style type="text/css">
  body {
    margin-top:40px;
}
.stepwizard-step p {
    margin-top: 10px;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard {
    display: table;
    width: 50%;
    position: relative;
}
.stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
}
.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-order: 0;
}
.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
}
</style>
<script type="text/javascript">
  $(document).ready(function () {
  var navListItems = $('div.setup-panel div a'),
          allWells = $('.setup-content'),
          allNextBtn = $('.nextBtn');

  allWells.hide();

  navListItems.click(function (e) {
      e.preventDefault();
      var $target = $($(this).attr('href')),
              $item = $(this);

      if (!$item.hasClass('disabled')) {
          navListItems.removeClass('btn-primary').addClass('btn-default');
          $item.addClass('btn-primary');
          allWells.hide();
          $target.show();
          $target.find('input:eq(0)').focus();
      }
  });

  allNextBtn.click(function(){
      var curStep = $(this).closest(".setup-content"),
          curStepBtn = curStep.attr("id"),
          nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
          curInputs = curStep.find("input[type='text'],input[type='email'],input[type='password'],input[type='url'],select"),
          isValid = true;

      $(".form-group").removeClass("has-error");
      for(var i=0; i<curInputs.length; i++){
          if (!curInputs[i].validity.valid){
              isValid = false;
              $(curInputs[i]).closest(".form-group").addClass("has-error");
              if($(curInputs[i]).attr("id") == "pass" || $(curInputs[i]).attr("id") == "repass")
              {
                if($(curInputs[i]).val() != $('#repass').val())
                {
                  isValid = false;
                  $(curInputs[i]).closest(".form-group").addClass("has-error");
                  $('#repass').closest(".form-group").addClass("has-error");
                }
              }
          }
      }

      if (isValid)
          nextStepWizard.removeAttr('disabled').trigger('click');
  });

  $('div.setup-panel div a.btn-primary').trigger('click');
});
</script>

<script type="text/javascript">
  $(document).ready(function () {

      $("#major1").change(function () {
          var val = $(this).val();
          if(val != "")
          {
            $.getJSON("model/onet-list.php?code="+val, function(data) {
            $("#minor1 option").remove();
            $("#minor1").append('<option value="">None</option>');

            $.each(data, function(){
                $("#minor1").append('<option value="'+ this.value +'">'+ this.name +'</option>');
          });
        });
      }
      });

      $("#minor1").change(function () {
          var val = $(this).val();
          if(val != "")
          {
            $.getJSON("model/onet-list.php?code="+val, function(data) {
            $("#detailed1 option").remove();
            $("#detailed1").append('<option value="">None</option>');

            $.each(data, function(){
                $("#detailed1").append('<option value="'+ this.value +'">'+ this.name +'</option>');
          });
        });
      }
      });


      $("#major2").change(function () {
          var val = $(this).val();
          if(val != "")
          {
            $.getJSON("model/onet-list.php?code="+val, function(data) {
            $("#minor2 option").remove();
            $("#minor2").append('<option value="">None</option>');

            $.each(data, function(){
                $("#minor2").append('<option value="'+ this.value +'">'+ this.name +'</option>');
          });
        });
      }
      });

      $("#minor2").change(function () {
          var val = $(this).val();
          if(val != "")
          {
            $.getJSON("model/onet-list.php?code="+val, function(data) {
            $("#detailed2 option").remove();
            $("#detailed2").append('<option value="">None</option>');

            $.each(data, function(){
                $("#detailed2").append('<option value="'+ this.value +'">'+ this.name +'</option>');
          });
        });
      }
      });
  });
</script>

<style type="text/css">
  .tt-hint,
        .job {
            border: 1px solid #CCCCCC;
            /*border-radius: 8px 8px 8px 8px;*/
            /*font-size: 24px;*/
            height: 45px;
            line-height: 30px;
            outline: medium none;
            padding: 8px 12px;
            width: 430px!important;
        }

        .tt-dropdown-menu {
            width: 400px;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            /*border-radius: 8px 8px 8px 8px;*/
            /*font-size: 18px;*/
            color: #111;
            background-color: #F1F1F1;
        }
</style>
<script src="controller/type-ahead.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('input.job').typeahead({
        name: 'job',
        remote: 'model/onet-list.php?query=%QUERY'
    });

});

$(function () {

    $('#register-form').on('submit', function (e) {

        if (!e.isDefaultPrevented()) {
            var url = "model/register-action.php";

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                beforeSend: function(xhr, options){
                    var curInputs = $(".form-group").find("input[type='text'],input[type='email'],input[type='password'],input[type='url'],select");
                    var isValid = true;

                    $(".form-group").removeClass("has-error");
                    for(var i=0; i<curInputs.length; i++){
                        if (!curInputs[i].validity.valid){
                            isValid = false;
                            $(curInputs[i]).closest(".form-group").addClass("has-error");
                        }
                        if($(curInputs[i]).attr("id") == "pass" || $(curInputs[i]).attr("id") == "repass")
                        {
                          if($(curInputs[i]).val() != $('#repass').val())
                          {
                            isValid = false;
                            $(curInputs[i]).closest(".form-group").addClass("has-error");
                            $('#repass').closest(".form-group").addClass("has-error");
                          }
                        }
                    }

                    if (!isValid)
                      xhr.abort();
                },
                success: function (data)
                {
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;

                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                    if (messageAlert && messageText) {
                        $('#register-form').find('.messages').html(alertBox);
                        $('#register-form')[0].reset();
                        $("#minor1 option, #minor2 option, #detailed2 option, #detailed1 option").remove();
                        $("#a-step-5").removeAttr('disabled').trigger('click');
                    }
                }
            });
            
            return false;
        }
    })
});
</script>

</body>
</html>
<?php ob_end_flush(); ?>