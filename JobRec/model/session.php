<?php

$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "job_recommendation");
if (!$connection)
{
    die("Database connection failed: " . mysqli_error());
}
if(!isset($_SESSION)) 
	session_start();
if(isset($_SESSION['user']))
{
	$user_check = $_SESSION['user'];
	$ses_sql = mysqli_query($connection, "SELECT email FROM user_profile WHERE email = '$user_check'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['email'];
}
mysqli_close($connection);

if(!isset($login_session))
{
    // header('Location: index.php');
}

?>