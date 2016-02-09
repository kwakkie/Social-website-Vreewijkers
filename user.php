<?php
include_once("includes/check_login_status.php");
include_once("includes/db_connect.php");
require_once("includes/getuser.php");
require_once("includes/friendblockgetandset.php");
require_once("includes/friendshtml.php");
require_once("includes/coverpicture.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/profile.css">
<link rel="stylesheet" href="css/status.css">

<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script src="js/friendAndBlockToggle.js"></script>
</head>
<body>
<?php include_once("includes/template_pagetop.php"); ?>
<div id="pageMiddle">
   
    <div id="userarea" style="background:url(<?php echo $background; ?>);">
            <div id="background_pic_box" >    
            <?php echo $background_btn; ?>
            <?php echo $background_form; ?>
            </div><!--END background_pic_box-->
          <div id="profile_pic_box" >
            <?php echo $profile_pic_btn; ?>
            <?php echo $avatar_form; ?>
            <?php echo $profile_pic; ?>
                <div id="nameuser">
                    <h1><?php echo $u; ?></h1>
                </div><!--END nameuser-->
                <div id="profile_buttons">
                    <p><span id="friendBtn"><?php echo $friend_button; ?>
                    <span id="blockBtn"><?php echo $block_button; ?></span></p>
                </div><!--END profile_buttons-->
            </div><!--END profile_pic_box-->
        </div><!--END userarea-->
    <div id="messagearea">        
        <div id="personalarea">
            
            <div id="userdata">
            <div id="boxtext">
              <p>Laatst gezien : <?php echo $lastsession; ?></p>
            </div><!--END boxtext-->
             <div id="boxtext"></span> 
                <?php echo $u." heeft ".$friend_count; ?>
                <?php if ($friend_count == 1){ echo" vriend";} 
                    else 
                   {echo" vrienden";}?>
                <?php echo $friends_view_all_link; ?>        
             </div><!--END boxtext-->
                <p><?php echo $friendsHTML; ?></p>
                    <div id="boxtext">
                    Foto's van <?php echo $u ?>
                    </div>
                    <div id="photo_showcase" onclick="window.location = 'photos.php?u=<?php echo $u; ?>';" title="view <?php echo $u; ?>&#39;s photo galleries">
                    <?php echo $coverpic; ?>
                    </div><!--END photo_showcase-->  
            </div><!--END userdata-->
         </div><!--END personalarea-->
          <?php include_once("includes/template_status.php"); ?>
     </div><!--END messagearea-->
     
</div><!--END pageMiddle-->

</body>
</html>
