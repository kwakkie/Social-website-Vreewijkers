<?php
include_once("includes/check_login_status.php");
// If user is already logged in, header away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
if(isset($_POST["e"])){
	// CONNECT TO THE DATABASE
	include_once("includes/db_connect.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$e = ($_POST['e']);
	$p = md5($_POST['p']);
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// FORM DATA ERROR HANDLING
	
	// END FORM DATA ERROR HANDLING
		$sql = "SELECT id, username, password FROM users WHERE email='$e' AND activated='1' LIMIT 1";
        $query = mysql_query($sql);
        $row = mysql_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
        $db_pass_str = $row[2];
		
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE); 
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
            $query = mysql_query($sql);
			echo $db_username;
		    exit();

}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Log In</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/main.css">

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var e = _("email").value;
	var p = _("password").value;
	if(e == "" || p == ""){
		_("status").innerHTML = "U heeft nog niet alles ingevuld";
	} else {
		_("loginbtn").style.display = "none";
		_("status").innerHTML = 'Even geduld a.u.b....';
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status").innerHTML = "Inloggen mislukt, probeert u het a.u.b. opnieuw";
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("e="+e+"&p="+p);
	}
}
</script>
</head>
<body>
<?php include_once("includes/template_pagetop.php"); ?>
<div id="bgimage">
<div id="pageMiddle">
  
  <!-- LOGIN FORM -->
  <form id="loginform" onsubmit="return false;">
    <h3>Inloggen</h3>
    <div>Email Adres:</div>
    <input type="text" id="email" onfocus="emptyElement('status')" maxlength="88">
    <div>Wachtwoord:</div>
    <input type="password" id="password" onfocus="emptyElement('status')" maxlength="100">
    <br /><br />
    <button id="loginbtn" onclick="login()">Log In</button> 
    <p id="status"></p>
    <div id="wwbtn"><button><a href="http://www.vreewijkers.nl/social/forgotpass.php">Wachtwoord vergeten?</a></button></div>
  </form>

  <!-- LOGIN FORM -->
</div>
</div>
<?php include_once("includes/template_pagebottom.php"); ?>
</body>
</html>