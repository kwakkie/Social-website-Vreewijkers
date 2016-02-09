<?php 
// Initialize any variables that the page might echo
$u = "";
$sex = "Male";
$userlevel = "";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
$country = "";
$joindate = "";
$lastsession = "";
$backgroundpicture = "";
$background_btn = "";
$background_form = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: http://www.vreewijkers.nl/social/index.php");
    exit();	
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysql_query($sql);
// Now make sure that user exists in the table
$numrows = mysql_num_rows($user_query);
if($numrows < 1){
	echo "Deze gebruiker betaat niet of dit account is nog niet geactiveerd";
    exit();	
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Wijzig profielfoto</a>';
	$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="parsers/photo_system.php">';
	$avatar_form .=   '<h4>Kies profielfoto</h4>';
	$avatar_form .=   '<input type="file" name="avatar" required>';
	$avatar_form .=   '<p><input type="submit" value="Upload"></p>';
	$avatar_form .= '</form>';
    
    $background_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'background_form\')">Wijzig achtergrondfoto</a>';
    $background_form = '<form id="background_form" enctype="multipart/form-data" method="post" action="parsers/photo_system.php">';
    $background_form .= '<h2>Kies achtergrondfoto</h2>';
    $background_form .= '<input type="file" name="background" required>';
    $background_form .= '<p><input type="submit" value="upload"></p>';
    $background_form .= '</form>';
}
// Fetch the user row from the query above
while ($row = mysql_fetch_array($user_query, MYSQL_ASSOC)) {
	$profile_id = $row["id"];
	$gender = $row["gender"];
	$country = $row["country"];
	$userlevel = $row["userlevel"];
	$avatar = $row["avatar"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
    $backgroundpicture = $row["backgroundpicture"];
}
if($gender == "f"){
	$sex = "Female";
}
$profile_pic = '<img src="user/'.$u.'/'.$avatar.'" alt="'.$u.'">';
if($avatar != ""){
           $picurl = "../user/$log_username/$avatar"; 
           if (file_exists($picurl)) { unlink($picurl); }
               if(!file_exists($picurl)){
                  mkdir($picurl, 0777,true);
               }
     }
if($avatar == NULL){
	$profile_pic = '<img src="images/avatardefault.jpg" alt="'.$user1.'">';
}
$background = 'user/'.$u.'/'.$backgroundpicture;
if($backgroundpicture == NULL){
    $background = 'images/achtergrond.jpg';
}
?>