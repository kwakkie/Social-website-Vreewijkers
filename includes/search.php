<?php
require_once("../includes/db_connect.php");
// Set variables
$searchfor = "";
$results = "";
if(isset($_POST['name'])){ //check if name is set
    $checkEmail = '/^[^@]+@[^\s\r\n\'";,@%]+$/';
    if (!preg_match($checkEmail, $_POST['name'])) {
    $searchfor = preg_replace('#[^a-z0-9]#i', '', $_POST['name']);}
    else {
        $searchfor = $_POST['name'];
    }
    $sql = "SELECT username FROM users WHERE username='$searchfor' OR email='$searchfor' AND activated='1' LIMIT 1";
    $query = mysql_query($sql);
    $results = mysql_fetch_assoc($query, MYSQL_ASSOC);
    if(!$results){
		mysql_close();
        echo "Not_found"; // send back not found result to ajax, ajax can handle further logic
        exit();
	}  
    $user = $results['username'];
    mysql_close();
    echo $user; // send back the user name that was requested as the result
    exit();
}
echo 'error';
exit();