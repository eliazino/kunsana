<?php
require_once('defines.php');
require_once('main.conf.php');
require_once('functions.php');
if($_POST['m']){
	$m = mysqli_real_escape_string($jr,$_POST['m']);
	$d = redef("query","select*from cities where states = '$m'",$jr,0);
	$txt = '<select name="city" id="select4" class="form form-control">';
	if(redef("mCount",$d,$jr,0) < 1){
		$txt = $txt."<option>Not available</option>";
	}
	else{
		while ($bull= redef("fetch",$d,$jr,0)){
			$txt = $txt."<option>".$bull['city']."</option>";
		}
	}
	$txt = $txt."</select>";
	echo $txt;
}
?>