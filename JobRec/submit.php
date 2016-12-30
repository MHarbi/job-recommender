<?php
include("model/session.php");

if(isset($_POST["submit"])){
	include("model/db-connect.php");
	
	
	$db = Db::getInstance();
	//$checkEmail=$db->query("SELECT email FROM user_profile WHERE email like {$_POST['email']} AND email NOT LIKE {$_SESSION['user']}");
	if($_SESSION['user'] == $_POST['email'])
	$firstName = $db->query("UPDATE user_profile set first_name='{$_POST['first_name']}' WHERE email LIKE '" . $_SESSION['user'] . "'");
	$lastName = $db->query("UPDATE user_profile set last_name='{$_POST['last_name']}' WHERE email LIKE '" . $_SESSION['user'] . "'");

	$password = $db->query("UPDATE user_profile set password='{$_POST['pw']}' WHERE email LIKE '" . $_SESSION['user'] . "'");
	$preferred_location = $db->query("UPDATE user_profile set preferred_location='" . "US-" . $_POST['state'] . "-" . $_POST['city'] . "' WHERE email LIKE '" . $_SESSION['user'] . "'");
	

	
	$checkEmail=$db->query("SELECT u.email from user_profile as u where u.email='{$_POST['email']}'
						and u.email LIKE (SELECT u1.email FROM user_profile as u1 WHERE email NOT LIKE'" .$_SESSION['user']."')");
	
	if(!$checkEmail->fetch()){
		$email = $db->query("UPDATE user_profile set email='{$_POST['email']}' WHERE email LIKE '" . $_SESSION['user'] . "'");
		echo "user information update success!";
		echo "<script>
			setTimeout(function(){
				window.location = './profile.php';	
			},2000)
			
		</script>";
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
	// 	//echo "<script>alert('The password you entered is different');</script>";
	// }else{
	// 	$pw = $db->query("UPDATE user_profile set password='{$_POST['pw']}' WHERE email LIKE '" . $_SESSION['user'] . "'");	
	// }
	
//	$res = $db->query("UPDATE user_profile set first_name='{$_POST['first_name']}' WHERE email LIKE '" . $_SESSION['user'] . "'");
}
//header("location: profile.php");

?>

