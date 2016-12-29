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
}

if(is_numeric($_GET['id']) and isset($_GET['p_id'])){
	$qo = $_GET['id'];
	$qw = mysqli_real_escape_string($jr, $qo);
	$za = redef("query","SELECT*FROM artslist where id = '$qw'",$jr,0);
	$zaq = redef("mCount",$za,$jr,0);
	if ($zaq < 1 or $zaq > 1){
		echo '<script type="text/javascript">
		location.replace("'.$baseLink.'error.php");
		</script>';
	}
	else{
		while($goo = redef("fetch",$za,$jr,0)){
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
		}
		if(isset($_SESSION['isVoted'])){
			$str = $_SESSION['isVoted'];
			$strarr = explode(",",$str);
			if(in_array($qw,$strarr)){
			}
			else{
				$reup = redef("query","update artslist set clicks = clicks + 1 where id = '$qo'",$jr,0);
				$_SESSION['isVoted'] = $_SESSION['isVoted'].','.$qw;
			}
		}
		else{
			$reup = redef("query","update artslist set clicks = clicks + 1 where id = '$qo'",$jr,0);
			$_SESSION['isVoted'] = $_SESSION['isVoted'].','.$qw;
		}
		$a = redef("query","select*from artists where username = '$artist'",$jr,0);
		while($na = redef("fetch",$a,$jr,0)){
			$afn = $na['fullname'];
			$address = $na['address'];
			$star = $na['star'];
			$email = $na['email'];
			$phone = $na['phone'];
			$state = $na['state'];
			$city = $na['city'];
			$site = $na['site'];
			$av = $na['artviews'];
		}
	}
}
else{
	echo '<script type="text/javascript">	location.replace("'.$baseLink.'error.php");	</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Kunsana.com <?php echo $name ." by ". $afn ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo $base; ?>
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="dep/css/owl.carousel.css">
    <link rel="stylesheet" href="dep/css/owl.theme.default.css">
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
    <link rel="stylesheet" href="dep/css/fonts.css">
  </head>
  <body class="for_artpreview">
    <?php require_once('dep/server/umenu.php') ?>
    
    
<div class="jumbo-carousel">
      
      <div class="container">
      <?php require_once('dep/server/gmenu.php') ?>
      
      </div>
    </div>
    <div  id="art_preview">
    <div class="container">
      <div class="row">
        <div class="preview_card  col-md-8">
          <div class="preview_thumb col-md-6" style="background:none">
          <table width="200" border="0" style="width:100%; height:100%; border:none; background:#FFF;">
          <tr>
            <td class="replace" align="center" valign="middle" style=""><img src="<?php echo $dir; ?>" alt="<?php echo $name ?>" onClick="inflate('<?php echo $dir; ?>');" class="img-responsive hand cartItem" style="vertical-align:middle" data-zoom-image="<?php echo $dir; ?>"></td>
          </tr>
        </table>

            
          </div>
          <div class="artpreview_des col-md-6" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif">
            <h3><?php echo $name; ?></h3>
            <div class="price"><span class="new_price"><?php echo pricer($price) ?></span></div>
            
            <div class="shipping_fee"><strong>Shipping fee:</strong> free</div>
            <!-- <div class="category">category: <span id="type">painting</span></div> -->
            <div class="size"><strong>Size:</strong> <?php echo ($size)? $size : 'Not Available' ?></div>
            <ul class="loc_like">
              <li class="likes"><strong><i class="fa fa-eye"></i></strong> <?php echo ($clicks > 1)? $clicks.' Views' : $clicks.' View' ?></li>
              <li class="location"><strong><i class="icon-user_loc"></i></strong> <?php echo $loc; ?></li>
            </ul>
            <div class="artist_name"><strong>Artist:</strong> <span class="name"><?php echo $afn; ?></span></div>
            <p><strong><i class="fa fa-magic"></i></strong> <?php echo $medium; ?></p>
            <div class="des-art"><?php echo $descr ?><p></p>
</div>
            <ul class="btns">
              <li class="add">
                <a href="javascript:void(0)" class="btn btn-default text-center" type="button" onClick="cart(this<?php echo ($tr)? ','.$userID.','.$qw : '' ?>)">add to cart</a>
              </li>
              <li class="buy">
              <form name="buy" action="checkout" method="post" id="buy"><input type="hidden" value="<?php echo $qo; ?>" name="artID" /></form>
                <a href="javascript: void(0)" onClick="document.getElementById('buy').submit()" class="btn btn-default text-center" type="button">buy now</a>
              </li>

            </ul>

          </div>

        </div>
        <div class="share col-md-4">
        <h1> share this artwork</h1>
          <div class="social_icon">
            <a href="#"><span class="icon-fb"><span class="path1"></span><span class="path2"></span></span></a>
            <a href="#"><span class="icon-insta"><span class="path1"></span><span class="path2"></span></span></a>
           <a href="#"> <span class="icon-tt"><span class="path1"></span><span class="path2"></span></span></a>
          </div>
          
        </div>
      </div>
    </div>
    </div>
    <div class="related_art">
      <div class="container">
      <div class="title">related artworks</div>
      <div class="row">
      <div class="owl-carousel owl-theme col-md-8">
        <?php 
		  $sql = "select*from artslist where (name like '%$name%' or descr like '%$name%') and id != $qw and bought =0 AND verified =1 order by id desc limit 6";
		  $xi = redef("query",$sql,$jr,0);
		  if(redef("mCount",$xi,$jr,0) < 1){
			  echo '<div align="center"><h3>Sorry! This list is empty</h3></div>';
		  }
		  while($xx = redef("fetch",$xi,$jr,0)){
		  ?>
            <div class="item"><a href="big/<?php echo $xx['id']; ?>/<?php echo escapeAll($xx['name']); ?>"><img src="<?php 
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
      <div class="more"><a href="showroom" class="btn btn-default text-center" type="button">more...</a></div>
     
    </div>
      </div>
    <?php require_once('dep/server/footer.php'); ?>
<div id="maincover" style="display:none;"></div>
<div id="pixcover" style="display:none" align="center" onClick="re(this.id,'pixcover')"></div>
    <script src="dep/js/jquery-latest.min.js"></script>
    <script src="dep/js/owl.carousel.min.js"></script>
    <script src="dep/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="dep/js/script.js"></script>
	<script src='dep/plugin/jquery.elevatezoom.js'></script>
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
	   $(".cartItem").elevateZoom({tint:true, tintColour:'#d9594c', tintOpacity:0.7});
    </script>
  </body>
</html>
