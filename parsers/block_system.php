<?php
include_once("../includes/check_login_status.php");
include_once("../includes/db_connect.php");

?><?php
if (isset($_POST['type']) && isset($_POST['blockee'])){
	$blockee = preg_replace('#[^a-z0-9]#i', '', $_POST['blockee']);
	$sql = "SELECT COUNT(id) FROM users WHERE username='$blockee' AND activated='1' LIMIT 1";
	$query = mysql_query($sql);
	$exist_count = mysql_fetch_row($query);
	if($exist_count[0] < 1){
		mysql_close();
		echo "$blockee does not exist.";
		exit();
	}
	$sql = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$blockee' LIMIT 1";
	$query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
	if($_POST['type'] == "block"){
	    if ($numrows > 0) {
			mysql_close();
	        echo "You already have this member blocked.";
	        exit();
	    } else {
			$sql = "INSERT INTO blockedusers(blocker, blockee, blockdate) VALUES('$log_username','$blockee',now())";
			$query = mysql_query($sql);
			mysql_close();
	        echo "blocked_ok";
	        exit();
		}
	} else if($_POST['type'] == "unblock"){
	    if ($numrows == 0) {
		    mysql_close();
	        echo "You do not have this user blocked, therefore we cannot unblock them.";
	        exit();
	    } else {
			$sql = "DELETE FROM blockedusers WHERE blocker='$log_username' AND blockee='$blockee' LIMIT 1";
			$query = mysql_query($sql);
			mysql_close();
	        echo "unblocked_ok";
	        exit();
		}
	}
}
?>
