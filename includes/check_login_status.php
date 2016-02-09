<?php
session_start();
include_once("includes/db_connect.php");
// Initialize some vars
$user_ok = false;
$log_id = "";
$log_username = "";
$log_password = "";
// User Verify function
function evalLoggedUser($id,$u,$p){
	$sql = "SELECT ip FROM users WHERE id='$id' AND username='$u' AND password='$p' AND activated='1' LIMIT 1";
    $query = mysql_query($sql);
    $numrows = mysql_num_rows($query);
	if($numrows > 0){
		return true;
	}
}
if(isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])) {
	$log_id = ($_SESSION['userid']);
	$log_username = ($_SESSION['username']);
	$log_password = ($_SESSION['password']);
	// Verify the user
	$user_ok = evalLoggedUser($log_id,$log_username,$log_password);
} else if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
	$_SESSION['userid'] = ($_COOKIE['id']);
    $_SESSION['username'] = ($_COOKIE['user']);
    $_SESSION['password'] = ($_COOKIE['pass']);
	$log_id = $_SESSION['userid'];
	$log_username = $_SESSION['username'];
	$log_password = $_SESSION['password'];
	// Verify the user
	$user_ok = evalLoggedUser($log_id,$log_username,$log_password);
	if($user_ok == true){
		// Update their lastlogin datetime field
		$sql = "UPDATE users SET lastlogin=now() WHERE id='$log_id' LIMIT 1";
        $query = mysql_query($sql);
	}
}
?>