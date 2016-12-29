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
	if(isset($_POST['req']) and $_POST['req'] == 'scart'){
		?>
            <div class="row head">
              <div class="col-xs-7 col-md-7">
                <header>item description</header>
              </div>
              <div class="col-xs-3 col-md-3">
                <header>quantity</header>
              </div>
              <div class=" col-xs-2 col-md-2">
                <header>price</header>
              </div>
            </div>
            <?php
			$totalA = 0;
			$sql = "SELECT artslist.id as artid, dir, name, artist, size, price FROM `carts` left join artslist on artslist.id = carts.cartItem where user = ".$userID;
			$carts = redef("query",$sql, $jr, 0);
			if(redef("mCount",$carts, $jr, 0) > 0){
				while($ta = redef("fetch",$carts,$jr,0)){
					$totalA = $totalA + $ta['price'];
				?>
				<div class="row cart_items" id="item<?php echo $ta['artid'] ?>">
              <div class="item_des col-xs-7 col-md-7">
                <div class="thumb"><img src="<?php 
		$y = explode("/",$ta['dir']);
		if(count($y) < 3)
		{
			echo $y[0]."/thumbs/".$y[1];
		}
		else
		{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>" alt="thumbnail"></div>

                <div class="short_details">
                  <ul>
                    <li><span id="artname"><?php echo $ta['name']; ?></span></li>
                    <li><span id="name">by: <span id="artist"><?php echo $ta['artist'] ?></span></span></li>
                    <li><span id="size">SIZE: <?php echo ($ta['size'])? $ta['size'] : 'Not available' ?></span></li>
                    <li class="remove"><button id="remove" onClick="RemoveItem(this, <?php echo $ta['price'] ?>, <?php echo $ta['artid'] ?>)"><i class="icon-cancel"></i><span >remove</span></button></li>
                  </ul>
                </div>
              </div>
              <div class="quantity col-xs-3 col-md-3">
                <ul>
                  <li><button><i class="icon-minus"></i></button></li>
                  <li class="quan_no">1</li>
                  <li><button><i class="icon-plus"></i></button></li>

                </ul>
              </div>
              <div class="price col-xs-2 col-md-2">
                <span><?php echo pricer($ta['price']) ?>.00</span>
              </div>
            </div>
              <?php
				}
			}
			else{
				echo '<div style="padding:6px" align="center"><h3>No item available</h3></div>';
			}
			?>
            <div class="row check_option">
              <div class="continue_shopping col-xs-4 col-md-4">
                <a href="#">continue shopping</a>
              </div>
              <div class="total col-xs-5 col-md-5">
                <span> total <input type="hidden" value="<?php echo $totalA ?>" id="total" /><span class="amount" id="amount"><?php echo pricer($totalA) ?></span>.00</span>
              </div>
              <div class="checkout col-xs-3 col-md-3">
                <a href="#" class="disabled">checkout</a>
              </div>
            </div>
        <?php
	}
	if(isset($_POST['req']) and $_POST['req'] == 'utr'){
		?>
            <div class="row head">
              <div class=" col-xs-4 col-md-4">
                <header>item description</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>quantity</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>price</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>date</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>confirm order</header>
              </div>
            </div>
            <?php
			$sql = "SELECT artslist.id as artid, dir,deliveryDate, transactDate, name, artist, size, price FROM `transactions` left join artslist on artslist.id = transactions.cartItem where status = 0 and user = ".$userID;
			$carts = redef("query",$sql, $jr, 0);
			if(redef("mCount",$carts, $jr, 0) > 0){
				while($ta = redef("fetch",$carts,$jr,0)){
					?>
            <div class="row unsettled_items">
              <div class="item_des col-xs-4 col-md-4">
                <div class="thumb col-xs-5"><img src="<?php 
		$y = explode("/",$ta['dir']);
		if(count($y) < 3)
		{
			echo $y[0]."/thumbs/".$y[1];
		}
		else
		{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>" alt="thumbnail"></div>

                <div class="short_details col-xs-7">
                  <ul>
                    <li><span id="artname"><?php echo $ta['name']; ?></span></li>
                    <li><span id="name">by: <span id="artist"><?php echo $ta['artist'] ?></span></span></li>
                  </ul>
                </div>
              </div>
              <div class="quantity col-xs-2 col-md-2">
                <span class="quan_no">1</span>
              </div>
              <div class="price  col-xs-2 col-md-2">
                <span><span class="amount"><?php echo pricer($ta['price']) ?>.00</span></span>
              </div>
              <div class="date col-xs-2 col-md-2">
               <div class="ordered">
                <header>ordered on: </header>
                 <?php echo $ta['transactDate'] ?>
                </div>
                
                <div class="receive">
                <header>will receive on:</header>
                 <?php echo $ta['deliveryDate'] ?>
                </div>
                
              </div>
              <div class="confirmation col-xs-2 col-md-2">
                <span><input id="check" name="check" type="checkbox"><label for="check">confirm</label></span>
              </div>
            </div>
            <?php
				}
			}
			else{
				echo '<div style="padding:6px" align="center"><h3>No item available</h3></div>';
			}
			?>
             <div class="button"><a href="#">continue shopping</a></div>
        <?php
	}
	if(isset($_POST['req']) and $_POST['req'] == 'ptr'){
		?>
            <div class="row head">
              <div class=" col-xs-4 col-md-4">
                <header>item description</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>quantity</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>price</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>date</header>
              </div>
              <div class="col-xs-2 col-md-2">
                <header>Delivery</header>
              </div>
            </div>
            <?php
			$sql = "SELECT artslist.id as artid, dir,deliveryDate, transactDate, name, artist, size, price FROM `transactions` left join artslist on artslist.id = transactions.cartItem where status = 1 and user = ".$userID;
			$carts = redef("query",$sql, $jr, 0);
			if(redef("mCount",$carts, $jr, 0) > 0){
				while($ta = redef("fetch",$carts,$jr,0)){
					?>
            <div class="row past_items">
              <div class="item_des col-xs-4 col-md-4">
                <div class="thumb col-xs-6"><img src="<?php 
		$y = explode("/",$ta['dir']);
		if(count($y) < 3)
		{
			echo $y[0]."/thumbs/".$y[1];
		}
		else
		{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>" alt="thumbnail"></div>
                <div class="short_details col-xs-6">
                  <ul>
                    <li><span id="artname"><?php echo $ta['name']; ?></span></li>
                    <li><span id="name">by: <span id="artist"><?php echo $ta['artist']; ?></span></span></li>
                  </ul>
                </div>
              </div>
              <div class="quantity col-xs-2 col-md-2">
                <span class="quan_no">1</span>
              </div>
              <div class="price  col-xs-2 col-md-2">
                <span><span class="amount"><?php echo pricer($ta['price']); ?></span>.00</span>
              </div>
              <div class="date col-xs-2 col-md-2">
                <?php echo $ta['deliveryDate']; ?>
              </div>
              <div class="confirmation col-xs-2 col-md-2">
                <span><?php echo ($ta['status'])? 'confirmed' : '<span style="color:red">Not Confirmed</span>' ?></span>
              </div>
            </div>
            <?php
				}
			}
			else{
				echo '<div style="padding:6px" align="center"><h3>No item available</h3></div>';
			}
				?>
            <div class="button"><a href="#">continue shopping</a></div>
            
          </div>
        <?php
	}
}
else{
	if($_POST['req'] === 'scart'){
		$slot = " Cart";
	}
	elseif($_POST['req'] === 'utr'){
		$slot = " Unsettled transaction";
	}
	else{
		$slot = " Past transaction";
	}
	echo '<div style="padding:6px" align="center"><h3>Please sign in to view your'.$slot.'</h3></div>';
}
?>