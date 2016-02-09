<?php 
$sql = "SELECT * FROM status WHERE account_name='$u' AND type='a' OR account_name='$u' AND type='c' ORDER BY postdate DESC LIMIT 20";
$query = mysql_query($sql);
$statusnumrows = mysql_num_rows($query);
while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
	$statusid = $row["id"];
	$account_name = $row["account_name"];
	$author = $row["author"];
	$postdateraw = new DateTime($row["postdate"]);
    $postdate = $postdateraw->format('d/m/Y H:i');
    $image = $row["image"];
    $avatar = $row["avatar"];
	$data = $row["data"];
	$data = nl2br($data);
	$data = str_replace("&amp;","&",$data);
	$data = stripslashes($data);
    $authoravatar = "user/".$author."/".$avatar;
	$statusDeleteButton = '';
    
	if($author == $log_username || $account_name == $log_username ){
		$statusDeleteButton = '<span id="sdb_'
                             .$statusid.'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''
                             .$statusid.'\',\'status_'
                             .$statusid.'\');" title="VERWIJDER DIT BERICHT EN ALLE REACTIES">verwijder</a></span> &nbsp; &nbsp;';
	}
	// GATHER UP ANY STATUS REPLIES
	$status_replies = "";
	$query_replies = mysql_query("SELECT * FROM status WHERE osid='$statusid' AND type='b' ORDER BY postdate ASC");
	$replynumrows = mysql_num_rows($query_replies);
    if($replynumrows > 0){
        while ($row2 = mysql_fetch_array($query_replies, MYSQL_ASSOC)) {
			$statusreplyid = $row2["id"];
			$replyauthor = $row2["author"];
            $replyavatar = $row2["avatar"];
			$replydata = $row2["data"];
			$replydata = nl2br($replydata);
            $replypostdateraw = new DateTime($row2["postdate"]);
            $replypostdate = $replypostdateraw->format('d/m/Y H:i');
			$replydata = str_replace("&amp;","&",$replydata);
			$replydata = stripslashes($replydata);
			$replyDeleteButton = '';
            $replyavatar = "user/".$replyauthor."/".$replyavatar;
          
			if($replyauthor == $log_username || $account_name == $log_username ){
				$replyDeleteButton = '<span id="srdb_'
                                    .$statusreplyid.'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''
                                    .$statusreplyid.'\',\'reply_'
                                    .$statusreplyid.'\');" title="VERWIJDER DEZE REACTIE">verwijder</a></span>';
			}
			$status_replies .= '<div id="reply_'
                                .$statusreplyid.'" class="reply_boxes"><div><a href="user.php?u='
                                .$replyauthor.'"><img class="friendpics" src="'
                                .$replyavatar.'">'
                                .$replyauthor.'</a> '
                                .$replypostdate.':</b> '
                                .$replyDeleteButton.'<br />'
                                .$replydata.'</div></div>';
        }
    }
	$statuslist .= '<div id="status_'
                .$statusid.'" class="status_boxes"><div><b><a href="user.php?u='
                .$author.'"><img class="friendpics" src="'
                .$authoravatar.'">'
                .$author.'</a> '
                .$postdate.':</b> '
                .$statusDeleteButton.' <br />'
                .$data.'</div>'
                .$status_replies.'</div>';
	if($isFriend == true || $log_username == $u){
	                        $statuslist .= '<textarea id="replytext_'
                            .$statusid.'" class="replytext" onkeyup="statusMax(this,250)" placeholder="Reageer hier op"></textarea><button id="replyBtn_'
                            .$statusid.'" onclick="replyToStatus('
                            .$statusid.',\''
                            .$u.'\',\'replytext_'
                            .$statusid.'\',this)">Reageer</button>
                            <button id="addImg" onclick="addImage">Foto bijvoegen</button>';
	}
}
?>