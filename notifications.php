<?php
include_once("includes/db_connect.php");
include_once("includes/check_login_status.php");
// If the page requestor is not logged in, header them away
if($user_ok != true || $log_username == ""){
	header("location: http://www.vreewijkers.nl/social");
    exit();
}
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysql_query($sql);
$numrows = mysql_num_rows($query);
if($numrows < 1){
	$notification_list = "Je hebt geen meldingen";
} else {
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$noteid = $row["id"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$notification_list .= "<p><a href='user.php?u=$initiator'>$initiator</a> | $app<br />$note</p>";
	}
}
mysql_query( "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?><?php
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query = mysql_query($sql);
$numrows = mysql_num_rows($query);
if($numrows < 1){
	$friend_requests = 'Geen vrienden verzoeken';
} else {
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$thumbquery = mysql_query("SELECT avatar FROM users WHERE username='$user1' LIMIT 1");
		$thumbrow = mysql_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1pic = '<img src="user/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
		if($user1avatar == NULL){
			$user1pic = '<img src="images/avatardefault.jpg" alt="'.$user1.'" class="user_pic">';
		}
		$friend_requests .= '<div id="friendreq_'.$reqID.'" class="vrienden verzoek">';
		$friend_requests .= '<a href="user.php?u='.$user1.'">'.$user1pic.'</a>';
		$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="user.php?u='.$user1.'">'.$user1.'</a> verzoekt vriendschap<br /><br />';
		$friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accepteer</button> or ';
		$friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">weiger</button>';
		$friend_requests .= '</div>';
		$friend_requests .= '</div>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Meldingen en vrienden verzoeken</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/notifications.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script src="js/friendRequestHandler.js"></script>
</head>
<body>
<?php include_once("includes/template_pagetop.php"); ?>
<div id="pageMiddle">
  <!-- START Page Content -->
  <div id="notesBox"><h2>Meldingen</h2><?php echo $notification_list; ?></div>
  <div id="friendReqBox"><h2>Vrienden verzoeken</h2><?php echo $friend_requests; ?></div>
  <div style="clear:left;"></div>
  <!-- END Page Content -->
</div>
</body>
</html>