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
if(isset($_POST['loc'])){
	$categ = $_POST['cats'];
	$location = $_POST['loc'];
	$searchQ = $_POST['searchQ'];
	$sortBy = $_POST['sortBy'];
	$newIt = $_POST['newIt'];
	$viewAll = $_POST['viewAll'];
	$next = mysqli_real_escape_string($jr,$_POST['next']);
	$locatStr = ($location != "0")?  "loc = '".mysqli_real_escape_string($jr,$location)."' and " : ' ';
	if(sizeof($categ) > 0){
		$start = 0;
		$wstr = "and (";
		while($start < sizeof($categ)){
			if($start + 1 == sizeof($categ)){
				$wstr = $wstr." categ = '".mysqli_real_escape_string($jr,$categ[$start])."') ";
			}
			else{
				$wstr = $wstr." categ = '".mysqli_real_escape_string($jr,$categ[$start])."' or ";
			}
			$start++;
		}
	}
	/*if($newIt == 'true'){
		$sortStr = "order by id desc";
	}
	else{*/
		if($sortBy == 0){
			$sortStr = "order by clicks desc";
		}
		else if($sortBy == 1){
			$sortStr = "order by price asc";
		}
		else if($sortBy == 2){
			$sortStr = "order by price desc";
		}
		else if($sortBy == 3){
			$sortStr = "order by name";
		}
	///}
	if(isset($_POST['searchQ']) and trim($_POST['searchQ']) != '' and $viewAll != 'true'){
		$keywod = mysqli_real_escape_string($jr,$searchQ);
		$sql = "SELECT*FROM `artslist` WHERE ".$locatStr." (MATCH(loc) AGAINST ('$keywod') or MATCH(name)AGAINST('$keywod') or MATCH(artist)AGAINST('$keywod') or MATCH(descr)AGAINST('$keywod')) and verified = 1 and bought = 0 ".$wd.$wstr.$sortStr." ";
		if($newIt == 'true'){$sql = "select*from (".$sql.") as newTab order by id desc"; }
		//$k = redef("query","",$jr,0);
	 }
	else{		
	 	//$k = redef("query","",$jr,0);
		$sql = "select*from artslist where ".$locatStr." verified = 1 and bought = 0 ".$wd.$wstr.$sortStr." ";
		if($newIt == 'true'){$sql = "select*from (".$sql.") as newTab order by id desc"; }
	}
	$k = redef("query",$sql,$jr,0);
	echo '<input type="hidden" id="resCount" value="'.redef("mCount",$k,$jr,0).'" />';
	if(redef("mCount",$k,$jr,0) < 1){
		echo '<div align="center"><h3>No search result was found for your query</h3></div>';
	}
	$lastval = 0;
	$start = 0;
	if(is_numeric($next)){
		$mileStone = $next + 13;
	}
	else{
		$mileStone = 13;
	}
	if($next + 1  > redef("mCount",$k,$jr,0)){
		echo '<div align="center"><h3>No more result to display</h3></div>';
	}
	while ($b = redef("fetch",$k,$jr,0)){
		$start++;
		if($start == $mileStone){
			break;
		}
		if(is_numeric($next)){
			if($start <= $next){
				$printIt = false;
			}
			else{
				$printIt = true;
			}
		}
		else{
			//$lastval++;
			$printIt = true;
		}
		if($printIt){
		  ?>
		  <div id="card" class="col-sm-6 col-md-4">
				  <header class="thumb" style="height:265px;">
					<div class="item"><a href="big/<?php echo $b['id']; ?>/<?php echo escapeAll($b['name']); ?>"><img src="<?php 
					$y = explode("/",$b['dir']);
					if(count($y) < 3){
						echo $y[0]."/thumbs/".$y[1];
					}
					else{	
						  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
					} ?>" alt="thumbnail"></a>
					</div>
				  </header>
				  <div class="card_body" style="padding-left:5px; height:99px; padding-top:5px;">
					<span><?php echo $b['name'] ?></span>
					<div class="price"> 
					  <?php echo pricer($b['price']) ?>.00</div>
					  <ul class="btns">
						<li class="add" style="display:inline-block">
					<a href="javascript:void(0)" class="btn btn-default text-center" style="border:#d9594c thin solid; color:#d9594c" type="button" onClick="cart(this<?php echo ($tr)? ','.$userID.','.$b['id'] : '' ?>)">add to cart</a>
				  </li>
				  <li class="buy" style="display:inline-block">
				  <form name="buy" action="checkout" method="post" id="buy"><input type="hidden" value="<?php echo $b['id']; ?>" name="artID" /></form>
					<a href="javascript: void(0)" onClick="document.getElementById('buy').submit()" style="background:#d9594c; color:#FFF;" class="btn btn-default text-center" type="button">buy now</a>
				  </li>
				</ul>
				  </div></div>            
	<?php
	}
	}
	//$start = $start -1;
	if($start == redef("mCount",$k,$jr,0)){
	}
	else{
		echo '<div id="newItem'.$start.'"><input name="nextValue" type="hidden" id="nextValue" value="'.$start.'" /></div>';
	}
}
?>