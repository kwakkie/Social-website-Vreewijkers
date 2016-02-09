<?php
include_once("includes/check_login_status.php");
// If user is already logged in, header away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
include_once("includes/db_connect.php");
if(isset($_POST["e"])){
	$e = ($_POST['e']);
	$sql = "SELECT id, username FROM users WHERE email='$e' AND activated='1' LIMIT 1";
	$query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
	if($numrows > 0){
		while($row = mysql_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$u = $row["username"];
		}
		$emailcut = substr($e, 0, 4);
		$randNum = rand(10000,99999);
		$tempPass = "$emailcut$randNum";
		$hashTempPass = md5($tempPass);
		$sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
	    $query = mysql_query($sql);
		$to = "$e";
		$from = "auto_responder@vreewijkers.nl";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject ="yoursite Temporary Password";
		$msg = '<h2>Hello '.$u.'</h2><p>DIt is een geautomatiseerd bericht van vreewijkers social site. Heeft u dit niet aangevraagd omdat u uw wachtwoord bent vergeten kunt u dit bericht negeren, u hoeft geen verdere actie te ondernemen.</p><p>Heeft u wel aangegeven dat u uw wachtwoord bent vergeten kunt u inloggen met het volgende tijdelijke wachtwoord en heeft u de mogelijkheid om een nieuw wachtwoord in te stellen</p><p>Na het aanklikken van de link is dit uw nieuwe tijdelijke wachtwoord:<br /><b>'.$tempPass.'</b></p><p><a href="http://www.vreewijkers.nl/social/forgotpass.php?u='.$u.'&p='.$hashTempPass.'">Klik hier om uw wachtwoord aan te passen en in te loggen met dit nieuwe wachtwoord</a></p><p>Als u niet op de link klikt wordt u wachwoord niet veranderd, om uw wachtwoord te veranderen naar het bovenstaande wachwoord wat wij voor u hebben gemaakt, klik dan op de link</p>';
		if(mail($to,$subject,$msg,$headers)) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
    exit();
}
?><?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
	$u = ($_GET['u']);
	$temppasshash = ($_GET['p']);
	if(strlen($temppasshash) < 10){
		exit();
	}
	$sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
	$query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
	if($numrows == 0){
		header("location: message.php?msg=Deze gebruikersnaam is niet gekoppeld aan dit tijdelijke wachtwoord.");
    	exit();
	} else {
		$row = mysql_fetch_row($query);
		$id = $row[0];
		$sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u' LIMIT 1";
	    $query = mysql_query($sql);
		$sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u' LIMIT 1";
	    $query = mysql_query($sql);
	    header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/main.css">
<style type="text/css">
#forgotpassform{
	margin-top:24px;	
}
#forgotpassform > div {
	margin-top: 12px;	
}
#forgotpassform > input {
	width: 250px;
	padding: 3px;
	background: #F3F9DD;
}
#forgotpassbtn {
	font-size:15px;
	padding: 10px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function forgotpass(){
	var e = _("email").value;
	if(e == ""){
		_("status").innerHTML = "Geef uw email adres waar het tijdelijke wachtwoord naartoe gestuurd moet worden.";
	} else {
		_("forgotpassbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "forgotpass.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h3>Stp 2. Controleer uw email ( eventueel het postvak ongewenste mai)l</h3><p>U kunt nu dit venster sluiten</p>';
				} else if (response == "no_exist"){
					_("status").innerHTML = "Dit email adres is niet bekend in ons systeem";
				} else if(response == "email_send_failed"){
					_("status").innerHTML = "Mail function failed to execute";
				} else {
					_("status").innerHTML = "An unknown error occurred";
				}
	        }
        }
        ajax.send("e="+e);
	}
}
</script>
</head>
<body>
<?php include_once("includes/template_pagetop.php"); ?>
<div id="pageMiddle">
  <h3>Vraag een nieuw tijdelijk wachtwoord aan</h3>
  <form id="forgotpassform" onsubmit="return false;">
    <div>Step 1: Geef hier uw email adres.</div>
    <input id="email" type="text" onfocus="_('status').innerHTML='';" maxlength="88">
    <br /><br />
    <button id="forgotpassbtn" onclick="forgotpass()">Stuur tijdelijk wachtwoord</button> 
    <p id="status"></p>
  </form>
</div>
<?php include_once("includes/template_pagebottom.php"); ?>
</body>
</html>