<?php
session_start();
require_once('dep/server/defines.php');
require_once('dep/server/main.conf.php');
require_once('dep/server/functions.php');
if(isset($_COOKIE['UID1'])){
	$tr = validate($_COOKIE['UID1'],$_COOKIE['sk'],$jr);
}
else if(isset($_SESSION['UID1'])){
	$tr = validate($_SESSION['UID1'],$_SESSION['sk'],$jr);
}
else{
	$tr = false;
}
if($tr){
	header("location:profile");
}
else{
	if(isset($_POST['email']) and isset($_POST['password'])){
		$email = mysqli_real_escape_string($jr,$_POST['email']);
		$pass = Mcrypt($_POST['password']);
		$remember = $_POST['remember'];
		$t = redef("query","select*from user where (email = '$email' or username= '$email') and passwo = '$pass'",$jr,0);
		$c = redef("mCount",$t,$jr,0);
		if($c > 0){
			$cmail = mCrypt($email);
			$i = redef("query","select*from session_tab where user = '$cmail'",$jr,0);
			if(redef("mCount",$i,$jr,0) > 0){
				while($bi = redef("fetch",$i,$jr,0)){
					$due = $bi['due'];
					if($due <= time()){
						$sessionID = $bi['sessionStr'];
						//$r = redef("query","update session_tab set sessionStr = '$sessionID' where user = '$email'",$jr,0);
					}
					else{
						$sessionID = mCrypt(rand(9999,100000000));
						$cmail = mCrypt($email);
						$r = redef("query","update session_tab set sessionStr = '$sessionID' where user = '$cmail'",$jr,0);
						$r = redef("query","update user set sessionkey = '$sessionID' where email = '$email'",$jr,0);
					}
				}
			}
			else{
				$sessionID = mCrypt(rand(9999,100000000));
				$due = time() + 86400*7;
				$r = redef("query","insert into session_tab (sessionStr,user,due) values ('$sessionID' ,'$cmail',$due)",$jr,0);
				$r = redef("query","update user set sessionkey = '$sessionID' where email = '$email'",$jr,0);
			}
			if(isset($remember)){
				header("location:i.php?a=".$sessionID."&b=".$cmail);
			}
			else{
				$_SESSION['UID1'] = $cmail;
				$_SESSION['sk'] = $sessionID;
				echo"<script>
					location.replace('index');
				</script>";
			}
		}
		else{
			$message = eM('The username and password does not match our record');
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>sign in</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="dep/css/bootstrap.min.css" rel="stylesheet">
		<link href="dep/style.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="dep/css/owl.carousel.css">
		<link rel="stylesheet" href="dep/css/owl.theme.default.css">
		<link rel="stylesheet" href="dep/css/font-awesome.min.css">
		<link rel="stylesheet" href="dep/css/fonts.css">
	</head>

	<body class="for_signin">
		<?php require_once('dep/server/umenu.php') ?>
		<div class="main"> 
			<div class="container">
				<div class="jumbo-carousel">
					<!--menus -->
				</div>
                
				<div class="sign_box">
					<div class="head"><span style="font-weight:bold; font-size:40px">sign in to buy</span><br>
<a href="artist-2" style="font-size:14px">Or sell your artwork</a></div>
                    
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="signin">
					<div class="body">
						<div class="details_input">
							<div class="email">
							  <input type="text" id="text2" placeholder="email or username" name="email" required>
						  </div>
							<div class="password">
                            <input type="password" id="text" placeholder="***" name="password" required>
                            </div>
                             <label style="color:#FFF"><input type="checkbox" style="width:auto" name="remember" value="remember" /> Remember me</label>
						</div>
						<div class="button hand">
							<a onClick="document.getElementById('sig').click()">sign in <i class="icon-enter"></i></a>
                            <button style="display:none" id="sig"></button>
					  </div>
						<div class="forgot_pass">
							<a href="#">forgot your password?</a> | <a href="register">Not yet a member?</a>
						</div>
					</div>
                    </form>
				</div>
				<footer>
					<div class="container">
						<div class="row">
							<div class="col-sm-4 col-md-4 copyright"> 
								Copyright &copy; 2016 kunsana Inc
							</div>
							<div class="col-sm-4 col-md-4 social_links">
								<ul>
									<li><a href="#"><i class="fa fa-facebook fa-2x"></i></a></li>
									<li><a href="#"><i class="fa fa-instagram fa-2x"></i></a></li>
									<li><a href="#"><i class="fa fa-twitter fa-2x"></i></a></li>
								</ul>
							</div>
							<div class="col-sm-4 col-md-4 payment">
								<ul>
									<li><a href="#"><i class="icon-cc-mastercard"></i></a></li>
									<li><a href="#"><i class="icon-paypal"></i></a></li>
									<li><a href="#"><i class="icon-visa"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
				</footer>

			</div>

		</div>
		<script src="dep/js/jquery-latest.min.js"></script>
		<script src="dep/js/bootstrap.min.js"></script>
        <script src="dep/js/script.js"></script>
        <?php 
		if($message){
			echo "<script type=\"text/javascript\">mCra(\"".$message."\");</script>";
		}
		?>
	</body>
</html>
