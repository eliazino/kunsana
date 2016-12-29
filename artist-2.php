<?php
session_start();
require_once('dep/server/defines.php');
require_once('dep/server/main.conf.php');
require_once('dep/server/functions.php');
if(isset($_COOKIE['UID2'])){
	$tr = validate2($_COOKIE['UID2'],$_COOKIE['sk2'],$jr);
}
else if(isset($_SESSION['UID2'])){
	$tr = validate2($_SESSION['UID2'],$_SESSION['sk2'],$jr);
}
else{
	$tr = false;
}
if($tr){
	header("location:dashboard");
}
else{
	if(isset($_POST['email']) and isset($_POST['password'])){
		$email = mysqli_real_escape_string($jr,$_POST['email']);
		$pass = Mcrypt($_POST['password']);
		$remember = $_POST['remember'];
		$t = redef("query","select*from artists where username= '$email' and passw = '$pass'",$jr,0);
		$c = redef("mCount",$t,$jr,0);
		if($c > 0){
			$sessionID = mCrypt(rand(9999,100000000));
			$due = time() + 86400*7;
			$r = redef("query","update artists set sessionkey = '$sessionID' where username = '$email'",$jr,0);
			if(isset($remember)){
				header("location:i.php?a=".$sessionID."&t=".$due);
			}
			else{
				$_SESSION['UID2'] = $cmail;
				$_SESSION['sk2'] = $sessionID;
				echo"<script>
					location.replace('dashboard');
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
					<div class="head">Sign in as artist</div>
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
							<a href="#">forgot your password?</a> | <a href="artist-1">Not registered?</a>
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
