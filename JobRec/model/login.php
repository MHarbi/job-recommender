<?php

session_start(); 
$error=''; 

if (isset($_POST['submit']))
{
    if (empty($_POST['username']) || empty($_POST['password'])) 
    {
        $error = "Username or Password is invalid";
    }
    else
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $connection = mysqli_connect("localhost", "root", "");
        if (!$connection)
        {
            die("Database connection failed: " . mysqli_error());
        }
        $db = mysqli_select_db($connection, "job_recommendation");
        
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);
        
        // sql query to find user and fetch authorization information
        $query = mysqli_query($connection, "SELECT email, first_name, last_name, preferred_location, preferred_loc_lat, preferred_loc_lon FROM user_profile WHERE email = '$username' AND password = '$password'");
        $rows = mysqli_num_rows($query);
        if ($rows === 1)
        {
            $session = mysqli_fetch_assoc($query);
            
            // initialize session
            $_SESSION['user'] = $session['email'];
            $_SESSION['first_name'] = $session['first_name'];
            $_SESSION['last_name'] = $session['last_name'];
            $_SESSION['location'] = $session['preferred_location'];
            $_SESSION['loc_lat'] = $session['preferred_loc_lat'];
            $_SESSION['loc_lon'] = $session['preferred_loc_lon'];
        } 
        else
        {
            $error = "Username or Password is invalid";
        }
        mysqli_close($connection);
    }
}

?>