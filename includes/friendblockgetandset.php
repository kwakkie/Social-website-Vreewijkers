<?php
$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true){
	$friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
	if(mysql_num_rows(mysql_query($friend_check)) > 0){
        $isFriend = true;
    }
	$block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username' LIMIT 1";
	if(mysql_num_rows(mysql_query($block_check1)) > 0){
        $ownerBlockViewer = true;
    }
	$block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u' LIMIT 1";
	if(mysql_num_rows(mysql_query($block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}

$friend_button = '<button disabled>Vriend toevoegen</button>';
$block_button = '<button disabled>Blokkeer gebruiker</button>';
// FRIEND BUTTON LOGIC
if($isFriend == true){
	$friend_button = '<button onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')">Vriend verwijderen</button>';
} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
	$friend_button = '<button onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')">Vriend toevoegen</button>';
}
// BLOCK BUTTON LOGIC
if($viewerBlockOwner == true){
	$block_button = '<button onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')">Deblokkeer gebruiker</button>';
} else if($user_ok == true && $u != $log_username){
	$block_button = '<button onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')">Blokkeer gebruiker</button>';
}
?>