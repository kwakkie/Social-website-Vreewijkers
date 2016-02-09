<?php 
include_once("includes/db_connect.php"); 
include_once("includes/check_login_status.php");
require_once("includes/ownerstatus.php");
require_once("includes/replyhandler.php");
?>
<script>
function postToStatus(action,type,user,ta){
	var data = _(ta).value;
	if(data == ""){
		alert("Lege berichten zijn niet toegestaan");
		return false;
	}
	_("statusBtn").disabled = true;
	var ajax = ajaxObj("POST", "parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "post_ok"){
				var sid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				var currentHTML = _("statusarea").innerHTML;
				_("statusarea").innerHTML = '<div id="status_'
                +sid+'" class="status_boxes"><div><b>Zojuist geplaatst:</b> <span id="sdb_'
                +sid+'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''
                +sid+'\',\'status_'
                +sid+'\');" title="VERWIJDER DIT BERICHT EN ALLE REACTIES">verwijder bericht</a></span><br />'
                +data+'</div></div><textarea id="replytext_'
                +sid+'" class="replytext" onkeyup="statusMax(this,250)" placeholder="reageer hier"></textarea><button id="replyBtn_'
                +sid+'" onclick="replyToStatus('
                +sid+',\'<?php echo $u; ?>\',\'replytext_'
                +sid+'\',this)">Reageer</button>'+currentHTML;
				_("statusBtn").disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action="+action+"&type="+type+"&user="+user+"&data="+data);
}
function replyToStatus(sid,user,ta,btn){
	var data = _(ta).value;
	if(data == ""){
		alert("Lege berichten zijn niet toegestaan");
		return false;
	}
	_("replyBtn_"+sid).disabled = true;
	var ajax = ajaxObj("POST", "parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "reply_ok"){
				var rid = datArray[1];
				data = data.replace(/</g,"&lt;")
                .replace(/>/g,"&gt;").replace(/\n/g,"<br />")
                .replace(/\r/g,"<br />");
				_("status_"+sid).innerHTML += '<div id="reply_'
                +rid+'" class="reply_boxes"><div><b>Je hebt gereageerd:</b><span id="srdb_'
                +rid+'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''
                +rid+'\',\'reply_'
                +rid+'\');" title="VERWIJDER DEZE REACTIE">verwijder reactie</a></span><br />'
                +data+'</div></div>';
				_("replyBtn_"+sid).disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=status_reply&sid="+sid+"&user="+user+"&data="+data);
}
function deleteStatus(statusid,statusbox){
	var conf = confirm("Klik op OK om dit bericht met alle reacties te verwijderen");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(statusbox).style.display = 'none';
				_("replytext_"+statusid).style.display = 'none';
				_("replyBtn_"+statusid).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_status&statusid="+statusid);
}
function deleteReply(replyid,replybox){
	var conf = confirm("Klik op OK om deze reactie te verwijderen");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(replybox).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_reply&replyid="+replyid);
}
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum aantal tekens bereikt");
		field.value = field.value.substring(0, maxlimit);
	}
}
</script>
<div id="statusui">
  <?php echo $status_ui; ?>
</div>
<div id="statusarea">
  <?php echo $statuslist; ?>
</div>