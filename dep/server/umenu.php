<?php
require_once('main.conf.php');
if(isset($_COOKIE['UID1'])){
	$key = mysqli_real_escape_string($jr,$_COOKIE['sk']);
	$tr = validate($_COOKIE['UID1'],$_COOKIE['sk'],$jr);
}
else if(isset($_SESSION['UID1'])){
	$tr = validate($_SESSION['UID1'],$_SESSION['sk'],$jr);
	$key = mysqli_real_escape_string($jr,$_SESSION['sk']);
}
else{
	$tr = false;
}
if($tr){
	$f = redef("query","select*from user where sessionKey = '$key'",$jr,0);
	while($cv = redef("fetch",$f,$jr,0)){
		$fullname = $cv['fulln'];
		$userID = $cv['id'];
		$username = $cv['username'];
		$phone = $cv['phone'];
		$email = $cv['email'];
		$gender = $cv['gender'];
		$address = $cv['address'];
	}
	$namesSplit = explode(' ',$fullname);
}
?>
<div id="stow" align="center"></div>
<div class="user-nav">
      <div class="container">
        <ul class="user-list">
        <li><a href="index"><i class="fa fa-home"></i> Home </a></li><li><span class="separator">|</span></li>
          <?php echo ($tr)? '<li><a href="cart"><i class="fa fa-user"></i> '.$fullname.'</a></li><li><span class="separator">|</span></li>' : '' ?>
          
          <?php echo (!$tr)? '<li><a href="register"><i class="fa fa-lock"></i> Sign Up</a></li><li><span class="separator">|</span></li>' : ''?>
          
          <?php echo (!$tr)? '<li><a href="signin"><i class="fa fa-key"></i> Log In</a></li><li><span class="separator">|</span></li>' : '' ?>
          
          <?php echo ($tr)? '<li><a href="destroy"><i class="fa fa-sign-out"></i> Sign out</a></li>' : '' ?>
        </ul>
      </div>
     </div>
