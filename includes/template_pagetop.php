<?php
$envelope = '<img src="images/note_still.jpg" width="20" height="20" alt="Notes" title="This envelope is for logged in members">';
$loginLink = '<a href="login.php">Inloggen</a> &nbsp; | &nbsp; <a href="index.php">Registreren</a>';
if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_row($query);
	$notescheck = $row[0];
	$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
    if ($numrows == 0) {
		$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/note_blue.jpg" width="20" height="20" alt="Notes"></a>';
    } else {
		$envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/note_red.jpg" width="20" height="20" alt="Notes"></a>';
	}
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; | &nbsp; <a href="logout.php">Log Out</a>';
}
?>
<script>
        var search = ajaxObj("POST", "includes/search.php");
        search.onreadystatechange = function() {
        if(ajaxReturn(search) == true) {
            if (search.responseText !='Not_found') {
                var redirect = search.responseText;
                location.href = "http://www.vreewijkers.nl/social/user.php?u="+redirect;
                } else {
                    alert("Sorry, deze gebruikersnaam is niet gevonden");
                    window.location.reload();
                }
            }
        }
        function ajax_search() {
            var namesearch = _("name").value;
            search.send("name="+namesearch);
        }
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73429526-2', 'auto');
  ga('send', 'pageview');

</script>    
<div id="pageTop">
	<div id="pageTopWrap">
		<div id="pageTopRest">
        	<div id="menu1">
            <?php echo $envelope; ?> &nbsp; &nbsp; <?php echo $loginLink; ?>
            </div>
            <div id="menu2">
            	<a href="http://www.vreewijkers.nl/social"><img src="images/HomeButton.png" alt="Home" title="Vreewijk Social Homepage"></a>
                <div id="search"><input type="text" id="name" name="name" placeholder="Zoek personen">&nbsp<input type="submit" value=" Zoeken " onCLick="javascript:ajax_search();"></button>
                </div>
            </div>
        </div>
   </div>
</div>