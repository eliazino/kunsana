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
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>shopping cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo $base; ?>
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="dep/css/owl.carousel.css">
    <link rel="stylesheet" href="dep/css/owl.theme.default.css">
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
    <link rel="stylesheet" href="dep/css/fonts.css">
  </head>

  <body class="for_artcart">
    <?php require_once('dep/server/umenu.php') ?>
     <div class="jumbo-carousel">
      <div class="container">
       	<?php require_once('dep/server/gmenu.php') ?>        
      </div>
    </div>

    <div class="main_cart">
      <div class="container">
        <div class="row">
          <div class="profile col-sm-4 col-md-3">
            <div class="abt_user">
              <div class="user_img"> <a href="#"><img src="dep/img/<?php echo !($gender)? 'male' : strtolower($gender) ?>.png" alt="thumbnail"></a></div>

              <div class="user_details">
                <div id="fullname"><span id="surname"><?php echo $namesSplit[0] ?>,</span> <?php echo $namesSplit[1] ?></div>
                <div id="email"><?php echo $email ?></div>
                <div id="no"><?php echo $phone ?></div>
              </div>
            </div>
            <div class="options">
              <nav>
                <ul class="nav sidenav" >
                  <li class="active" id="scart">
                    <a href="javascript:void(0)" ><i class="icon-cart"></i><span class="opt-content">Shopping cart</span></a>
                  </li>
                  <li class="" id="ptr"> 
                    <a href="javascript:void(0)"><i class="icon-clock"></i><span class="opt-content">Past transaction</span></a>
                  </li>
                  <li class="" id="utr">
                    <a href="javascript:void(0)"><i class="icon-chain-broken"></i><span class="opt-content">Unsettled transaction</span></a>
                  </li>
                </ul>
              </nav>   
            </div>  
          </div>
          <div class="cart col-sm-8 col-md-9" id="ctabl">
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
			if(isset($userID)){
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
			}
			else{
				echo '<div style="padding:6px" align="center"><h3>Please sign in to view your cart</h3></div>';
			}
			?>
            <div class="row check_option">
              <div class="continue_shopping col-xs-4 col-md-4">
                <a href="showroom">continue shopping</a>
              </div>
              <div class="total col-xs-5 col-md-5">
                <span> total <input type="hidden" value="<?php echo $totalA ?>" id="total" /><span class="amount" id="amount"><?php echo pricer($totalA) ?></span>.00</span>
              </div>
              <div class="checkout col-xs-3 col-md-3">
                <a href="checkout" class="disabled">checkout</a>
              </div>

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
          <?php 
		  $sql = "select*from artslist where bought =0 AND verified =1 order by id desc limit 6";
		  $xi = redef("query",$sql,$jr,0);
		  while($xx = redef("fetch",$xi,$jr,0)){
		  ?>
            <div class="item"><a href="big/<?php echo $xx['artid']; ?>/<?php echo escapeAll($xx['name']); ?>"><img src="<?php 
		$y = explode("/",$xx['dir']);
		if(count($y) < 3)
		{
			echo $y[0]."/thumbs/".$y[1];
		}
		else
		{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>"></a></div>
            <?php
		  }
		  ?>
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
  </body>
</html>