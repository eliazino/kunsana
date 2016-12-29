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
if(isset($_REQUEST['cat'])){
	$cat = mysqli_real_escape_string($jr,$_REQUEST['cat']);
	$sq = "and categ = '".$cat."'";
}
else{
	$sq = "";
}
if(isset($_POST['searchQ'])){
$keywod = mysqli_real_escape_string($jr,$_POST['searchQ']);
	  $k = redef("query","SELECT*FROM `artslist` WHERE (MATCH(loc)AGAINST('$keywod') or MATCH(name)AGAINST('$keywod') or MATCH(artist)AGAINST('$keywod') or MATCH(descr)AGAINST('$keywod')) and verified = 1 and bought = 0 ".$sq." order by clicks desc limit 12",$jr,0);
}
else{
	 $k = redef("query","select*from artslist where verified = 1 and bought = 0 ".$sq." order by clicks desc limit 12",$jr,0);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo ($_POST['searchQ'] != '')? '"'.$_POST['searchQ'].'" - kunsana.com' :  'kunsana.com - search' ?> </title>
    <?php echo $base; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="dep/css/owl.carousel.css">
    <link rel="stylesheet" href="dep/css/owl.theme.default.css">
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
    <link rel="stylesheet" href="dep/css/fonts.css">
    <script src="dep/js/modernizr.custom.js"></script>
  </head>

  <body class="for_artsearch">
    <?php require_once('dep/server/umenu.php') ?>
     <div class="jumbo-carousel">
      <div class="container">
       <?php require_once('dep/server/gmenu.php') ?>
      </div>
    </div>
    <div class="main">
      <div class="search_header">
        <div class="container search_title" style="padding-top:20px;">
          <span id="search_name" style="font-size:20px;"><?php echo ($_POST['searchQ'] != '')? '"'.$_POST['searchQ'].'" - kunsana.com' :  'kunsana.com - arts' ?></span><br>
        <span id="product_no"> <span id="num"> <?php echo redef("mCount",$k, $jr,0); ?>  </span>product <?php echo (isset($_REQUEST['cat']))? " found in '".$cat."'" : " -" ?></span> 
      </div>
    </div>
    <div class="container">
    <div class="row" style=""><div class="col-sm-12" style=""><br>

    	<form role="form" onSubmit="return reSearch(this)">
                                        <div class="form-group input-group" style="height:50px;">
                                            <input type="text" placeholder="Try another search" class="form-control" name="searchbox" style="outline:none; height:50px;" value="<?php echo $_POST['searchQ']; ?>" id="searchP">
                                            <span class="input-group-addon hand"><i class="fa fa-search"></i></span>
                                        </div>
          </form>
    </div></div>
      <div class="row search">
        <div class=" col-xs-12 col-md-3 refine_panel">
        <h3 class="mobileAuth" style="display:none"><i class="fa fa-list"></i> click to refine your result</h3>
          <section class ="searchSection">
            <form class="ac-custom ac-checkbox ac-boxfill" autocomplete="off">
              <h3 class="refine_panel_title">Refine your result</h3>
              <div class="new_class">
              <ul>
                <li><input id="cb09" name="newIt" type="checkbox" class="params" value="true" onChange="reSearch(this)"><label for="cb09">Recent Artworks</label></li>
              </ul>
                </div>
              <h3 class="panel_title">category</h3>
              <ul>
              <?php
			  $start = 10;
              $cats = redef("query","select*from acat order by categ",$jr,0);
			  while($tada = redef("fetch",$cats,$jr,0)){
				  ?>
                <li><input <?php echo ($_REQUEST['cat'] == $tada['categ'])? 'checked' : '' ?> id="cb<?php echo $start; ?>" name="category" value="<?php echo $tada['categ']; ?>" type="checkbox" class="params" onChange="reSearch(this)"><label for="cb<?php echo $start; ?>"><?php echo $tada['categ'] ?></label></li>
                <?php
				$start++;
			  }?>    
              </ul>
            </form>
          </section>
          <div class="search_by">
            <div class="loc_drop" style="padding-bottom:30px;">
              <select class="form-control select select-primary" name="location" data-toggle="select" onChange="reSearch(this)">
                <option value="0" selected>ALL LOCATION</option>
                <?php 
				$q = redef("query","select*from states order by state_n",$jr,0);
				while($xo = redef("fetch",$q,$jr,0)){
                echo '<option value="'.$xo['state_n'].'">'.ucfirst($xo['state_n']).'</option>';
				}
				?>
              </select>
            </div>
          </div>

        </div>
        <div class="col-xs-12 col-md-9 searched_prod">
          <div class="row section1">
            <span class=" sort_by col-xs-8 col-md-6">
              <span>sort by:</span><select class="form-control select select-primary" name="sortBy" data-toggle="select" onChange="reSearch(this)">
                <option value="0" selected>popularity</option>
                <option value="1">price ascending</option>
                <option value="2">price desending</option>
                <option value="3">name</option>
              </select>
            </span>
            <span class="view col-xs-4 col-md-6"><a name="viewAll" href="javascript: void(0)" onClick="reSearch(this)">view all</a></span>
</div><div class="row search_cards" >
<div class="coverGlass" id="coverGlass" align="center"><table width="200" border="0px" style="width:100%; height:100%; border:none">
  <tr>
    <td align="center" valign="middle"><i class="fa fa-spinner fa-spin fa-3x" style="vertical-align:middle"></i> <h3 style="vertical-align:middle">Loading</h3></td>
  </tr>
</table>
</div>
<div id="cardCont">
<?php
if(redef("mCount",$k,$jr,0) < 1){
	echo '<h3>No search result was found for your query</h3>';
}
$lastval = 0;
while ($b = redef("fetch",$k,$jr,0)){
	$lastval++;
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
?>
<div id="newItem<?php echo $lastval ?>"><input name="nextValue" type="hidden" id="nextValue" value="<?php echo $lastval ?>" /></div>
</div>
</div>
</div>
      </div>
    </div>

    </div>
  
  <?php require_once('dep/server/footer.php'); ?>


  <script src="dep/js/jquery-latest.min.js"></script>
  <script src="dep/js/owl.carousel.min.js"></script>
  <script src="dep/js/bootstrap.min.js"></script>
  <script src="dep/js/svgcheckbx.js"></script>
  <script src="dep/js/script.js"></script>
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

  </body>
</html>