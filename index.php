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
	$ji = redef("query","select*from user where sessionKey = '$_SESSION[UID1]'",$jr,0);
	while($v = redef("fetch",$ji,$jr,0)){
		$username = $v['username'];
		$phone = $v['phone'];
		$email = $v['email'];
		$id = $v['id'];
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Kunsana Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="kunsana.com. Your one stop shop for original african artworks. We help artists get buyers from all over the world for their artwork">
    <meta name="author" content="">
	<?php echo $base; ?>
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
    <link rel="stylesheet" href="dep/css/fonts.css">
  </head>
  <body>
    <?php require_once('dep/server/umenu.php') ?>
    <div class="jumbo-carousel">
      <div id="myCarousel" class="carousel slide hidden-xs" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <img src="dep/img/1.png" alt="arts" class="img-responsive">
          </div>
          <div class="item">
            <img src="dep/img/2.png" alt="arts" class="img-responsive">
          </div>

          <div class="item">
            <img src="dep/img/3.png" alt="arts" class="img-responsive">

          </div>

        </div>


        <!--
<a class="L-control" href="#myCarousel" role="button" data-slide="prev">
<span class="fa fa-arrow-circle-left" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="R-control" href="#myCarousel" role="button" data-slide="next">
<span class="fa fa-arrow-circle-right" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
-->
      </div>
      <!-- Gmenu -->
      <?php require_once('dep/server/gmenu.php') ?>
      <div class="container">
        <div class="row">
          <div class="banner-content text-center hidden-xs">
            <h1>HELLO! WE ARE <span> KUNSANA</span></h1>

            <h3>Africa's Leading Online Art <span>Market</span></h3><br>

            <h3> <a href="showroom" class="btn-explore">explore </a></h3>

          </div>
          <div class="container">
            <div class="row">
              <div class="banner-content-small text-center visible-xs">
                <h1>HELLO! WE ARE <span>KUNSANA</span></h1>

                <h3>Africa's Leading Online Art <span>Market</span></h3><br>

                <h3> <a href="showroom" class="btn-explore">explore </a></h3>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="featured-product">
      <div class="container">
        <div class="heading">
          <h1>FEATURED PRODUCTS</h1>
        </div>
      </div>
      <div class="container">
        <div class="row">
        <!-- -->
		<?php
		$userI = ($userID)? $userID : '0';
		$sql = "SELECT username, descr, size, fullname, clicks, name, artid, price, dir, medium , descr, carts.id AS carted FROM ( SELECT username, descr, size, fullname, clicks, artslist.id AS artid, name, price, dir, medium FROM artslist
LEFT JOIN artists ON artists.username = artslist.artist WHERE bought =0 AND verified =1 ) AS ntab LEFT JOIN carts ON carts.cartItem = artid AND carts.user =".$userI." order by clicks desc limit 9";
			$se = redef("query",$sql,$jr,0);
			$start = 0;
			while ($mu = redef("fetch",$se,$jr,0))
			{
			?>        
          <div class="col-md-4 col-sm-6"><article class="card" >
            <header class="card_thumb">
              <a href="big/<?php echo $mu['artid']; ?>/<?php echo escapeAll($mu['name']); ?>"><img alt="<?php echo $mu['name']; ?>" style="width:406px; height:406px" src="<?php 
		$y = explode("/",$mu['dir']);
		if(count($y) < 3){
			echo $y[0]."/thumbs/".$y[1];
		}
		else{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>"></a>
            </header>
            <div class="card_body">
              <div class="card_title"><h2><?php echo ucfirst($mu['name']); ?></h2></div>
              <div class="price"><?php echo (!$mu['price'])? 'Not Available' : pricer($mu['price']); ?></div>
              <div class="artist">ARTIST:<span class="artist_name"><?php echo $mu['fullname']; ?></span></div>
              <div class="card_description">
                <p><i class="fa fa-magic"></i> Medium: <?php echo $mu['medium']; ?></p>
                <p><i class="fa fa-arrows"></i> Size: <?php echo $mu['size'] ?></p>
                <p><?php echo (strlen($mu['descr']) > 100)? substr($mu['descr'], 0, 100).'...' : $mu['descr'] ?></p>
              </div>
            </div>
            <footer class="card_footer">
             <button class="add-to-cart" <?php echo (is_numeric($mu['carted']))? 'style="background:green"' : '' ?> <?php echo ($tr)? '' : '' ?> onClick="cart(this<?php echo ($tr)? ','.$userID.','.$mu['artid'] : '' ?>)"><?php echo (is_numeric($mu['carted']))? '<i class="fa fa-check"></i> Added' : '<i class="fa fa-shopping-bag"></i>add to cart' ?></button>
              <span class="likes"><i class="fa fa-eye"></i><a href="#"><?php echo ($mu['clicks'] >1)? $mu['clicks'].' Views' : $mu['$clicks'].'View' ?></a></span>
            </footer>
            </article></div>
            <?php
			}
			?>
            
            
          <!-- -->
        </div>
      </div>
    </div>
    <div class="new-arrivals">
      <div class="heading">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h1>NEW ARRIVALS</h1>
            </div>
            <div class="control-right col-md-6">
              <a href="showroom" class="btn btn-default">veiw all</a>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          
          
          
          <?php
		$sql = "SELECT username, descr, size, fullname, clicks, name, artid, price, dir, medium , descr, carts.id AS carted FROM ( SELECT username, descr, size, fullname, clicks, artslist.id AS artid, name, price, dir, medium FROM artslist
LEFT JOIN artists ON artists.username = artslist.artist WHERE bought =0 AND verified =1 ) AS ntab LEFT JOIN carts ON carts.cartItem = artid AND carts.user =".$userI." order by artid desc limit 3";
			$se = redef("query",$sql,$jr,0);
			$start = 0;
			while ($mu = redef("fetch",$se,$jr,0))
			{
			?>        
          <div class="col-md-4 col-sm-6"><article class="card" >
            <header class="card_thumb">
              <a href="big/<?php echo $mu['artid']; ?>/<?php echo escapeAll($mu['name']); ?>"><img alt="<?php echo $mu['name']; ?>" style="width:406px; height:406px" src="<?php 
		$y = explode("/",$mu['dir']);
		if(count($y) < 3)
		{
			echo $y[0]."/thumbs/".$y[1];
		}
		else
		{	
			  echo $y[0]."/".$y[1]."/thumbs/".$y[2];
		} ?>"></a>
            </header>
            <div class="card_body">
              <div class="card_title"><h2><?php echo ucfirst($mu['name']); ?></h2></div>
              <div class="price"><?php echo (!$mu['price'])? 'Not Available' : pricer($mu['price']); ?></div>
              <div class="artist">ARTIST:<span class="artist_name"><?php echo $mu['fullname']; ?></span></div>
              <div class="card_description">
                <p><i class="fa fa-magic"></i> Medium: <?php echo $mu['medium']; ?></p>
                <p><i class="fa fa-arrows"></i> Size: <?php echo $mu['size'] ?></p>
                <p><?php echo (strlen($mu['descr']) > 100)? substr($mu['descr'], 0, 100).'...' : $mu['descr'] ?></p>
              </div>
            </div>
            <footer class="card_footer">
              <button class="add-to-cart" <?php echo (is_numeric($mu['carted']))? 'style="background:green"' : '' ?> <?php echo ($tr)? '' : '' ?> onClick="cart(this<?php echo ($tr)? ','.$userID.','.$mu['artid'] : '' ?>)"><?php echo (is_numeric($mu['carted']))? '<i class="fa fa-check"></i> Added' : '<i class="fa fa-shopping-bag"></i>add to cart' ?></button>
              <span class="likes"><i class="fa fa-eye"></i><a href="#"><?php echo ($mu['clicks'] >1)? $mu['clicks'].' Views' : $mu['$licks'].'View' ?></a></span>
            </footer>
            </article></div>
            <?php
			}
			?>
          
          
          
        </div>
      </div>
    </div>
    <div class="events">
      <div class="container">
        <div class="heading">
          <h1 class="text-center">EVENTS</h1>
        </div>
      </div>
      <div class="container">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!--          Indicators -->
          <!--
