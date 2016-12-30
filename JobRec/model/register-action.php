<?php

include_once 'db-connect.php';
$db = Db::getInstance();

$okMessage = 'Registration form successfully submitted. Thank you, you may now log in!';
$errorMessage = 'There was an error while submitting the form. Please try again later';

$responseArray = array('type' => 'success', 'message' => $okMessage);
try
{
    
	// clean user inputs to prevent sql injections
	$first_name = trim($_POST['first_name']); $first_name = strip_tags($first_name); $first_name = htmlspecialchars($first_name);

	$last_name = trim($_POST['last_name']); $last_name = strip_tags($last_name); $last_name = htmlspecialchars($last_name);
	
	$email = trim($_POST['email']); $email = strip_tags($email); $email = htmlspecialchars($email);
	
	$pass = trim($_POST['pass']); $pass = strip_tags($pass); $pass = htmlspecialchars($pass);

	$country = trim($_POST['country']); $country = strip_tags($country); $country = htmlspecialchars($country);

	$state = trim($_POST['state']); $state = strip_tags($state); $state = htmlspecialchars($state);
	
	$city = trim($_POST['city']); $city = strip_tags($city); $city = htmlspecialchars($city);

	$major1 = trim($_POST['major1']); $minor1 = trim($_POST['minor1']); $detailed1 = trim($_POST['detailed1']);

	$major2 = trim($_POST['major2']); $minor2 = trim($_POST['minor2']); $detailed2 = trim($_POST['detailed2']);

	$interests = array($detailed1, $detailed2);

	$job1 = trim($_POST['job1']); $job1 = strip_tags($job1); $job1 = htmlspecialchars($job1);

	$job2 = trim($_POST['job2']); $job2 = strip_tags($job2); $job2 = htmlspecialchars($job2);

	$job1 = trim($_POST['job1']); $job1 = strip_tags($job1); $job1 = htmlspecialchars($job1);

	$job2 = trim($_POST['job2']); $job2 = strip_tags($job2); $job2 = htmlspecialchars($job2);
	
	{
		try {
			 $db->beginTransaction();

			$query = "INSERT INTO `user_profile`(`email`, `password`, `first_name`, `last_name`, `preferred_location`) 
				  VALUES ('" . $email . "',
				          '" . $pass . "',
				          '" . $first_name . "',
				          '" . $last_name . "',
				          'US-" . $state . "-" . $city . "')";
			$res = $db->query($query);

			$interests_num = 0;

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
						VALUES ('$job_id', '" . $email . "')");

					$interests_num++;
				}
			}

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
						VALUES ('$job_id', '" . $email . "')");
				}
				else
				{
					$res2 = $db->query("INSERT INTO job_case(case_type, job_title) 
						VALUES ('Resume', '$job1')");

					$job_id = intval($db->lastInsertId());
					$res3 = $db->query("INSERT INTO user_interest(job_id, email) 
						VALUES ('$job_id', '" . $email . "')");
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
						VALUES ('$job_id', '" . $email . "')");
				}
				else
				{
					$res2 = $db->query("INSERT INTO job_case(case_type, job_title) 
						VALUES ('Resume', '$job2')");

					$job_id = intval($db->lastInsertId());
					$res3 = $db->query("INSERT INTO user_interest(job_id, email) 
						VALUES ('$job_id', '" . $email . "')");
				}
			}
			

			if ($res && $interests_num >= 2) {
				$db->commit();
				$responseArray = array('type' => 'success', 'message' => $okMessage);

				// unset($job1);
				// unset($job2);
				// unset($_SESSION['new-user']);
			} 
			else {
				$db->rollback();
				$responseArray = array('type' => 'danger', 'message' => $errorMessage);
			}

		} catch (Exception $e) {
			echo $e;
			$db->rollback();
		}
			
	}

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}
else {
    echo $responseArray['message'];
}
