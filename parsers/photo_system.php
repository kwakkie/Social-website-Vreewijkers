<?php
include_once("../includes/check_login_status.php");
include_once("../includes/db_connect.php");
?><?php 
if (isset($_POST["show"]) && $_POST["show"] == "galpics"){
	$picstring = "";
	$gallery = preg_replace('#[^a-z 0-9,]#i', '', $_POST["gallery"]);
	$user = preg_replace('#[^a-z0-9]#i', '', $_POST["user"]);
	$sql = "SELECT * FROM photos WHERE user='$user' AND gallery='$gallery' ORDER BY uploaddate ASC";
	$query = mysql_query($sql);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$id = $row["id"];
		$filename = $row["filename"];
		$description = $row["description"];
		$uploaddate = $row["uploaddate"];
		$picstring .= "$id|$filename|$description|$uploaddate|||";
    }
	mysql_close();
	$picstring = trim($picstring, "|||");
	echo $picstring;
	exit();
}
?><?php 
if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
	$fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
	$fileType = $_FILES["avatar"]["type"];
	$fileSize = $_FILES["avatar"]["size"];
	$fileErrorMsg = $_FILES["avatar"]["error"];
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	list($width, $height) = getimagesize($fileTmpLoc);
	if($width < 10 || $height < 10){
		header("location: ../message.php?msg=ERROR: Deze afbeelding is te klein");
        exit();	
	}
	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
	if($fileSize > 1048576) {
		header("location: ../message.php?msg=ERROR: Deze afbeelding is groter dan 1mb");
		exit();	
	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
		header("location: ../message.php?msg=ERROR: Deze afbeelding is geen jpg, gif of png type");
		exit();
	} else if ($fileErrorMsg == 1) {
		header("location: ../message.php?msg=ERROR: An unknown error occurred");
		exit();
	}
	$sql = "SELECT avatar FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_row($query);
	$avatar = $row[0];
	if($avatar != ""){
		$picurl = "../user/$log_username/$avatar"; 
	    if (file_exists($picurl)) { unlink($picurl); }
        if(!file_exists($picurl)){
                  mkdir($picurl, 0777,true);
               }
	}
	$moveResult = move_uploaded_file($fileTmpLoc, "../user/$log_username/$db_file_name");
	if ($moveResult != true) {
		header("location: ../message.php?msg=ERROR: File upload mislukt");
		exit();
	}
	include_once("../includes/image_resize.php");
	$target_file = "../user/$log_username/$db_file_name";
	$resized_file = "../user/$log_username/$db_file_name";
	$wmax = 200;
	$hmax = 300;
	img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	$sql = "UPDATE users SET avatar='$db_file_name' WHERE username='$log_username' LIMIT 1";
	$query = mysql_query($sql);
    $sql = "UPDATE status SET avatar='$db_file_name' WHERE author='$log_username' ";
    $query = mysql_query($sql);
	mysql_close();
	header("location: ../user.php?u=$log_username");
	exit();
}
?>
<?php 
if (isset($_FILES["background"]["name"]) && $_FILES["background"]["tmp_name"] != ""){
	$fileName = $_FILES["background"]["name"];
    $fileTmpLoc = $_FILES["background"]["tmp_name"];
	$fileType = $_FILES["background"]["type"];
	$fileSize = $_FILES["background"]["size"];
	$fileErrorMsg = $_FILES["background"]["error"];
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	list($width, $height) = getimagesize($fileTmpLoc);
	if($width < 10 || $height < 10){
		header("location: ../message.php?msg=ERROR: Deze afbeelding is te klein");
        exit();	
	}
	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
	if($fileSize > 1048576) {
		header("location: ../message.php?msg=ERROR: De afbeelding is groter dan 1mb");
		exit();	
	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
		header("location: ../message.php?msg=ERROR: De afbeelding is geen jpg, gif of png type");
		exit();
	} else if ($fileErrorMsg == 1) {
		header("location: ../message.php?msg=ERROR: An unknown error occurred");
		exit();
	}
	$sql = "SELECT backgroundpicture FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysql_query($sql);
	$row = mysql_fetch_row($query);
	$background = $row[0];
	if($background != ""){
		$picurl = "../user/$log_username/$background"; 
	    if (file_exists($picurl)) { unlink($picurl); }
        if(!file_exists($picurl)){
                  mkdir($picurl, 0777,true);
               }
	}
	$moveResult = move_uploaded_file($fileTmpLoc, "../user/$log_username/$db_file_name");
	if ($moveResult != true) {
		header("location: ../message.php?msg=ERROR: Bestand versturen mislukt");
		exit();
	}
	include_once("../includes/image_resize.php");
	$target_file = "../user/$log_username/$db_file_name";
	$resized_file = "../user/$log_username/$db_file_name";
	$wmax = 1000;
	$hmax = 360;
	img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	$sql = "UPDATE users SET backgroundpicture='$db_file_name' WHERE username='$log_username' LIMIT 1";
	$query = mysql_query($sql);
	mysql_close();
	header("location: ../user.php?u=$log_username");
	exit();
}
?><?php 
if (isset($_FILES["photo"]["name"]) && isset($_POST["gallery"])){
	$sql = "SELECT COUNT(id) FROM photos WHERE user='$log_username'";
	$query = mysql_query($sql);
	$row = mysql_fetch_row($query);
	if($row[0] > 14){
		header("location: ../message.php?msg=Het maximum aantal afbeeldingen is bereikt");
        exit();	
	}
	$gallery = preg_replace('#[^a-z 0-9,]#i', '', $_POST["gallery"]);
	$fileName = $_FILES["photo"]["name"];
    $fileTmpLoc = $_FILES["photo"]["tmp_name"];
	$fileType = $_FILES["photo"]["type"];
	$fileSize = $_FILES["photo"]["size"];
	$fileErrorMsg = $_FILES["photo"]["error"];
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	$db_file_name = date("DMjGisY")."".rand(1000,9999).".".$fileExt; // WedFeb272120452013RAND.jpg
	list($width, $height) = getimagesize($fileTmpLoc);
	if($width < 10 || $height < 10){
		header("location: ../message.php?msg=ERROR: Deze afbeelding is te klein");
        exit();	
	}
	if($fileSize > 1048576) {
		header("location: ../message.php?msg=ERROR: Deze afbeelding is groter dan 1mb");
		exit();	
	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
		header("location: ../message.php?msg=ERROR: Deze afbeelding is geen jpg, gif of png type");
		exit();
	} else if ($fileErrorMsg == 1) {
		header("location: ../message.php?msg=ERROR: An unknown error occurred");
		exit();
	}
	$moveResult = move_uploaded_file($fileTmpLoc, "../user/$log_username/$db_file_name");
	if ($moveResult != true) {
		header("location: ../message.php?msg=ERROR: File upload failed");
		exit();
	}
	include_once("../includes/image_resize.php");
	$wmax = 800;
	$hmax = 600;
	if($width > $wmax || $height > $hmax){
		$target_file = "../user/$log_username/$db_file_name";
	    $resized_file = "../user/$log_username/$db_file_name";
		img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	}
	$sql = "INSERT INTO photos(user, gallery, filename, uploaddate) VALUES ('$log_username','$gallery','$db_file_name',now())";
	$query = mysql_query($sql);
	mysql_close();
	header("location: ../photos.php?u=$log_username");
	exit();
}
?><?php 
if (isset($_POST["delete"]) && $_POST["id"] != ""){
	$id = preg_replace('#[^0-9]#', '', $_POST["id"]);
	$query = mysql_query("SELECT user, filename FROM photos WHERE id='$id' LIMIT 1");
	$row = mysql_fetch_row($query);
    $user = $row[0];
	$filename = $row[1];
	if($user == $log_username){
		$picurl = "../user/$log_username/$filename"; 
	    if (file_exists($picurl)) {
			unlink($picurl);
			$sql = "DELETE FROM photos WHERE id='$id' LIMIT 1";
	        $query = mysql_query($sql);
		}
	}
	mysql_close();
	echo "deleted_ok";
	exit();
}
?>
