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
	header("location:index");
}
else{
	if(isset($_POST['yin'])){
		$fullname = mysqli_real_escape_string($jr,$_POST['fullname']);
		$user = mysqli_real_escape_string($jr,trim($_POST['username']));
		$pw = mysqli_real_escape_string($jr,$_POST['p1']);
		$email = mysqli_real_escape_string($jr,$_POST['email']);
		$gender = mysqli_real_escape_string($jr, $_POST['gender']);
		$phone = mysqli_real_escape_string($jr, $_POST['phone']);
		$err = 0;
		if($_POST['p1'] !== $_POST['p2']){ $message = eM('Passwords do not match'); $err++; }
		if(count(explode(' ', $fullname)) < 2){ $message = eM('Full name probably contain only one name, at least a Firstname and a Lastname are expected'); $err++; }
		if(strlen($pw) < 6){ $message = eM('Your password cannot be less than 6 characters long'); $err++; }
		if(strlen($user) < 5){$message = eM('Your username cannot be less than 6 characters long'); $err++; }
		if(strlen($phone) < 11 or !is_numeric($phone)){$message = eM('Your phone number should be all numeric and 11 characters long'); $err++; }
		if($err < 1){
			$pw = Mcrypt($pw);
			$t = redef("query","select*from user where username = '$user'",$jr,0);
			if(redef("mCount",$t,$jr,0) < 1){
				$t = redef("query","select*from user where email = '$email'",$jr,0);
				if(redef("mCount",$t,$jr,0) < 1){
					$ins = redef("query","insert into user (fulln, email, username, passwo, gender, phone) values('$fullname' ,'$email', '$user','$pw', '$gender', '$phone')",$jr,0);
					if($ins){
						$cmail = mCrypt($email);
						$sessionID = mCrypt(rand(9999,100000000));
						$due = time() + 86400*7;
						$r = redef("query","insert into session_tab (sessionStr,user,due) values ('$sessionID' ,'$cmail',$due)",$jr,0);
						$r = redef("query","update user set sessionkey = '$sessionID' where email = '$email'",$jr,0);
						$_SESSION['UID1'] = $cmail;
						$_SESSION['sk'] = $sessionID;
						echo"<script>
							location.replace('index');
						</script>";
					}
					else{
						$message = eM('Sorry, could not complete the request, please try again');
					}
				}
				else{
					$message = eM('Sorry, the email entered is taken');
				}
			}
			else{
				$message = eM('Please select another Username, it has been taken by another user');
			}
		}		
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>user registration</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="dep/css/bootstrap.min.css" rel="stylesheet">
		<link href="dep/style.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="dep/css/owl.carousel.css">
		<link rel="stylesheet" href="dep/css/owl.theme.default.css">
		<link rel="stylesheet" href="dep/css/font-awesome.min.css">
		<link rel="stylesheet" href="dep/css/fonts.css">
	</head>

	<body class="for_user_reg">
		<?php require_once('dep/server/umenu.php') ?>
		<div class="main">        
			<div class="container">
            <form name="sig" method="post" action="signin" style="display:none" id="sform">
            <input type="hidden" name="email" value="<?php echo $_POST['username'] ?>">
            <input type="password" style="display:none" name="password" value="<?php echo $_POST['p1'] ?>">
            </form>
				<form name="register" action="register" method="post">
				<div class="user_reg">
					<div class="head">
						<span id="b2">sign up</span><br>
						<span id="b3"><a href="artist-1">Or start selling as an artist</a></span>
					</div>
					<div class="body">
						<div class="details_input">
							<div class="firstname">
								<header>Your full name</header>
								<input type="text" name="fullname" value="<?php echo $_POST['fullname'] ?>" id="text" placeholder="full name" required>
							</div>
					    <div class="firstname" style="padding-bottom:15px;">
							  <header>Gender</header>
							  <select name="gender" class="form form-control" style="outline:none">
                              	<option value="Female">Female</option>
                                <option value="Male">Male</option>
                              </select>
						  </div>
							<div class="username">
						    <header>username</header>
								<input type="text" id="text" value="<?php echo $_POST['username'] ?>" name="username" placeholder="username" required>
							</div>
							<div class="username">
							  <header>Phone Number</header>
							  <input type="text" id="text" value="<?php echo $_POST['phone'] ?>" name="phone" placeholder="Phone Number" required>
						  </div>
							<div class="email">
						    <header>email</header>
								<input type="text" id="text" value="<?php echo $_POST['email'] ?>" name="email" placeholder="email" required>
							</div>
							<div class="password">
								<header>password</header>
								<input type="password" id="text" name="p1" placeholder="password" required>
							</div>
							<div class="confirm_password">
								<header>confirm password</header>
								<input type="password" id="text" name="p2" placeholder="re-type password" required>
							</div>
						</div>
						<div class="agree_to_terms">
							<input id="agree" name="agree" type="checkbox" required><label for="agree">I agree to the <a href="terms">Terms of Service</a></label>
                            <input type="hidden" value="ying" name="yin" >
						</div>
						<div class="button hand">
							<a onClick="document.getElementById('sig').click()">register<i class="icon-enter"></i></a>
                            <button style="display:none" id="sig"></button>
						</div>
						<div class="for_members">
							Already a member? <a href="signin"> Login Here</a>
						</div>
					</div>
				</div>
                </form>
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
