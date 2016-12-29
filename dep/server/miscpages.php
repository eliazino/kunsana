<?php
session_start();
require_once('defines.php');
require_once('main.conf.php');
require_once('functions.php');
if(isset($_COOKIE['UID1'])){
	$tr = validate($_COOKIE['UID1'],$_COOKIE['sk'],$jr);
	$key = mysqli_real_escape_string($jr,$_COOKIE['sk']);
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
	}
	
	if(isset($_POST['art_id']) and isset($_POST['user_id'])){
		$user = mysqli_real_escape_string($jr,$_POST['user_id']);
		$item = mysqli_real_escape_string($jr,$_POST['art_id']);
		$tim = time();
		$ji = redef("query","select*from carts where user = '$user' and cartItem = '$item'", $jr,0);
		if(redef("mCount",$ji,$jr,0) > 0){
			echo "true".'#mkShfT'.wM('The item was already added to cart');
		}
		else{
			$iq = redef("query","insert into carts (user, cartItem, addDate) values ('$user', '$item', '$tim')",$jr,0);
			$ta = redef("query","select*from carts where user = '$user'",$jr,0);
			$counter = redef("mCount",$ta,$jr,0);
			if($iq){
				echo "true".'#mkShfT'.sM('The item was added to cart succesfully')."#mkShfT".$counter;
			}
			else{
				echo "false".'#mkShfT'.eM('The item could not be added at the moment')."#mkShfT".$counter;
			}
		}
	}
	
	if(isset($_POST['sever']) and isset($_POST['art_id'])){
		$artId = mysqli_real_escape_string($jr, $_POST['art_id']);
		$sql = "delete from carts where user = $userID and cartItem = $artId";
		$k = redef("query",$sql,$jr,0);
		$ta = redef("query","select*from carts where user = '$userID'",$jr,0);
		$counter = redef("mCount",$ta,$jr,0);
		if(redef("ar",$k,$jr,0) > 0){
			$nsql = "SELECT sum(price) as total from carts left join artslist on carts.cartItem = artslist.id where carts.user = $userID";
			$b = redef("query",$nsql,$jr,0);
			while($mi = redef("fetch",$b,$jr,0)){
				$total = $mi['total'];
			}
			$response = "true"."#mkShfT".$total."#mkShfT".pricer($total)."#mkShfT".sM("The selected art was removed!")."#mkShfT".$counter;
		}
		else{
			$response = "false"."#mkShfT null #mkShfT null #mkShfT".eM("Sorry, an error occured!")."#mkShfT".$counter;
		}
		echo $response;
	}
}
else{
	echo "false".'#mkShfT'.eM('Sorry, request require that you sign in again')."#mkShfT".$counter;
}
?>