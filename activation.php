<?php
if (isset($_GET['id']) && isset($_GET['u']) && isset($_GET['p'])) {
	// Connect to database and sanitize incoming $_GET variables
    include_once("includes/db_connect.php");
    $id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$p = ($_GET['p']);
	//Evaluate the lengths of the incoming $_GET variable
	if($id == "" || strlen($u) < 3 || strlen($p) != 74){
	// Log this issue into a text file
	header("location: message.php?msg=activation_string_length_issues");
    exit(); 
	}
	// Check against the database
	$sql = "SELECT * FROM users WHERE id='$id' AND username='$u' AND password='$p' LIMIT 1";
    $query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
	// Evaluate for a match in the system (0 = no match, 1 = match)
	if($numrows == 0){
		// Log this potential hack attempt
		header("location: message.php?msg=Deze activatie code is niet goed, probeert u het a.u.b. opnieuw");
    	exit();
	}
	// Match was found, you can activate them
	$sql = "UPDATE users SET activated='1' WHERE id='$id' LIMIT 1";
    $query = mysql_query($sql);
	// Optional double check to see if activated in fact now = 1
	$sql = "SELECT * FROM users WHERE id='$id' AND activated='1' LIMIT 1";
    $query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
	// Evaluate the double check
    if($numrows == 0){
		// Log this issue of no switch of activation field to 1
        header("location: message.php?msg=activation_failure");
    	exit();
    } else if($numrows == 1) {
		// activation!
        header("location: message.php?msg=activation_success");
    	exit();
    }
} else {
	// Log this issue of missing initial $_GET variables
	header("location: message.php?msg=missing_GET_variables");
    exit(); 
}
?>