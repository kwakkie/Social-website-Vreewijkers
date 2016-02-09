<?php
session_start();
// If user is logged in, header them away
if(isset($_SESSION["username"])){
	header("location: http://www.vreewijkers.nl/social/logout.php");
    exit();
}
?>
<?php
// Ajax calls this NAME CHECK code to execute
if(isset($_POST["usernamecheck"])){
	include_once("includes/db_connect.php");
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysql_query($sql); 
    $uname_check = mysql_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
	    echo '<strong style="color:#F00;">3 tot 16 tekens aub</strong>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<strong style="color:#F00;">Uw gebruikersnaam moet beginnen met een letter</strong>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<strong style="color:#009900;">' . $username . ' OK</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $username . ' is al bezet</strong>';
	    exit();
    }
}
?>
<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	include_once("includes/db_connect.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = $_POST['e'];
	$p = $_POST['p'];
	$g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysql_query($sql); 
	$u_check = mysql_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysql_query($sql); 
	$e_check = mysql_num_rows($query);
	//--------------------------------------------
	// FORM DATA ERROR HANDLING
	//--------------------------------------------
	$checkEmail = '/^[^@]+@[^\s\r\n\'";,@%]+$/';
	if (!preg_match($checkEmail, $e)) {
		echo "Ongeldig e-mail adres";
		exit();
	} else if($u == "" || $e == "" || $p == "" || $g == "" || $c == ""){
		echo "Het volgende is fout gegaan, verbeter a.u.b.waar nodig ";
        exit();
	} else if ($u_check > 0){ 
        echo "Deze naam is al in gebruik";
        exit();
	} else if ($e_check > 0){ 
        echo "Dit e-mail adres is al in gebruik";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "De gebruikersnaam moet tussen de 3 en 16 tekens groot zijn";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'De gebruikersnaam mag niet beginnen met een cijfer';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
		$cryptpass = crypt($p);
		include_once ("includes/randStrGen.php");
		$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (username, email, password, gender, country, ip, signup, lastlogin, notescheck)       
		        VALUES('$u','$e','$p_hash','$g','$c','$ip',now(),now(),now())";
;
		$query = mysql_query($sql); 
		$uid = mysql_insert_id($db_connect);
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
;
		$query = mysql_query($sql);
		// Create directory to hold each user's files(pics, MP3s, etc.)
		if (!file_exists("user/$u")) {
			mkdir("user/$u", 0755);
		}
		// Email the user their activation link
		$to = "$e";							 
		$from = "auto_responder@social.com";
		$subject = 'Vreewijkers social site Account Activation';
		$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Vreewijkers Social Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.vreewijkers.nl/social"><img src="http://www.vreewijkers.nl/social/images/Logo.png" width="90" height="90" alt="Vreewijkers" style="border:none; float:left;"></a>Vreewijkers Account Activation</div><div style="padding:24px; font-size:17px;">Hallo '.$u.',<br /><br />Klik op de onderstaande link om uw account te activeren en te gebruiken:<br /><br /><a href="http://www.vreewijkers.nl/social/activation.php?id='.$uid.'&u='.$u.'&p='.$p_hash.'">Klik hier voor activering</a><br /><br />Na succesvol activeren kunt u inloggen met:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/main.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	_(x).innerHTML = "";
}
function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function signup(){
	var u = _("username").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var c = _("country").value;
	var g = _("gender").value;
	var status = _("status");
	if(u == "" || e == "" || p1 == "" || p2 == "" || c == "" || g == ""){
		status.innerHTML = "Alles invullen a.u.b.";
	} else if(p1 != p2){
		status.innerHTML = "Controleer uw wachtwoord";
	} else if( _("terms").style.display == "none"){
		status.innerHTML = "Bekijk gebruiksvoorwaardes om akkoord te gaan";
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = 'even geduld a.u.b....';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
					status.innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
				} else {
					window.scrollTo(0,0);
					_("signupform").innerHTML = "OK "+u+", controleer uw e-mail en eventueel uw ongewenste reclame box op <u>"+e+"</u> om uw account te activeren. Zonder activering kunt u geen gebruik maken van uw account";
				}
	        }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1+"&c="+c+"&g="+g);
	}
}
function openTerms(){
	_("terms").style.display = "block";
	emptyElement("status");
}
/* function addEvents(){
	_("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; */
</script>
</head>
<?php include_once("includes/template_pagetop.php"); ?>
<div id="bgimage">
<div id="pageMiddle">
    
  <h3>Registreren</h3>
  <form name="signupform" id="signupform" onsubmit="return false;">
    <div>Gebruikersnaam: </div>
    <input id="username" type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
    <span id="unamestatus"></span>
    <div>Email Adres:</div>
    <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
    <div>Wachtwoord:</div>
    <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
    <div>Wachtwoord controle:</div>
    <input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="16">
    <div>Geslacht:</div>
    <select id="gender" onfocus="emptyElement('status')">
      <option value=""></option>
      <option value="m">Man</option>
      <option value="f">Vrouw</option>
    </select>
    <div>Land:</div>
    <select id="country" onfocus="emptyElement('status')">
	<option value=""></option>
    <option value="Nederland">Nederland</option>
    <option value="België">België</option>
    </select>
    
    <br /><br />
    <button id="signupbtn" onclick="signup()">Maak Account</button>
    <span id="status"></span>
  </form>
  <a href="http://www.vreewijkers.nl/social/user.php?u=dick">bekijk voorbeeld pagina</a></br></br>
  <div id="disclaimer">Note aan iedereen die deze pagina heeft gevonden,
      deze pagina is nog in ontwikkeling dus er worden in de loop der tijd functies toegevoegd.</div>

</div>
<div id="personal">
    backend werkend</br>
     inlog / uitlog / cookies</br>
     registratie met bevestigings mail</br>
     wachtwoord reset</br>
     avatar aanpassen </br>
     achtergrond aanpassen </br>
     berichten plaatsen en verwijderen </br>
     gebruikers blokkeren of toevoegen als vriend / verwijderen als vriend</br>
     fotogalerij </br>
     vriendelijst met avatars </br>
     avatar tonen in berichten</br>
     notificaties tonen ( icons nog aanpassen ! )</br>
     inlog pagina layout en registratie pagina layout </br>
     Registratie pagina als startpagina gebruiken.</br></br></br>
    next - extra functionaliteit toevoegen </br>
     gebruikers zoeken </br>
    1. foto's toevoegen aan berichten </br>
    2. responsive maken</br>
    3. layout galerij en notificaties aanpassen</br>
    </br>
  
 
    <a href="http://www.vreewijkers.nl/social/user.php?u=dick">link om voorbeeld pagina bekijken</a></br></br>
    
    
</div><!--END disclaimer-->
</div>
<?php include_once("includes/template_pagebottom.php"); ?>
</body>
</html>
