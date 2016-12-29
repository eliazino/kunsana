<?php
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
	/*$f = redef("query","select*from user where sessionkey = '$key'",$jr,0);
	while($cv = redef("fetch",$f,$jr,0)){
		$fullname = $cv['fulln'];
		$userID = $cv['id'];
		$username = $cv['username'];
		$phone = $cv['phone'];
		$email = $cv['email'];
	}
	$namesSplit = explode(' ',$fullname);
	echo $userID;*/
}
$cartIt = false;
if(isset($_POST['artID'])){
	$id = mysqli_real_escape_string($jr,$_POST['artID']);
	$d = redef("query","select*from artslist where id = '$id' and bought = 0 and verified = 1",$jr,0);
	if(redef("mCount",$d,$jr,0) < 1){
		$message = eM("Sorry, the artwork is no longer available");
	}
	else{
		$cartIt = true;
		while($goo = redef("fetch",$d,$jr,0)){
			$dir = $goo['dir'];
			$name = $goo['name'];
			$descr = $goo['descr'];
			$price = $goo['price'];
			$artist = $goo['artist'];
			$categ = $goo['ctaeg'];
			$clicks = $goo['clicks'];
			$udate = $goo['udate'];
			$orient = $goo['orient'];
			$size = $goo['size'];
			$seller = $goo['seller'];
			$loc = $goo['loc'];
			$nstat = $goo['nstat'];
			$medium = $goo['medium'];
			$size = $goo['size'];
			$isSingle = true;
		}
	}
}
else{
	$civ = redef("query", "select name, bought, verified, price, dir, artist from carts left join artslist on artslist.id = carts.cartItem where carts.user = '$userID'",$jr,0);
	if(redef("mCount",$civ,$jr,0) > 0){
		$cartIt = true;
	}
	if($tr and $cartIt){
	}
	else{
		$message = eM("Sorry, No item was found in the cart, kindly sign in to access your cart");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>checkout</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="dep/css/bootstrap.min.css" rel="stylesheet">
		<link href="dep/style.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="dep/css/owl.carousel.css">
		<link rel="stylesheet" href="dep/css/owl.theme.default.css">
		<link rel="stylesheet" href="dep/css/font-awesome.min.css">
		<link rel="stylesheet" href="dep/css/fonts.css">
	</head>

	<body class="for_checkout">
		<?php require_once('dep/server/umenu.php') ?>
		<div class="jumbo-carousel">
			<?php require_once('dep/server/gmenu.php') ?>
		</div>

		<div class="main">
			<div class="container">
				<div class="row button">
					<div class="col-xs-6 col-md-6">
						<div class="to_cart">
							<a href="cart"><i class="icon-arrow-left hidden-xs"></i>back to cart</a>
						</div>
					</div>
					<div class="col-xs-6 col-md-6">
						<div class="to_payment">
							<a href="javascript: void(0)" class="paynow" style="<?php echo ($cartIt)? '' : 'opacity:.4' ?>">proceed to payment<i class="icon-arrow-right hidden-xs"></i></a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="items_list">
						<div class="row head">
							<div class="col-xs-6 col-md-6">
								<header>item description</header>
							</div>
							<div class=" col-xs-3 col-md-3">
								<header>price</header>
							</div>
							<div class=" col-xs-3 col-md-3">
								<header>total</header>
							</div>
						</div>
                        
                        
                        <?php
						if($isSingle){
							?>
						<div class="row check_items">
							<div class="item_des col-xs-6 col-md-6">
								<div class="thumb"><img src="<?php 
		$y = explode("/",$dir);
		if(count($y) < 3)
		{
			echo $y[0]."/thumbs/".$y[1];
		}
		else
		{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>" alt="thumbnail"></div>
								<div class="short_details">
									<div class="artname">
										<span><?php echo $name; ?></span>
									</div>
									<div class="quantity">
										Quantity: <span class="quan_no">1</span>
									</div>
								</div>
							</div>
							<div class="price col-xs-3 col-md-3">
								<span><span class="amount"><?php echo pricer($price); ?></span>.00</span>
								<div class="ship_cost">
									<span>shipping cost:</span>	<span>N<span class="amount">00</span>.00</span>
								</div>
							</div>
							<div class="total_price col-xs-3 col-md-3">
								<span><span class="amount"><?php echo pricer($price); ?></span>.00</span>
							</div>
						</div>
                        <?php
						$total = $price;
						}
						else{
							$total = 0;
							if(redef("mCount",$civ,$jr,0) < 1){
								echo "<div align='center'><h4>No item is available in the cart, please add an art to cart </h4></div>";
							}
							else{
								while($bi = redef("fetch",$civ,$jr,0)){
									if($bi['bought'] == 1 or $bi['verified'] == 0){$faded = "faded"; $missing = true; $missM = eM('One or more item in your cart is no more available'); } else {$faded = ''; $total = $total + $bi['price'];}
								?>
                                <div class="row check_items <?php echo $faded ?>">
							<div class="item_des col-xs-6 col-md-6">
								<div class="thumb"><img src="<?php 
									$y = explode("/",$bi['dir']);
									if(count($y) < 3){
										echo $y[0]."/thumbs/".$y[1];
									}
									else{	
										  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
									} ?>" alt="thumbnail"></div>
								<div class="short_details">
									<div class="artname">
										<span><?php echo $bi['name']; ?></span>
									</div>
									<div class="quantity">
										Quantity: <span class="quan_no">1</span>
									</div>
								</div>
							</div>
							<div class="price col-xs-3 col-md-3">
								<span><span class="amount"><?php echo pricer($bi['price']); ?></span>.00</span>
								<div class="ship_cost">
									<span>shipping cost:</span>	<span>N<span class="amount">00</span>.00</span>
								</div>
							</div>
							<div class="total_price col-xs-3 col-md-3">
								<span><span class="amount"><?php echo pricer($bi['price']); ?></span>.00</span>
							</div>
						</div>
                                <?php
							}
						}
						}
						?>
						<div class="row grand_total">
							<span> grand total <span class="amount"><?php echo pricer($total); ?></span>.00</span>
						  </div>
			</div>
				</div>
				<div class="row delivery_add">
					<header>confirm delivery address</header>

					<div class="form_body">
						<div class="name"><input type="text" id="text" value="<?php echo $fullname ?>" class="form form-control" placeholder="Enter name"></div>

						<div class="phone"><input type="text" id="text" value="<?php echo $phone; ?>" class="form form-control" placeholder="Enter phone"></div>
						<div class="your_msg">
							<textarea rows="5" name="comment" id="text" class="form form-control" style="resize:none" placeholder="Enter delivery address"><?php echo $address; ?></textarea>
						</div>
						
					</div>
				</div>
				<div class="row button">
					<div class="col-xs-6 col-md-6">
						<div class="to_cart">
							<a href="#"><i class="icon-arrow-left hidden-xs"></i>back to cart</a>
						</div>
					</div>
					<div class="col-xs-6 col-md-6">
						<div class="to_payment">
							<a href="javascript: void(0)" class="paynow" style="<?php echo ($cartIt)? '' : 'opacity:.4' ?>">proceed to payment<i class="icon-arrow-right hidden-xs"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="related_art">
			<div class="container">
				<div class="title">suggested artworks</div>
				<div class="row">
					<div class="owl-carousel owl-theme col-md-8">
						<div class="item"><a href="#"><img src="dep/img/pre1.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre2.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre3.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre4.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre5.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre3.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre4.png"></a></div>
						<div class="item"><a href="#"><img src="dep/img/pre5.png"></a></div>
					</div>
				</div>
				<div class="more"><a href="#" class="btn btn-default text-center" type="button">more...</a></div>

			</div>
		</div>
		<?php require_once('dep/server/footer.php'); ?>
		<script src="dep/js/jquery-latest.min.js"></script>
		<script src="dep/js/owl.carousel.min.js"></script>
		<script src="dep/js/bootstrap.min.js"></script>
		<script>
			$('.owl-carousel').owlCarousel({
				loop:true,
				margin:10,
				nav:false,
				autoplay:true,
				autoplayTimeout:2000,
				autoplayHoverPause:true,
				responsive:{
					0:{
						items:2
					},
					600:{
						items:3
					},
					1000:{
						items:5
					}
				}
			})
		</script>
        <script src="dep/js/script.js"></script>
        <?php 
		if($message){
			echo "<script type=\"text/javascript\">mCra(\"".$message."\");</script>";
		}
		if($missing){
			echo "<script type=\"text/javascript\">mCra(\"".$missM."\");</script>";
		}
		?>
	</body>
</html>