<ol class="carousel-indicators">
<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
<li data-target="#myCarousel" data-slide-to="1"></li>
<li data-target="#myCarousel" data-slide-to="2"></li>
</ol>
-->

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <div class="item active">
              <div class="row">
                <div class="col-md-6 ">
                  <div class="event_title">TITLE</div>
                  <div class="event_date">6 Jan 2016 9:00 AM </div>
                  <div class="event_description">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irtture dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div>
                  <div class="event_venue">
                    <span>VENUE</span><br>
                    <span class="address">ARTMARKET AUDITORIUM, LEKKI LAGOS.
                    </span>

                  </div>
                  <div class="event_speaker">
                    <span> SPEAKER</span><br>
                    <span class="name">Isaiah Obidinna (minister for Arts and Creativity)</span>

                  </div>
                </div>
                <div class="event_img col-md-6 hidden-xs hidden-sm">

                  <img src="dep/img/event1.png" alt="event-banner" class="img-responsive">
                </div>
              </div>

            </div>

            <div class="item">
              <div class="row">
                <div class="col-md-6">
                  <div class="event_title">TITLE</div>
                  <div class="event_date">6 Jan 2016 9:00 AM </div>
                  <div class="event_description">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irtture dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div>
                  <div class="event_venue">
                    <span>VENUE</span><br>
                    <span class="address">ARTMARKET AUDITORIUM, LEKKI LAGOS.
                    </span>

                  </div>
                  <div class="event_speaker">
                    <span> SPEAKER</span><br>
                    <span class="name">Isaiah Obidinna (minister for Arts and Creativity)</span>

                  </div>
                </div>
                <div class="event_img col-md-6 hidden-xs hidden-sm">

                  <img src="dep/img/event1.png" alt="event-banner" class="img-responsive">
                </div>
              </div>
            </div>

            <div class="item">

              <div class="row">
                <div class="col-md-6">
                  <div class="event_title">TITLE</div>
                  <div class="event_date">6 Jan 2016 9:00 AM </div>
                  <div class="event_description">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irtture dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div>
                  <div class="event_venue">
                    <span>VENUE</span><br>
                    <span class="address">ARTMARKET AUDITORIUM, LEKKI LAGOS.
                    </span>

                  </div>
                  <div class="event_speaker">
                    <span> SPEAKER</span><br>
                    <span class="name">Isaiah Obidinna (minister for Arts and Creativity)</span>

                  </div>
                </div>
                <div class="event_img col-md-6 hidden-xs hidden-sm">

                  <img src="dep/img/event1.png" alt="event-banner" class="img-responsive">
                </div>
              </div>
            </div>

          </div>


          <!--
