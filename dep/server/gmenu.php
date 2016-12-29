<?php
require_once('main.conf.php');
if(isset($_COOKIE['UID1'])){
	$key = mysqli_real_escape_string($jr,$_COOKIE['sk']);
	$tr = validate($_COOKIE['UID1'],$_COOKIE['sk'],$jr);
}
else if(isset($_SESSION['UID1'])){
	$tr = validate($_SESSION['UID1'],$_SESSION['sk'],$jr);
	$key = mysqli_real_escape_string($jr,$_SESSION['sk']);
}
else{
	$tr = false;
}
if($tr){
	$sql = "SELECT user.id AS uid, cartItem, carts.user, username FROM  `carts` LEFT JOIN user ON user.id = carts.user WHERE user.sessionkey = '$key'";
	$f = redef("query",$sql,$jr,0);
	$tcount = redef("mCount",$f,$jr,0);
}
?>
<div id="stow" align="center"></div>
<nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collaspe">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index"><img src="dep/media/kunsana.png" title="Kunsana" style="width:70px; height:auto" /></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collaspe">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index">Home<span class="sr-only">(current)</span></a></li>
            <!--<li><a href="#">Artist</a></li> -->
           <li class="dropdown">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown">collection<b class="caret"></b></a>
		            <ul class="dropdown-menu multi-column columns-2">
			            <div class="row">
				            <div class="col-sm-6">
					            <ul class="multi-column-dropdown">
                                <?php
								$datas = [];
								$start = 0;
								$sql = "select*from acat order by categ";
								$t = redef("query",$sql,$jr,0);
								while($dat = redef("fetch",$t,$jr,0)){
									$datas[$start] = $dat['categ'];
									$start++;
								}
								$div = ($start+1)/2;
								$col1 = ceil($div);
								$col2 = $start - $col1;
								$start = 0;
								while($start < $col1){
								?>
						            <li class="menuItem"><a href="showroom/<?php echo $datas[$start]; ?>"><?php echo $datas[$start]; ?></a></li>
                                    <?php
									$start++;
								}
								?>
					            </ul>
				            </div>
				            <div class="col-sm-6">
					            <ul class="multi-column-dropdown">
                                	<?php
									while($start < $col1+$col2){
									?>
						            <li class="menuItem"><a href="showroom/<?php echo $datas[$start]; ?>"><?php echo $datas[$start]; ?></a></li>
                                    <?php
									$start++;
									}
									?>
					            </ul>
				            </div>
			            </div>
		            </ul>
		        </li>
            <li><a href="#">blog</a></li>
            <li><a href="cart"><i class="icon-cart"></i> <span class="cart_no" id="cartCount"><?php echo ($tcount)? $tcount : 0 ?></span> cart</a></li>
            <li><div class="box">
              <div class="container-1">
              <form action="showroom" name="search" method="post" id="search">
                <span class="icon"><a href="javascript:void(0)" onclick="document.getElementById('search').submit()"><i class="fa fa-search"></i></a></span>
                <input type="text" id="search" placeholder="Find the art you love" name="searchQ" />
                </form>
              </div>
            </div></li>
            
            <!--
<li class="cart" style="font-size:30px; padding-top:1%; color:#fff; padding-left: 15px;">
<i class="fa fa-shopping-bag"></i>
</li>
<li class="cart-badge" >
<div class="row"><span class="badge">0</span></div>
<div class="row">cart</div>
</li>
-->


          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>