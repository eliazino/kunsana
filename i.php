<?php
session_start();
require_once('dep/server/defines.php');
require_once('dep/server/main.conf.php');
require_once('dep/server/functions.php');
if(isset($_GET['a']) and $_GET['b']){
	$a = mysqli_real_escape_string($jr,$_GET['a']);
	$b = mysqli_real_escape_string($jr,$_GET['b']);
	$villi = redef("query","select*from session_tab where sessionStr = '$a' and user = '$b'",$jr,0);
	if(redef("mCount",$villi,$jr,0) < 0){
		header("location:index");
	}
	else{
		setcookie("UID1",$b,time()+ 86400*7,"/","http://localhost/artnaija");
		setcookie("sk",$a,time()+ 86400*7,"/","http://localhost/artnaija");
	}
	$tap = 'index';
}
else if(isset($_GET['a']) and isset($_GET['t'])){
	$a = mysqli_real_escape_string($jr,$_GET['a']);
	$t = mysqli_real_escape_string($jr,$_GET['t']);
	setcookie("UID2",$t,time()+ 86400*7,"/","http://localhost/artnaija");
	setcookie("sk2",$a,time()+ 86400*7,"/","http://localhost/artnaija");
	$tap = 'dashboard';
}
?>
<body onLoad="location.replace('index.php')">
 <div style="padding-top:30px;" align="center"><img src="dep/img/M.gif" alt="Loading" ><br>
<a href="<?php echo $tap ?>">click if stucked here</a></div>
</body>