function friendToggle(type,user,elem){
	var conf = confirm("Kies Ok om verzoek te versturen");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = 'even geduld aub ...';
	var ajax = ajaxObj("POST", "parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = 'Vrienden verzoek verstuurd';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\',\'friendBtn\')">Voeg toe als vriend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = 'Probeer het later nog eens';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
function blockToggle(type,blockee,elem){
	var conf = confirm("Kies OK om deze gebruiker te blokkeren.");
	if(conf != true){
		return false;
	}
	var elem = document.getElementById(elem);
	elem.innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "parsers/block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $u; ?>\',\'blockBtn\')">Deblokkeer gebruiker</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $u; ?>\',\'blockBtn\')">Blokkeer gebruikerr</button>';
			} else {
				alert(ajax.responseText);
				elem.innerHTML = 'Probeer het later nog eens';
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}