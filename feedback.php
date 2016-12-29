<?php
session_start();
require_once('dep/server/defines.php');
require_once('dep/server/main.conf.php');
require_once('dep/server/functions.php');
if(isset($_POST['email']) and isset($_POST['name']) and isset($_POST['body'])){
	list($userName, $mailDomain) = explode("@", $_POST['email']);
	$right = checkdnsrr($mailDomain, "MX");
	if($right){
		$sender = "no-reply@kunsana.com";
		$subject = "Your feedback counts";
		$to = $_POST['email'];
		$message = "Thank you for your feedback. We have received your feedback and will work on it. We will reply to your feedback via this email if applicable. <br/> Regards";
		if(tryMail($sender, $message, $to, $subject)){
			$to = "info@kunsana.com";
			$subject = $_POST['name'];
			$sender = $_POST['email'];
			$message = $_POST['body'].'<br>'.$_POST['email'];
			if(tryMail($sender, $message, $to, $subject)){
				$returnM = sM("Your feedback has been succesfully sent");
			}else{
				$returnM = eM("Sorry, an error occured while sending the feedback");
			}
		}
		else{
			$returnM = eM("Sorry, error occured while contacting your email. email must be valid");
		}
	}
	else{
		$returnM = eM("Sorry, error occured while contacting your email. email must be valid");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
    <link rel="stylesheet" href="dep/css/fonts.css">
  </head>

  <body class="for_feedback">
    <div class="jumbo-carousel">
      <div class="container">
        <?php require_once('dep/server/gmenu.php') ?>
      </div>
    </div>
    <div class="feedback">
        <div class="container">
          <header>leave us a feedback</header>
          <div class="form_instruction">
          <p>Use the form below to send us your comments or report any problems you experienced finding information on our website. We will be able to improve our site much faster with your help.</p>
          <h5>Please fill this form appropriately</h5>
          
          </div>
          <form action="feedback" method="post" name="feedback">
          <div class="form_body">
            <div class="name"><input type="text" value="<?php echo $_POST['name'] ?>" id="text" placeholder="Enter name" name="name"></div>

            <div class="email"><input type="text" id="text" value="<?php echo $_POST['email'] ?>" placeholder="Enter email" name="email"></div>
            <div class="your_msg">
              <textarea rows="10" id="text" placeholder="Enter your message" name="body"><?php echo $_POST['body'] ?></textarea>
            </div>
            <div class="button">
            <button name="feedback" id="feedback" class="hidden"></button>
              <a href="javascript:void(0)" onClick="document.getElementById('feedback').click()">send feedback</a>
            </div>
          </div>
          </form>
        </div>
      </div>


    <?php require_once('dep/server/footer.php') ?>
	<script src="dep/js/jquery-latest.min.js"></script>
    <script src="dep/js/bootstrap.min.js"></script>
    <script src="dep/js/script.js"></script>
    <?php 
	if($returnM){
		echo "<script type=\"text/javascript\">mCra(\"".$returnM."\");</script>";
	}
		?>
  </body>
</html>