function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("OK om te '"+action+"'.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Verzoek geaccepteerd!</b><br />Jullie zijn nu vrienden";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Verzoek geweigerd</b><br />Je wilt geen vrienden worden met deze gebruiker";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}