<a class="L-control" href="#myCarousel" role="button" data-slide="prev">
<span class="fa fa-arrow-circle-left" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="R-control" href="#myCarousel" role="button" data-slide="next">
<span class="fa fa-arrow-circle-right" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
-->
        </div>
      </div>
    </div>
    <div class="fosoin visible-lg">
      <div class="container">
        <div class="row">
          <div class="forum_picks text-center">
            <div class="col-md-4">
              <div class="heading">
                <h1 class="text-center">FORUM PICKS</h1>
              </div>
            </div>
          </div>
          <div class="social_media text-center">
            <div class="col-md-4">
              <div class="heading">
                <h1 class="text-center">SOCIAL MEDIA</h1>
              </div>
            </div>
          </div>
          <div class="interviews text-center">
            <div class="col-md-4">
              <div class="heading">
                <h1 class="text-center">INTERVIEWS</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="meet_our_artist">
      <div class="container">
        <div class="heading">
          <h1>MEET OUR ARTISTS</h1>
        </div>
      </div>
      <div class="container">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!--          Indicators -->
          <!--
<ol class="carousel-indicators">
<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
<li data-target="#myCarousel" data-slide-to="1"></li>
<li data-target="#myCarousel" data-slide-to="2"></li>
</ol>
-->

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <div class="item active text-center">
              <div class="artist_img">
                <img src="dep/img/artist2.png" alt="artist-img">
              </div>
              <div class="artist_name">Lussie arnold</div>
              <div class="artist_category">photographer</div>
              <i class="fa fa-quote-left"></i>
              <div  class="artistic_quote"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..</p></div>

              <div class="artist_location">lagos, nigeria</div>


            </div>

            <div class="item text-center">
              <div class="artist_img">
                <img src="dep/img/artist2.png" alt="artist-img">
              </div>
              <div class="artist_name">Lussie arnold</div>
              <div class="artist_category">photographer</div>
              <i class="fa fa-quote-left"></i>
              <div  class="artistic_quote"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..</p></div>

              <div class="artist_location">lagos, nigeria</div>
            </div>

            <div class="item text-center">
              <div class="artist_img">
                <img src="dep/img/event1.png" alt="artist-img">
              </div>
              <div class="artist_name">Lussie arnold</div>
              <div class="artist_category">photographer</div>
              <i class="fa fa-quote-left"></i>
              <div  class="artistic_quote"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..</p></div>

              <div class="artist_location">lagos, nigeria</div>
            </div>

          </div>
        </div>


        <!--
<a class="left control" href="#myCarousel" role="button" data-slide="prev">
<span class="fa fa-arrow-circle-left" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="right control" href="#myCarousel" role="button" data-slide="next">
<span class="fa fa-arrow-circle-right" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
-->
      </div>
    </div>
    <div class="testimonials">
      <div class="container">
        <div class="heading">
          <h1>what they say</h1>
        </div>
      </div>


      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!--          Indicators -->
        <!--
<ol class="carousel-indicators">
<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
<li data-target="#myCarousel" data-slide-to="1"></li>
<li data-target="#myCarousel" data-slide-to="2"></li>
</ol>
-->

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
          <div class="item active text-center">
            <i class="fa fa-quote-left"></i>
            <div  class="cus_testimonials"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div>
            <div class="cus_name">richie smith</div>
            <div class="cus_location">ibadan, nigeria</div>


          </div>
          <div class="item text-center">
            <i class="fa fa-quote-left"></i>
            <div  class="cus_testimonials"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div>
            <div class="cus_name">richie smith</div>
            <div class="cus_location">ibadan, nigeria</div>


          </div>
          <div class="item text-center">
            <i class="fa fa-quote-left"></i>
            <div  class="cus_testimonials"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p></div>
            <div class="cus_name">richie smith</div>
            <div class="cus_location">ibadan, nigeria</div>


          </div>
        </div>
      </div>
    </div>
    <?php require_once('dep/server/footer.php'); ?>
    <script src="dep/js/jquery-latest.min.js"></script>
    <script src="dep/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="dep/js/script.js"></script>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var $_Tawk_API={},$_Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/581dbe738fe702416d726bc1/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
  </body>
