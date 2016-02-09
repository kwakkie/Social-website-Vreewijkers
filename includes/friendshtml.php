<?php
$friendsHTML = '';
$friends_view_all_link = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysql_query($sql);
$query_count = mysql_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
	$friendsHTML = $u." heeft nog geen vrienden";
} else {
	$max = 18;
	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysql_query($sql);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysql_query($sql);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}
	$friendArrayCount = count($all_friends);
	if($friendArrayCount > $max){
		array_splice($all_friends, $max);
	}
	if($friend_count > $max){
		$friends_view_all_link = '<a href="view_friends.php?u='.$u.'">view all</a>';
	}
	$orLogic = '';
	foreach($all_friends as $key => $user){
			$orLogic .= "username='$user' OR ";
	}
	$orLogic = chop($orLogic, "OR ");
	$sql = "SELECT username, avatar FROM users WHERE $orLogic";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$friend_username = $row["username"];
		$friend_avatar = $row["avatar"];
		if($friend_avatar != ""){
			$friend_pic = 'user/'.$friend_username.'/'.$friend_avatar.'';
		} else {
			$friend_pic = 'images/avatardefault.jpg';
		}
		$friendsHTML .= '<a href="user.php?u='.$friend_username.'"><img class="friendpics" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
	}
}
?>