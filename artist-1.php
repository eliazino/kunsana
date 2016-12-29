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
  if(isset($_POST['yin'])){
	  $user = mysqli_real_escape_string($jr,trim($_POST['uname']));
	  $email = mysqli_real_escape_string($jr,$_POST['mail']);
	  $phone = mysqli_real_escape_string($jr, $_POST['phone']);
	  $fpw = mysqli_real_escape_string($jr,$_POST['fpwd']);
	  $spw = mysqli_real_escape_string($jr,$_POST['spwd']);
	  $err = 0;
	  if(isset($_POST['agree'])){
		  if($_POST['fpwd'] !== $_POST['spwd']){ $message = eM('Passwords do not match'); $err++; }
		  if(strlen($fpw) < 6){ $message = eM('Your password cannot be less than 6 characters long'); $err++; }
		  if(strlen($user) < 5){$message = eM('Your username cannot be less than 6 characters long'); $err++; }
		  if(strlen($phone) < 11 or !is_numeric($phone)){$message = eM('Your phone number should be all numeric and 11 characters long'); $err++; }
		  if($err < 1){
			$pw = Mcrypt($fpw);
			$t = redef("query","select*from artists where username = '$user'",$jr,0);
			if(redef("mCount",$t,$jr,0) < 1){
				$t = redef("query","select*from artists where email = '$email'",$jr,0);
				if(redef("mCount",$t,$jr,0) < 1){
					$ins = redef("query","insert into artists (email, username, passw, phone) values('$email', '$user','$pw', '$phone')",$jr,0);
					if($ins){
						$sessionID = mCrypt(rand(9999,100000000));
						$due = time() + 86400*7;
						$r = redef("query","update artists set sessionkey = '$sessionID' where email = '$email'",$jr,0);
						$_SESSION['UID2'] = $cmail;
						$_SESSION['sk2'] = $sessionID;
						echo"<script>
							location.replace('dashboard');
						</script>";
					}
					else{
						$message = eM('Sorry, An error occured'); $err++; 
					}
				}
				else{
					$message = eM('Sorry, the email is not available'); $err++; 
				}
			}
			else{
				$message = eM('Sorry, the username is not available'); $err++;
			}
		  }
	  }
	  else{
		  $message = eM('Your must agree with our terms and condition'); $err++;
	  }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Artist account</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="dep/css/bootstrap.min.css" rel="stylesheet">
		<link href="dep/style.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="dep/css/owl.carousel.css">
		<link rel="stylesheet" href="dep/css/owl.theme.default.css">
		<link rel="stylesheet" href="dep/css/font-awesome.min.css">
		<link rel="stylesheet" href="dep/css/fonts.css">
	</head>

	<body class="for_artist_reg1">
		<?php require_once('dep/server/umenu.php') ?>
		<div class="main"> 
			<div class="container">
				<div class="jumbo-carousel">
					<?php require_once('dep/server/gmenu.php') ?>
				</div>
				<div class="user_reg">
					<div class="head">
						<span id="b1">artist Registration</span><br>

						<span id="b3">or register as a <a href="register">regular user</a></span>
					</div>
					<div class="body">
                    <form id="register" method="post" action="artist-1" enctype="multipart/form-data">
						<div class="details_input">
                        <h3 style="color:#FFF">Create Log in details</h3>
							<div class="username">
								<header>e-mail <span style="text-transform:lowercase">(must be valid)</span> <span style="color:red">*</span></header>
								<input type="email" required="required" name="mail" id="textfield8" value="<?php echo $_POST['mail']; ?>" class="form form-control" style="width:100%; height:48px;" />
							</div>
							<div class="email">
								<header>Phone Number <span style="font-size:11px; text-transform:lowercase">(enter format 08123456789)</span></header>
								<input type="number" required="required" name="phone" id="textfield8" value="<?php echo $_POST['phone']; ?>" class="form form-control" style="width:100%; height:48px;" />
							</div>
							<div class="phone">
								<header>Choose Username <span style="color:red">*</span></header>
								<input type="text" name="uname" id="text" value="<?php echo $_POST['uname']; ?>" onKeyUp="check(this.id,'un',5)" class="form form-control" style="width:100%; height:48px;" required="required"/>
							</div>
							
							<div class="password">
								<header>Choose Password <span style="color:red">*</span></header>
								<input type="password" name="fpwd" id="textfield10"  onkeyup="check(this.id,'fpw',6)" class="form form-control" style="width:100%; height:48px;" required="required"/>
							</div>
							<div class="confirm_password">
								<header>Confirm Password<span style="color:red"> *</span></header>
								<input type="password" name="spwd" id="textfield11" onKeyUp="check(this.id,'spw',7)" class="form form-control" style="width:100%; height:48px;" required="required"/>
							</div>
						</div>
                        
						<div class="agree_to_terms">
							<input id="agree" name="agree" type="checkbox" required><label for="agree">I agree to the <a href="#">Terms of Service</a></label>
						</div>
						<div class="button">
                        <button id="reg" style="display:none">button</button>
							<a href="javascript:void(0)" onClick="document.getElementById('register').submit()">continue<i class="icon-enter"></i></a>
						</div>
                        <input type="hidden" name="yin" value="yeen">
                        </form>
						<div class="for_members">
							Already a member? <a href="artist-2"> Login Here</a>
						</div>
					</div>
				</div>

				<footer>
					<div class="container">
						<div class="row">
							<div class="col-sm-4 col-md-4 copyright"> 
								Copyright &copy; 2016 kunsana.com
							</div>
							<div class="col-sm-4 col-md-4 social_links">
								<ul>
									<<li><a href="#"><i class="fa fa-facebook fa-2x"></i></a></li>
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
