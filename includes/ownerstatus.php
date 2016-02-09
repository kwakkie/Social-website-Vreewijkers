<?php
$status_ui = "";
$statuslist = "";
if($isOwner == "yes"){
	$status_ui = '<textarea id="statustext" onkeyup="statusMax(this,250)" placeholder="Nog wat nieuws te melden '.$u.'?"></textarea>';
	$status_ui .= '<button id="statusBtn" onclick="postToStatus(\'status_post\',\'a\',\''.$u.'\',\'statustext\')">Plaats bericht</button><button id="addImg" onclick="addImage">Foto bijvoegen</button>';
} else if($isFriend == true && $log_username != $u){
	$status_ui = '<textarea id="statustext" onkeyup="statusMax(this,250)" placeholder="Hoi '.$log_username.', schrijf een bericht aan '.$u.'"></textarea>';
	$status_ui .= '<button id="statusBtn" onclick="postToStatus(\'status_post\',\'c\',\''.$u.'\',\'statustext\')">Plaats bericht</button><button id="addImg" onclick="addImage">Foto bijvoegen</button>';
}
?>