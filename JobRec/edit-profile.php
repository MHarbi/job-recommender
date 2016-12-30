<?php

include("model/login.php");

$logged = true;
if(!isset($_SESSION['user']))
{
    $logged = false;
    header("location: index.php");
}

if(isset($_POST["submit"]))
{
  require_once("model/db-connect.php");
  $db = Db::getInstance();
  $checkEmail = $db->query("SELECT u.email FROM user_profile AS u WHERE u.email LIKE '{$_POST['email']}' 
                          AND u.email IN (SELECT u1.email FROM user_profile AS u1 WHERE u1.email NOT LIKE '{$_SESSION['user']}')");
  
  if(!$checkEmail->fetch() || $_SESSION['user'] == $_POST['email'])
  {
    $db->query("UPDATE user_profile set first_name='{$_POST['first_name']}', last_name='{$_POST['last_name']}', password='{$_POST['pw']}', preferred_location='US-{$_POST['state']}-{$_POST['city']}', email='{$_POST['email']}' WHERE email LIKE '{$_SESSION['user']}'");

    // echo "user information update success!";
    // echo "<script>
    //   setTimeout(function(){
    //     window.location = './profile.php';  
    //   },2000)
      
    // </script>";

    $interests = array(trim($_POST['detailed1']), trim($_POST['detailed2']));
    $db->query("DELETE FROM `job_case` WHERE job_id IN (SELECT job_id FROM user_interest WHERE case_type LIKE 'Onet' AND email LIKE '{$_POST['email']}')");
      
    foreach ($interests as $value) {
      $res1 = $db->query("SELECT * FROM onet WHERE soc_code LIKE '" . $value . "'");
      $count = $res1->rowCount();
      $row = $res1->fetch();
      if($count > 0)
      {
        $res2 = $db->query("INSERT INTO job_case(case_type, job_title, soc_code, job_desc, job_desc_terms, job_desc_freqs, degree_required, pay_avg_monthly) 
          VALUES ('Onet', '" . $row['job_title'] . "', '" . $row['soc_code'] . "', 
          '" . $row['job_desc'] . "',
          '" . $row['job_terms'] . "', 
          '" . $row['job_frequencies'] . "', 
          \"" . $row['degree'] . "\",
          '" . $row['pay_avg_monthly'] . "')");

        $job_id = intval($db->lastInsertId());
        $res3 = $db->query("INSERT INTO user_interest(job_id, email) 
          VALUES ('$job_id', '{$_POST['email']}')");

      }
    }
    unset($interests);

    $job1 = trim($_POST['job1']); $job1 = strip_tags($job1); $job1 = htmlspecialchars($job1);
    $job2 = trim($_POST['job2']); $job2 = strip_tags($job2); $job2 = htmlspecialchars($job2);

    $db->query("DELETE FROM `job_case` WHERE job_id IN (SELECT job_id FROM user_interest WHERE case_type LIKE 'Resume' AND email LIKE '{$_POST['email']}')");

    if(!empty($job1))
      {
        $res1 = $db->query("SELECT * FROM onet WHERE job_title LIKE '" . $job1 . "'");
        $count = $res1->rowCount();
        $row = $res1->fetch();
        if($count > 0)
        {
          $res2 = $db->query("INSERT INTO job_case(case_type, job_title, soc_code, job_desc, job_desc_terms, job_desc_freqs, degree_required, pay_avg_monthly) 
            VALUES ('Resume', '" . $row['job_title'] . "', '" . $row['soc_code'] . "', 
            '" . $row['job_desc'] . "',
            '" . $row['job_terms'] . "', 
            '" . $row['job_frequencies'] . "', 
            \"" . $row['degree'] . "\",
            '" . $row['pay_avg_monthly'] . "')");

          $job_id = intval($db->lastInsertId());
          $res3 = $db->query("INSERT INTO user_interest(job_id, email) 
            VALUES ('$job_id', '{$_POST['email']}')");
        }
        else
        {
          $res2 = $db->query("INSERT INTO job_case(case_type, job_title) 
            VALUES ('Resume', '$job1')");

          $job_id = intval($db->lastInsertId());
          $res3 = $db->query("INSERT INTO user_interest(job_id, email) 
            VALUES ('$job_id', '{$_POST['email']}')");
        }
      }

    if(!empty($job2))
      {
        $res1 = $db->query("SELECT * FROM onet WHERE job_title LIKE '" . $job2 . "'");
        $count = $res1->rowCount();
        $row = $res1->fetch();
        if($count > 0)
        {
          $res2 = $db->query("INSERT INTO job_case(case_type, job_title, soc_code, job_desc, job_desc_terms, job_desc_freqs, degree_required, pay_avg_monthly) 
            VALUES ('Resume', '" . $row['job_title'] . "', '" . $row['soc_code'] . "', 
            '" . $row['job_desc'] . "',
            '" . $row['job_terms'] . "', 
            '" . $row['job_frequencies'] . "', 
            \"" . $row['degree'] . "\",
            '" . $row['pay_avg_monthly'] . "')");

          $job_id = intval($db->lastInsertId());
          $res3 = $db->query("INSERT INTO user_interest(job_id, email) 
            VALUES ('$job_id', '{$_POST['email']}')");
        }
        else
        {
          $res2 = $db->query("INSERT INTO job_case(case_type, job_title) 
            VALUES ('Resume', '$job2')");

          $job_id = intval($db->lastInsertId());
          $res3 = $db->query("INSERT INTO user_interest(job_id, email) 
            VALUES ('$job_id', '{$_POST['email']}')");
        }
      }
    header("location: profile.php");

  }
  else{
    echo "new email already exists, please use another, will redirect to edit profile page in 2s.";
    echo "<script>
      setTimeout(function(){
        window.location = './edit_profile.php'; 
      },2000)
      
    </script>";
  };
  // if ($_POST(pw)!=$_POST(c_pw)) {
  //  //echo "<script>alert('The password you entered is different');</script>";
  // }else{
  //  $pw = $db->query("UPDATE user_profile set password='{$_POST['pw']}' WHERE email LIKE '" . $_SESSION['user'] . "'"); 
  // }
  
//  $res = $db->query("UPDATE user_profile set first_name='{$_POST['first_name']}' WHERE email LIKE '" . $_SESSION['user'] . "'");
unset($db);
}
//header("location: profile.php");

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
<div class="w3-main w3-white">

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:80px"></div>

  <?php require_once('model/job-query.php'); ?>

<?php
      require_once("model/db-connect.php");
      $db = Db::getInstance();

      $result = $db->query("SELECT soc_code, job_title FROM onet WHERE soc_group LIKE 'major'");
      foreach ($result->fetchAll() as $row) { 
        $majorList[] = $row;
      }

      $res = $db->query("SELECT * FROM user_profile WHERE email LIKE '" . $_SESSION['user'] . "'");
      $row = $res->fetch();

      $location = str_replace('US-', '', $row['preferred_location']);
      $state = substr($location, 0, 2);
      $city = substr($location, 3);
      $location = $state.', '.$city;

      $res1 = $db->query("SELECT j.soc_code, o.path 
                          FROM job_case j, user_interest u, onet o 
                          WHERE u.email LIKE '{$row['email']}' AND u.job_id = j.job_id AND j.soc_code = o.soc_code AND 
                                j.case_type = 'Onet'");
      $res2 = $db->query("SELECT job_title FROM job_case, user_interest where user_interest.email='{$row['email']}' and
user_interest.job_id=job_case.job_id and job_case.case_type='Resume'");

      $interests = array();
      $i = -1;
      while ($row1 = $res1->fetch()) {
          $interests[++$i]['soc_code'] = $row1['soc_code'];
          $interests[$i]['path'] = explode(".", $row1['path']);
          $tmp = $db->query("SELECT soc_code FROM onet WHERE path = '{$interests[$i]['path'][0]}'");
          $tmp = $tmp->fetch();
          $interests[$i]['major'] = $tmp['soc_code'];
          $tmp = $db->query("SELECT soc_code FROM onet WHERE path = '{$interests[$i]['path'][0]}.{$interests[$i]['path'][1]}'");
          $tmp = $tmp->fetch();
          $interests[$i]['minor'] = $tmp['soc_code'];
      }

?>
  <div class="w3-container" style="margin: auto; width: 75%">
    <h4><strong>Profile</strong></h4>
    <form id="update-form" class="w3-twothird" role="form" method="post" action="">
      <label>First Name:</label>
        <input class="w3-input" type="text" name="first_name" value="<?php echo $row['first_name']; ?>" required/>
      <br><label>Last Name:</label>
        <input class="w3-input" type="text" name="last_name" value="<?php echo $row['last_name']; ?>" />
      <br><label>Email:</label>
        <input class="w3-input" type="text" name="email" value="<?php echo $row['email']; ?>"/>
      <br><label>Password: </label>
        <input class="w3-input" type="password" name="pw" value="<?php echo $row['password']; ?>"/>
      <br><label>Comfirm Password: </label>
        <input class="w3-input" type="password" name="c_pw" value="<?php echo $row['password']; ?>"/>
        <br>
      <h6><strong> Prefered Location:</strong></h6>
      <div class="w3-clear">
        <div class="w3-third">
            <label>State: </label><br>
            <select name="state" class="w3-select" style="width: 250px;">
              <?php
              $states = array(
                "AL"=>"Alabama",
                "AK"=>"Alaska",
                "AZ"=>"Arizona",
                "AR"=>"Arkansas",
                "CA"=>"California",
                "CO"=>"Colorado",
                "CT"=>"Connecticut",
                "DE"=>"Delaware",
                "DC"=>"District Of Columbia",
                "FL"=>"Florida",
                "GA"=>"Georgia",
                "HI"=>"Hawaii",
                "ID"=>"Idaho",
                "IL"=>"Illinois",
                "IN"=>"Indiana",
                "IA"=>"Iowa",
                "KS"=>"Kansas",
                "KY"=>"Kentucky",
                "LA"=>"Louisiana",
                "ME"=>"Maine",
                "MD"=>"Maryland",
                "MA"=>"Massachusetts",
                "MI"=>"Michigan",
                "MN"=>"Minnesota",
                "MS"=>"Mississippi",
                "MO"=>"Missouri",
                "MT"=>"Montana",
                "NE"=>"Nebraska",
                "NV"=>"Nevada",
                "NH"=>"New Hampshire",
                "NJ"=>"New Jersey",
                "NM"=>"New Mexico",
                "NY"=>"New York",
                "NC"=>"North Carolina",
                "ND"=>"North Dakota",
                "OH"=>"Ohio",
                "OK"=>"Oklahoma",
                "OR"=>"Oregon",
                "PA"=>"Pennsylvania",
                "RI"=>"Rhode Island",
                "SC"=>"South Carolina",
                "SD"=>"South Dakota",
                "TN"=>"Tennessee",
                "TX"=>"Texas",
                "UT"=>"Utah",
                "VT"=>"Vermont",
                "VA"=>"Virginia",
                "WA"=>"Washington",
                "WV"=>"West Virginia",
                "WI"=>"Wisconsin",
                "WY"=>"Wyoming"
              );
              $state;
              foreach ($states as $state_code => $state_text){
                if($state_code == $state){
                  echo "<option value=\"$state_code\" selected>$state_text</option>\n";
                }
                else{
                  echo "<option value=\"$state_code\" >$state_text</option>\n";
                }
              }
              ?>
            </select>
          </div>
          <div class="w3-third">
            <label>City: </label>
            <input class="w3-input w3-right" type="text" name="city" value="<?php echo $city; ?>" />
          </div>
        </div>

        <h6><strong> Interesting Job #1</strong></h6>
        <label>Major Title</label>
        <select id="major1" name="major1" class="w3-select" required="required">
        <option value="">None</option>
        <?php 
          foreach ($majorList as $row) { 
              echo '<option ';
              echo ' value="'.$row['soc_code'].'">'.$row['job_title'].'</option>';  
          }  
        ?>
        </select>
          <p><label>Minor Title</label>
          <br><select id="minor1" name="minor1" class="w3-select" required="required">
          
          </select></p>
          <p><label>Detailed Title</label>
          <br><select id="detailed1" name="detailed1" class="w3-select" required="required">
          
          </select></p>

          <h6><strong> Interesting Job #2</strong></h6>
            <label>Major Title</label>
            <select id="major2" name="major2" class="w3-select" required="required">
              <option value="">None</option>
              <?php 
                foreach ($majorList as $row) { 
                    echo '<option ';
                    echo ' value="'.$row['soc_code'].'">'.$row['job_title'].'</option>';  
                }  
              ?>
            </select>
            <p><label>Minor Title</label>
            <br><select id="minor2" name="minor2" class="w3-select" required="required"></p>
          
            </select>
            <p><label>Detailed Title</label>
            <br><select id="detailed2" name="detailed2" class="w3-select" required="required">
          
          </select></p>

      <?php
      /*$i=0;
      while ($row = $res1->fetch()) {
        echo "<div class='w3-row w3-large'>
        <div class='w3-col s3'>
        <p>Job Interests ". ($i + 1) . ":</p>
        </div>
        <div class='w3-col s6'>
        <p><input type='text' style='width:100%' value='" .($row[0]). "' /><p/>
        </div>
        </div>";

        $i++;
      }*/
      ?>
      <?php
      $exJobs = array();
      while ( $row = $res2->fetch()) {
        $exJobs[] = $row[0];
      } ?>

      <h6><strong> Job Experiences</strong></h6>
      <p><label>Job 1</label>
        <input maxlength="200" type="text" name="job1" class="w3-input job" placeholder="Enter a Job" value="<?php if(isset($exJobs[0])) echo $exJobs[0]; ?>" /></p>
      <p><label>Job 2</label>
        <input maxlength="200" type="text" name="job2" class="w3-input job" placeholder="Enter a Job" value="<?php if(isset($exJobs[0])) echo $exJobs[1]; ?>" /></p>


    <a class="w3-btn w3-red w3-padding w3-margin-right" href="profile.php" style="margin-top:5%;">Cancel</a>
    <input class="w3-btn w3-green w3-padding w3-margin-right" type="submit" value="Update" name="submit" style="margin-top:5%;">
  </form>
  </div>
  <hr>

  <footer class="w3-container w3-padding-16" style="margin-top:32px">Powered by <a href="http://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">w3.css</a></footer>

<!-- End page content -->
</div>

<script>
  $(document).ready(function () {
      var _init1 = true;
      var _init2 = true;

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
            }).done(function(data){
              if(_init1)
              {
                $("#minor1").val("<?php if(isset($interests[0]['minor'])) echo $interests[0]['minor']; ?>").change();
              }
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
            })
            .done(function(data){
              if(_init1)
              {
                $("#detailed1").val("<?php if(isset($interests[0]['soc_code'])) echo $interests[0]['soc_code']; ?>").change();
                _init1 = false;
              }
            });;
          }
    });

      $("#major1").val("<?php if(isset($interests[0]['major'])) echo $interests[0]['major']; ?>").change();

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
            }).done(function(data){
              if(_init2)
              {
                $("#minor2").val("<?php if(isset($interests[0]['minor'])) echo $interests[1]['minor']; ?>").change();
              }
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
            })
            .done(function(data){
              if(_init2)
              {
                $("#detailed2").val("<?php if(isset($interests[0]['soc_code'])) echo $interests[1]['soc_code']; ?>").change();
                _init2 = false;
              }
            });;
          }
    });

      $("#major2").val("<?php if(isset($interests[0]['major'])) echo $interests[1]['major']; ?>").change();

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
