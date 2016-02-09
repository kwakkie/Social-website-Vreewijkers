<?php 
$coverpic = "";
$sql = "SELECT filename FROM photos WHERE user='$u' ORDER BY RAND() LIMIT 1";
$query = mysql_query($sql);
if(mysql_num_rows($query) > 0){
	$row = mysql_fetch_row($query);
	$filename = $row[0];
	$coverpic = '<img src="user/'.$u.'/'.$filename.'" alt="pic">';
}
?>