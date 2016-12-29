<?php
session_start();
require_once('dep/server/defines.php');
require_once('dep/server/main.conf.php');
require_once('dep/server/functions.php');
if(isset($_SESSION['sk2'])){
	$sessionID = mysqli_real_escape_string($jr, $_SESSION['sk2']);
	$dat = redef("query","select*from artists where sessionkey = '$sessionID'",$jr,0);
		if(redef("mCount",$dat,$jr,0) > 0){
			while($cv = redef("fetch",$dat,$jr,0)){
			$fullname = $cv['fullname'];
			$userID = $cv['id'];
			$username = $cv['username'];
			$phone = $cv['phone'];
			$email = $cv['email'];
			$gender = $cv['gender'];
			$address = $cv['address'];
			$city = $cv['city'];
			$state = $cv['state'];
			$artbot = $cv['artbought'];
			$company = $cv['company'];
			$compadd = $cv['compadd'];
			$bio = $cv['about'];
			$position = $cv['position'];
			$upldir = $cv['upldir'];
			$thumb = $cv['thumbdir'];
			$tr = true;
		}
	}
	else{
		header("location:artist-1");
	}
}
else{
	header("location:artist-1");
}
if($upldir){
	if (file_exists($upldir."/thumbs")){
	}
	else{
	 mkdir($upldir."/thumbs");
	}
}
else{
	$rana = rand(0,100000000);
	mkdir("arts/".$username."_".$rana);
	mkdir("arts/".$username."_".$rana."/thumbs");
	$udie = "arts/".$username."_".$rana;
	$upldir = $udie;
	$citeeee = redef("query","update artists set upldir = '$udie' where id = '$userID'",$jr,0);
}

if (isset($_POST['edit'])){
	$fullnp = mysqli_real_escape_string($jr,$_POST['fln']);
	$genp = mysqli_real_escape_string($jr,$_POST['gender']);
	$abotp = mysqli_real_escape_string($jr,$_POST['abt']);
	$intrep = mysqli_real_escape_string($jr,$_POST['intr']);
	$cadp = mysqli_real_escape_string($jr,$_POST['cad']);
	$phop = mysqli_real_escape_string($jr,$_POST['pho']);
	$cnamep = mysqli_real_escape_string($jr,$_POST['cname']);
	$compap = mysqli_real_escape_string($jr,$_POST['compa']);
	$posp = mysqli_real_escape_string($jr,$_POST['pos']);
	$statep = mysqli_real_escape_string($jr,$_POST['state']);
	$cityp = mysqli_real_escape_string($jr,$_POST['city']);
	$mailp = mysqli_real_escape_string($jr,$_POST['mail']);
	$funap = mysqli_real_escape_string($jr,$_POST['funa']);
	$fpwdp = mysqli_real_escape_string($jr,md5($_POST['fpwd']));
	$spwdp = mysqli_real_escape_string($jr,md5($_POST['spwd']));
	$ffeed = mysqli_real_escape_string($jr,$_FILES['ffeed']['name']);
	$site = mysqli_real_escape_string($jr,$_POST['site']);
	
	if ($_FILES['ffeed']['name'] != ""){
		$ilo = rand(1,10000000000000);
		$max_size = 2500000000;
		$allowtype = array('bmp', 'gif', 'jpg', 'jpeg', 'gif', 'png');
		$type = end(explode(".", strtolower($_FILES['ffeed']['name'])));
		if(in_array($type, $allowtype)){
			if ($_FILES['ffeed']['size']<= $max_size){
				if ($_FILES['ffeed']['error'] == 0){
					if ($thumb){
						$thumbdir = explode('/',$thumb);
			  			$thumbdir = $thumbdir[0].'/'.$thumbdir[1].'/';
					}
					else{
						mkdir("afo/".$art."".$ilo);
			  			$thumbdir = 'afo/'.$art.''.$ilo.'/';
					}
					$tot = $thumbdir."".$ffeed;
		 			$newname = 'nam'.rand(10000,10000000000).'.'.$type;
		 			$tot = $thumbdir."".$newname;
		  			move_uploaded_file($_FILES["ffeed"]["tmp_name"],$thumbdir."".$_FILES["ffeed"]["name"]);
					$sql = "update artists set fullname='$fullnp', about='$abotp', address='$cadp', email='$mailp', phone='$phop', city='$cityp', thumbdir='$tot', genre='$intrep', gender='$genp', company='$cnamep', compadd='$compap', position='$posp', `state`='$statep' where id = '$userID'";
		  			$insq = redef("query",$sql,$jr,0);
		  			$a = getimagesize($thumbdir.''.$_FILES['ffeed']['name']);
		   			thumbnail($newname,$_FILES['ffeed']['name'],$thumbdir,$thumbdir,$a[0],$a[1]);
					if($insq){
						$message = sM("Profile information was updated");
					}
					else{
						$message = eM("Sorry, an Error occurred");
					}
				}
				else{
					$message = eM("Sorry, The image file could not be read");
				}
			}
			else{
				$message = eM("Sorry, The image exceeded maximum size");
			}
		}
		else{
			$message = eM("Sorry, The image format is not supported");
		}
	}
	else{
		$sql = "update artists set fullname='$fullnp', about='$abotp', address='$cadp',site = '$site', email='$mailp', phone='$phop', city='$cityp', genre='$intrep', gender='$genp', company='$cnamep', compadd='$compap', position='$posp', state='$statep' where id = '$userID'";
		$insq = redef("query",$sql,$jr,0);
		  if ($insq){
			  $message = sM("Profile information was updated");
		  }
		  else{
			  $message = eM("Sorry, an Error occurred");
		  }
	}
}
if (isset($_POST['DeleteArt'])){
	$idval = mysqli_real_escape_string($jr,$_POST['DeleteArt']);
	$selc = redef("query","SELECT*FROM artslist where artist ='$username' and id = '$idval'",$jr,0);
	$selcom = redef("mCount",$selc,$jr,0);
	if($selcom > 0){
		while($paramlist = redef("fetch",$selc,$jr,0)){
			$completedir = $paramlist['dir'];
		}
		if(unlink($completedir)){
			$isdel =redef("query","DELETE FROM artslist where id = '$idval'",$jr,0);
			if($isdel){
				$message = sM("Success, Artwork was removed succesfully");
			}
			else{
				$message = eM("sorry, an error occured and the art could not be deleted. Query err");
			}
		}
		else{
			$message = eM("sorry, an error occured and the art could not be deleted. Non existing err");
		}
	}
	else{
		$message = eM("sorry, an error occured and the art could not be deleted. Auth violation err");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Artist Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="dep/css/fonts.css">
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
  </head>

  <body class="for_profile" data-spy="scroll" data-target=".sidebar" data-offset="15">
    <div id="stow" align="center"></div>
<div class="user-nav">
      <div class="container">
        <ul class="user-list">
          <?php echo ($tr)? '<li><a href="cart"><i class="fa fa-user"></i> '.$username.'</a></li><li><span class="separator">|</span></li>' : '' ?>
          
          <?php echo (!$tr)? '<li><a href="register"><i class="fa fa-lock"></i> Sign Up</a></li><li><span class="separator">|</span></li>' : ''?>
          
          <?php echo (!$tr)? '<li><a href="signin"><i class="fa fa-key"></i> Log In</a></li><li><span class="separator">|</span></li>' : '' ?>
          
          <?php echo ($tr)? '<li><a href="destroy-2"><i class="fa fa-sign-out"></i> Sign out</a></li>' : '' ?>
        </ul>
      </div>
     </div>
    <div class="jumbo-carousel">
      <div>
        <?php require_once('dep/server/gmenu.php') ?>
      </div>
    </div>
    <div class="back-img">
      <img src="dep/img/profilebanner.png">
    </div>

    <div class="user_prof">
      <div class="whwhw"></div>
      <div class="container">
        <div class="row user">
          <img src="<?php echo ($thumb)? $thumb : 'dep/img/avatar.png' ?>" class="artist_img" alt="<?php echo $fullname ?>">
          <div class="user_name">
            <h1 class="name"><?php $chunks = explode(" ", $fullname);
	$c = 0;
	while (count($chunks) > $c){
		if($c == 0){
			echo strtoupper($chunks[$c])." ";
		}
		else{
			echo ucfirst($chunks[$c])." ";
		}
		$c++;
	}
	if (!$fullname){
		echo "Names unavailable";
	}
	?></h1>
            <span class="username">&#64;<?php echo $username; ?></span>
            <span class="loc"><i class="icon-map"></i>
			<?php if(is_numeric($city)){
					  $z = redef("query","select*from cities where id = '$city'",$jr,0);
					  while ($v = redef("fetch",$z,$jr,0)){
						  echo $cityName = $v['city'];
					  }
				  }
				  else{
					  echo $cityName = ($city)? $city : 'City Not Set ';
				  } ?>,<?php if(is_numeric($state)){
					  $z = redef("query","select*from states where id = '$state'",$jr,0);
					  while ($v = redef("fetch",$z,$jr,0)){
						  echo $stateName = $v['state_n'];
					  }
				  }
				  else{
					  echo $stateName = ($state)? $state : 'State Not Set ';
				  } ?></span>
          </div>

          <div class="upload_no">
            <span class="num"><?php echo $artbot ?></span><br>
            <span class="title">uploads</span>
          </div>
          <div class="subcription_no">
            <span class="num">0</span><br>
            <span class="title">subcriptions</span>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row main">
        <div class="col-md-3" style="padding:0; top:-70px;" >
          <nav class="sidebar hidden-xs hidden-sm" >
            <ul class="nav sidenav" data-spy="affix" data-offset-top="500" data-offset-bottom="0" >
              <li class="">
                <a href="#personnal_info" class="active"><i class="icon-user"></i>Profile</a>
              </li>
              <li class=""> 
                <a href="#manage"><i class="icon-settings"></i>Manage Artworks</a>
              </li>
              <li class="">
                <a href="#add_art"><i class="icon-document-add"></i>Add Artworks</a>
              </li>
              <li class="">
                <a href="#view"><i class="icon-eye2"></i>View Subcriptions</a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="col-md-9">
        
        
        
        <?php
if(isset($_REQUEST['artID']) and $_GET['action'] = 'editArt'){
	$qo = $_REQUEST['artID'];
	$qw = mysqli_real_escape_string($jr, $qo);
	$za = redef("query","SELECT*FROM artslist where id = '$qw' and artist = '$username'",$jr,0);
	$zaq = redef("mCount",$za,$jr,0);
	if ($zaq < 1){
		$message = eM('Sorry, the artwork requested does not exist');
	}
	else{
		$display = true;
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
	}
	if($display){
        ?>
        <div class="poof add_art" style=" padding:12px; padding-right:40px;">
              <form method="post" enctype="multipart/form-data" action="dashboard?action=editArt&artID=<?php echo $_REQUEST['artID'] ?>" >
              <div class="art_details">
              <h1 class="page_header" style="color:#d9594c"><span class="fa fa-edit"></span> Editing Artwork <br><span class="note"><?php echo $qo ?></span></h1>
              <?php 
					$selall =redef("query","select*from artslist where artist = '$art'",$jr,0);
					$ro = redef("mCount",$selall,$jr,0);
					if ($ro >= 15){
						echo '<div style="background:#d9534f; color:#fff; padding:12px; font-size:14px" align="center">Your art have reached its limit, Kindly remove late artworks to add a new one</div><br/>';
						$disable = true;
					}
					?>
  <ul>
    <li>Fields Marked <span style="color:red">*</span> are compulsory.</li>
    <li>Fields marked <span style="color:green">*</span> are optional.</li>
  </ul>
  <?php
if(isset($_POST['editArtId'])){
	$artIDe = mysqli_real_escape_string($jr,$_POST['editArtId']);
	$name = mysqli_real_escape_string($jr,$_POST['name']);
	$descr = mysqli_real_escape_string($jr,htmlentities($_POST['description']));
	$categ = mysqli_real_escape_string($jr,$_POST['categ']);
	$price = mysqli_real_escape_string($jr,$_POST['price']);
	$width = mysqli_real_escape_string($jr,$_POST['width']);
	$height = mysqli_real_escape_string($jr,$_POST['height']);
	$medium = mysqli_real_escape_string($jr,htmlentities($_POST['medium']));
	$err = 0;
	if(strlen(trim($name)) < 10){ $message = eM("The Art name is cannot be less than 10 characters"); $err++; }
	if(strlen(trim($descr)) < 10 ){ $message = eM("The description is not good enough, cannot be less than 10 characters"); $err++; }
	if(!is_numeric($price)){$message = eM("Price can only be numbers"); $err++;}
	if((!is_numeric($width) and is_numeric($height)) or is_numeric($width) and !is_numeric($height)){$message = eM("You should set both height and width or leave both empty to keep previous value"); $err++;}
	if(strlen(trim($medium)) < 10 ){ $message = eM("Please state the medium, cannot be less than 10 characters"); $err++; }
	$locat = $cityName.", ".$stateName." State";
	$asize = (is_numeric($width) and is_numeric($height))? $width.' by '.$height.' Inch' : $size;
	if($err < 1 and isset($_FILES['uploadh']['name']) and trim($_FILES['uploadh']['name']) != '' ){
		$min_size= 50000;
		$max_size = 1000000;
		$allowtype = array('jpg', 'jpeg','png');
		$type = end(explode(".", strtolower($_FILES['uploadh']['name'])));
		$newname = 'kunsana_'.time().'_'.rand(10000,10000000000).'.'.$type;
		$a = getimagesize($_FILES['uploadh']['tmp_name']);
		if(in_array($type, $allowtype)){
			if($a[0] >= 400 and $a[1] >= 400){
				if($_FILES['uploadh']['error'] == 0){
					$artdir = $upldir.'/';
		  			$totalfile = $artdir."".$newname;
		  			$uda = date('d')." of ".date('M').", ".date('Y')." by ".$nt.":".date("i")." ".$ntz;
					$statusR = watermarker($_FILES['uploadh']['tmp_name'],$_FILES['uploadh']['name'], $newname, $artdir);
					if($statusR){
						thumbnail($newname,$newname,$artdir,$artdir,$a[0],$a[1]);
						resize_crop_image(400, 400, $artdir."".$newname, $artdir.'thumbs/'.$newname);
						 $insq = redef("query","update artslist set name='$name', dir ='$totalfile', descr='$descr', price='$price', categ='$categ', size='$asize', seller='$seller', medium='$medium' where id = $artIDe and artist = '$username'",$jr,0);
						 if($insq){
							 $_REQUEST['artID'] = 'null';
							 $message = sM("Your art information was updated succesfully");
						 }
						 else{
							 $message = eM("an error occured");
						 }
					}
				}
				else{
					$message = eM("Sorry, the image could not be read. Please select a new image.");
				}
			}
			else{
				$message = eM("Your image cannot have size less than 400 X 400. Please select a new image.");
			}
		}
		else{
			$message = eM("Your image is not valid, only image of format [*.jpg, *.jpeg, *.png] are allowed");
		}
	}
	else{
		//$message = em("A total of ".$err." errors needs to be resolved out");
		$insq = redef("query","update artslist set name='$name' , descr='$descr', price='$price', categ='$categ', size='$asize', seller='$seller', medium='$medium' where id = $artIDe and artist = '$username'",$jr,0);
		if($insq){
			$_REQUEST['artID'] = 'null';
		 	$message = sM("Your art information was updated succesfully");
		}
		else{
			$message = eM("An error occured");
		}
	}
}
                  ?> 
  
                    <div class="d title">
                    <header>Art Name <span style="color:red">*</span></header>
                    <input type="hidden" name="editArtId" value="<?php echo $qo ?>">
                    <input type="text" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> name="name" id="textfield2" value="<?php echo $name; ?>" class="form form-control" style="width:100%;" placeholder="Pinel Freez the insane" /></div>
                  
                    <div class="d title"><header>Medium and material<span style="color:red"> *</span></header>
                    <input type="text" name="medium" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> id="textfield" value="<?php echo $medium ?>" class="form form-control" style="width:100%;" placeholder="Oil and charcoal on cotton canvas" /></div>
                  
                    <div class="d size"><header>Original size (Inches) [Leave to retain value] <span style="color:red">*</span><header>
                    <input type="number" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> name="width" value="<?php echo ($_POST['succes']==1)? '' : $_POST['width'] ?>" class="" min="1" placeholder="20"/>
                    <input type="number" name="height" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> value="<?php echo ($_POST['succes']==1)? '' : $_POST['height']; ?>" class="" min="1" placeholder="20"/></div>
                  
                    <div class="d title"><header>Art and delivery description <span style="color:red">*</span></header>
                    <textarea name="description" id="textfield7" class="form form-control" style="width:100%;" placeholder="This artwork is a reminiscence of the 16th century Insane Assylum created in the heart of oakland forest. This artwork can only be delivered to resident of Lagos only"><?php echo $descr ?></textarea></div>
                  
                  <div class="d title"><header>Category <span style="color:red">*</span></header>
                    <select name="categ" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> id="select2" class="form form-control" style="width:100%;" >
                    <?php 
					$selcat = redef("query","select*from acat order by categ asc",$jr,0);
					while ($rund = redef("fetch",$selcat,$jr,0)){
                    ?>
                    <option <?php echo ($rund['categ'] == $categ)? 'selected=selected' : '' ?> ><?php echo $rund['categ']; ?></option>
                    <?php
					}
					?>
                    </select></div>
                    
                  <div class="d title">
                  <header>Price <span style="color:#000; font-size:10px;">(No punctuation)</span><span style="color:red">*</span></header>
                    <input type="number" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> name="price" id="textfield8" value="<?php echo $price; ?>" placeholder="20000" class="form form-control" style="width:100%;" />
                </div>
                  
                 <div class="d browse">
                <header>browse</header>
                <!--                <input type="file" id="browse" placeholder="browse...">-->
                <div class="box">
                  <input type="file" name="uploadh" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
                  <label for="file-7"><span></span> <strong><i class="icon-upload"></i> Choose a file&hellip;</strong></label>

                </div>
                </div>
                <div class="upload_btn">
              <button id="updart" class="hidden" name="updart">updart</button>
                <a href="javascript:void(0)" class="btn btn-default" onClick="document.getElementById('updart').click()"><i class="icon-upload"></i>update</a>
              </div>
              </div>
            </form>
        </div>
        <?php
	}
        }
		?>        
        
        
        
        <?php
		if($_GET['action'] == 'edit'){
			?>
        <form method="post" action="dashboard" enctype="multipart/form-data">
        <div class="editDiv" style="padding-top:20px;">
        <div style="padding:12px; font-weight:bold; color:#F13014;"><i class="fa fa-edit"></i> Editing Profile information</div>
          <div style="width:">
          <header>Fullname</header>
          <input type="text" name="fln" id="textfield3" value="<?php echo ($_POST['fln'])? $_POST['fln'] : $fullname; ?>" class="form form-control" style="width:100%;" /></div>
          <div style="width:">
          <header>Sex</header>
          <select id="select2" name="gender" class="form form-control">
                          <option 
                          <?php
						  if ($_POST['gender' ]== "Female" or $gender == "Female"){
							  echo "selected = selected";
						  }
						  ?>
                          >Female</option>
                          <option <?php
						  if ($_POST['gender'] == "Male" or $gender == "Male"){
							  echo "selected = selected";
						  }
						  ?>>Male</option>
            </select></div>
                        <div>
                        <header>Bio</header>
                        <textarea name="abt" id="textfield14" class="form form-control" style="width:100%;" ><?php echo ($_POST['abt'])?  $_POST['abt'] : $bio; ?></textarea>
                        </div>
                        <div>
                        <header>Specialization</header>
                        <input type="text" name="intr" id="textfield4" value="<?php echo ($_POST['genre'])? $_POST['genre'] : $genre; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div>
                        <header>Contact Address</header>
                        <textarea name="cad" id="textfield13" class="form form-control" style="width:100%;" ><?php echo ($_POST['cad'])? $_POST['cad'] : $address; ?></textarea>
                        </div>
                        <div>
                        <header>Phone Number</header>
                        <input type="text" name="pho" id="textfield5" value="<?php echo ($_POST['pho'])? $_POST['pho'] : $phone; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div class="s_h" style="font-weight:bold; padding:10px;">Job / Qualification Others</div>
                        <div>
                        <header>Company / Bussiness Name</header>
                        <input type="text" name="cname" id="textfield6"  value="<?php echo ($_POST['cname'])? $_POST['cname'] : $company; ?>" class="form form-control" style="width:100%;"  />
                        </div>
                        <div>
                        <header>Company / Bussiness Address</header>
                        <textarea name="compa" id="textfield12" class="form form-control" style="width:100%;" ><?php echo ($_POST['compa'])? $_POST['compa'] : $compadd; ?></textarea>
                        </div>
                        <div>
                        <header>Website</header>
                        <input type="text" name="site" id="textfield9" value="<?php echo ($_POST['site'])? $_POST['site'] : $site; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div>
                        <header>Position</header>
                        <input type="text" name="pos" id="textfield7" value="<?php echo ($_POST['pos'])? $_POST['pos'] : $position; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div class="s_h" style="font-weight:bold; padding:10px;">Location</div>
                        <div>
                        <header>State</header>
                        <select name="state" id="select3" class="form form-control" onChange="matchList(this.id)">
                          <?php
						  $re = redef("query","select*from states order by state_n asc",$jr,0);
						  while($mump = redef("fetch",$re,$jr,0)){
						  ?>
                            <option 
                            <?php
                            if ($_POST['state'] == $mump['state_n'] or $stateName == $mump['state_n'] || $_POST['state'] == $mump['id'] || $stateName == $mump['id']){
								?>
                                selected="selected"
                                <?php
							}
							?> value="<?php echo $mump['id']; ?>">
							
							<?php echo $mump['state_n']; ?></option>
                            <?php
						  }
						  ?>
                          </select>
                        </div>
            <div>
              <header>City</header>
                        <div id="manga"><select name="city" id="city" class="form form-control">
                          <option><?php echo isset($_POST['city'])? $_POST['state'] : $cityName ?></option>
                        </select></div>
          </div>
              <div class="s_h" style="font-weight:bold; padding:10px;">Log in detail</div>
                        <div>
                        <header>e mail</header>
                        <input type="text" name="mail" id="textfield8" value="<?php echo ($_POST['mail'])? $_POST['mail']: $email; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div>
                        <header>Choose a logo</header>
                        <input type="file" name="ffeed" id="ffeed" value="<?php echo $_POST['ffeed']; ?>" style="display:none" /><button class="btn btn-info" onClick="document.getElementById('ffeed').click(); return false"><i class="fa fa-camera"></i> Choose a new logo</button>
                        </div>
                        
                        <div align="right" style="padding-bottom:50px;">
                        <button name="edit" class="btn btn-primary">Update <i class="fa fa-pencil"></i></button>
                        </div>
          </div>
          </form>
        <?php
		}
		?>     
        
        
          <div class="personnal_info" id="personnal_info">
            <div class="row">
              <h1 class="page_header col-md-8 col-sm-6">
                Personnal information
                <br>
                <span class="note">These information is important to us</span>
              </h1>
              <div class="profile_btn col-md-4 col-sm-6">
                <a href="dashboard?action=edit" class="btn btn-default"><i class="icon-quill"></i>edit</a>
              </div>
            </div>
            <div class="p_details">
              <div class="row  full_name">
                <div class="d first col-md-6 col-sm-6">
                  <header>username</header>
                  <p><?php echo $username ?></p>
                </div>
                <div class="d last col-md-6 col-sm-6">
                  <header>gender</header>
                  <p>
                  <select name="gender">
                    <option value="<?php echo ($gender)? $gender : 'None' ?>"><?php echo ($gender)? $gender : 'None' ?></option>
                  </select></p>
                </div>
              </div>
              <div class="row  full_name">
                <div class="d first col-md-6 col-sm-6">
                  <header>firstname</header>
                  <p><?php $chunk = explode(" ",$fullname); 
				  $start = 1;
				  while($start < sizeof($chunk)){
					  echo $chunk[$start].' ';
					  $start++;
				  }
				   ?></p>
                </div>
                <div class="d last col-md-6 col-sm-6">
                  <header>lastname</header>
                  <p><?php $chunk = explode(" ",$fullname); echo $chunk[0] ?></p>
                </div>
              </div>
              <div class="d phone">
                <header>phone</header>
                <p><?php echo $phone ?></p>
              </div>
            </div>


          </div>
          <div class="authen_details">
            <h1 class="page_header">
              Contact details<br>
              <span class="note" >All this fields are important for your identification</span>
            </h1>
            <div class="row">
              <div class="col-md-4 col-sm-4">
                <div class="email">
                  <header>email</header>
                  <input type="text" id="text" placeholder="<?php echo $email ?>" value="<?php echo $email ?>">
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="country">
                  <header>State</header>
                  <select>
                    <option value="<?php echo $stateName; ?>"><?php echo $stateName; ?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-4 col-sm-4">
                <div class="state">
                  <header>city</header>
                  <select>
                    <option value="<?php echo $cityName; ?>"><?php echo $cityName; ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
            	<div class="col-md-6 col-sm-6">
                	<header>Bio</header>
                    <div><?php echo nl2br($bio) ?></div>
                </div>
                <div class="col-md-6 col-sm-6">
                	<header>Contact Address</header>
                    <div><?php echo nl2br($address) ?></div>
                </div>
            </div>
          </div>

          <div class="comp_details">
            <h1 class="page_header">Company's details<br><span class="note">Supply information about your company</span></h1>
            <div class="c_details">
              <div class=" d c_name">
                <header>name</header>
                <p><?php echo $company ?></p>
              </div>
              <div class="d c_loc">
                <header>location</header>
                <p><?php echo nl2br($compadd) ?></p>
              </div>
              <div class="d pos">
                <header>position</header>
                <p><?php echo $position ?></p>
              </div>
              <div class="d phone">
                <header>phone</header>
                <p><?php echo $phone ?></p>
              </div>
            </div>
          </div>
          <div class="manage_arts" id="manage">
            <h1 class="page_header">Manage Artworks<br> <span class="note">You can remove, edit or sponsor your artworks here</span></h1>
            <?php
			$ta = redef("query","select*from artslist where artist = '$username'",$jr,0);
			if(redef("mCount",$ta,$jr,0) < 1){
				echo '<div><h3>You do not have any artwork yet</h3></div>';
			}
			else{
				echo "<div class='row uploaded_gallery'>";
				while($p = redef("fetch",$ta,$jr,0)){
					?>
				<div class="M_thumb col-md-4 col-sm-6">
                <img src="<?php echo thumber($p['dir']) ?>">
                <div class="M_icon <?php  echo (!$p['verified'] || $p['bought'])? 'notActive' : '' ?>" style="padding-left:40px;">
                <?php
				if(!$p['verified'] || $p['bought']){
					?>
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo (!$p['verified'])? ' Artwork not yet approved' : 'Artwork has been sold' ?>" style="float:left"><span class="fa fa-info-circle"></span></a>
                <?php
				}
				?>
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="sponsor"><span class="icon-sponsor"></span></a>
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="edit" onClick="document.getElementById('editArt<?php echo $p['id']; ?>').submit()"><form action="dashboard?action=editArt" id="editArt<?php echo $p['id']; ?>" name="editArt" method="post"><input type="hidden" value="<?php echo $p['id'] ?>" name="artID" ></form><span class="icon-magic"></span></a>
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="delete" onClick="activateD('deltbtn<?php echo $p['id']; ?>')"><form name="delt" action="dashboard#manage" method="post" id="deltbtn<?php echo $p['id'] ?>"><input type="hidden" value="<?php echo $p['id']; ?>" name="DeleteArt" /></form><span class="icon-trash"></span></a>
                </div>
              </div>
              <?php
				}
				echo "</div>";
			}
			?>             
              </div>         
          
          <style>
		  #add_art input{
			  outline:none;
			  height:40px;
		  }
		  </style>
          
          <div class="poof add_art" id="add_art" style=" padding:12px; padding-right:40px;">
              <form method="post" enctype="multipart/form-data" action="dashboard#add_art" >
              <div class="art_details">
              <h1 class="page_header">Add Artwork<br><span class="note">Upload your artwork for marketing</span></h1>
              <?php 
					$selall =redef("query","select*from artslist where artist = '$art'",$jr,0);
					$ro = redef("mCount",$selall,$jr,0);
					if ($ro >= 15){
						echo '<div style="background:#d9534f; color:#fff; padding:12px; font-size:14px" align="center">Your art have reached its limit, Kindly remove late artworks to add a new one</div><br/>';
						$disable = true;
					}
					?>
  <ul>
    <li>Fields Marked <span style="color:red">*</span> are compulsory.</li>
    <li>Fields marked <span style="color:green">*</span> are optional.</li>
  </ul>
  <?php
if(isset($_POST['categ']) and !$disable and isset($_POST['uplart'])){	
	$name = mysqli_real_escape_string($jr,$_POST['name']);
	$descr = mysqli_real_escape_string($jr,htmlentities($_POST['description']));
	$categ = mysqli_real_escape_string($jr,$_POST['categ']);
	$price = mysqli_real_escape_string($jr,$_POST['price']);
	$width = mysqli_real_escape_string($jr,$_POST['width']);
	$height = mysqli_real_escape_string($jr,$_POST['height']);
	$medium = mysqli_real_escape_string($jr,htmlentities($_POST['medium']));
	$err = 0;
	if(strlen(trim($name)) < 10){ $message = eM("The Art name is cannot be less than 10 characters"); $err++; }
	if(strlen(trim($descr)) < 10 ){ $message = eM("The description is not good enough, cannot be less than 10 characters"); $err++; }
	if(!is_numeric($price)){$message = eM("Price can only be numbers"); $err++;}
	if(!is_numeric($width)){$message = eM("width can only be numbers"); $err++;}
	if(!is_numeric($height)){$message = eM("height can only be numbers"); $err++;}
	if(strlen(trim($medium)) < 10 ){ $message = eM("Please state the medium, cannot be less than 10 characters"); $err++; }
	$locat = $cityName.", ".$stateName." State";
	if($err < 1){
		$min_size= 50000;
		$max_size = 1000000;
		$allowtype = array('jpg', 'jpeg','png');
		$type = end(explode(".", strtolower($_FILES['uploadh']['name'])));
		$newname = 'kunsana_'.time().'_'.rand(10000,10000000000).'.'.$type;
		$a = getimagesize($_FILES['uploadh']['tmp_name']);
		if(in_array($type, $allowtype)){
			if($a[0] >= 400 and $a[1] >= 400){
				if($_FILES['uploadh']['error'] == 0){
					$asize = $width.' by '.$height.' Inch';
					$artdir = $upldir.'/';
		  			$totalfile = $artdir."".$newname;
		  			$uda = date('d')." of ".date('M').", ".date('Y')." by ".$nt.":".date("i")." ".$ntz;
					$statusR = watermarker($_FILES['uploadh']['tmp_name'],$_FILES['uploadh']['name'], $newname, $artdir);
					if($statusR){
						thumbnail($newname,$newname,$artdir,$artdir,$a[0],$a[1]);
						resize_crop_image(400, 400, $artdir."".$newname, $artdir.'thumbs/'.$newname);
						$message = sM("watermark added.");
						 $insq = redef("query","insert into artslist (name, descr, dir, price, artist, categ, nstat, udate, orient, loc, size, seller, medium) values ('$name', '$descr', '$totalfile', '$price', '$username', '$categ','$nego', '$uda', '$orientazn', '$locat', '$asize', '$seller', '$medium')",$jr,0);
						 $i = redef("query","update artists set artbought= artbought + 1 where username = '$username'",$jr,0);
						 if($i){
							 $message = sM("Your art was uploaded succesfully");
						 }
					}
				}
				else{
					$message = eM("Sorry, the image could not be read. Please select a new image.");
				}
			}
			else{
				$message = eM("Your image cannot have size less than 400 X 400. Please select a new image.");
			}
		}
		else{
			$message = eM("Your image is not valid, only image of format [*.jpg, *.jpeg, *.png] are allowed");
		}
	}
	else{
		//$message = em("A total of ".$err." errors needs to be resolved out");
	}
}
                  ?>
  
  
                    <div class="d title">
                    <header>Art Name <span style="color:red">*</span></header>
                    <input type="text" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> name="name" id="textfield2" value="<?php echo ($_POST['succes']==1)? '' : $_POST['name']; ?>" class="form form-control" style="width:100%;" placeholder="Pinel Freez the insane" /></div>
                  
                    <div class="d title"><header>Medium and material<span style="color:red"> *</span></header>
                    <input type="text" name="medium" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> id="textfield" value="<?php echo ($_POST['succes']==1)? '' : $_POST['medium']; ?>" class="form form-control" style="width:100%;" placeholder="Oil and charcoal on cotton canvas" /></div>
                  
                    <div class="d size"><header>Original size (Inches) <span style="color:red">*</span><header>
                    <input type="number" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> name="width" value="<?php echo ($_POST['succes']==1)? '' : $_POST['width'] ?>" class="" min="1" placeholder="20"/>
                    <input type="number" name="height" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> value="<?php echo ($_POST['succes']==1)? '' : $_POST['height']; ?>" class="" min="1" placeholder="20"/></div>
                  
                    <div class="d title"><header>Art and delivery description <span style="color:red">*</span></header>
                    <textarea name="description" id="textfield7" class="form form-control" style="width:100%;" placeholder="This artwork is a reminiscence of the 16th century Insane Assylum created in the heart of oakland forest. This artwork can only be delivered to resident of Lagos only"><?php echo ($_POST['succes']==1)? '' : $_POST['description']; ?></textarea></div>
                  
                  <div class="d title"><header>Category <span style="color:red">*</span></header>
                    <select name="categ" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> id="select2" class="form form-control" style="width:100%;" >
                    <?php 
					$selcat = redef("query","select*from acat order by categ asc",$jr,0);
					while ($rund = redef("fetch",$selcat,$jr,0)){
                    ?>
                    <option <?php echo ($rund['categ'] == $_POST['categ'])? 'selected=selected' : '' ?> ><?php echo $rund['categ']; ?></option>
                    <?php
					}
					?>
                    </select></div>
                    
                  <div class="d title">
                  <header>Price <span style="color:#000; font-size:10px;">(No punctuation)</span><span style="color:red">*</span></header>
                    <input type="number" <?php echo ($disable)? "disabled=\"disabled\"" : "" ?> name="price" id="textfield8" value="<?php echo ($_POST['succes']==1)? '' : $_POST['price']; ?>" placeholder="20000" class="form form-control" style="width:100%;" />
                </div>
                  
                 <div class="d browse">
                <header>browse</header>
                <!--                <input type="file" id="browse" placeholder="browse...">-->
                <div class="box">
                  <input type="file" name="uploadh" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
                  <label for="file-7"><span></span> <strong><i class="icon-upload"></i> Choose a file&hellip;</strong></label>

                </div>
                </div>
                <div class="upload_btn">
              <button id="uplart" class="hidden" name="uplart">uplart</button>
                <a href="javascript:void(0)" class="btn btn-default" onClick="document.getElementById('uplart').click()"><i class="icon-upload"></i>upload</a>
              </div>
              </div>
            </form>
        </div>
        </div>

      </div>
    </div>
    <?php require_once('dep/server/footer.php'); ?>
    
    <script src="dep/js/custom-file-input.js"></script>
    <script src="dep/js/jquery-latest.min.js"></script>
		<script src="dep/js/bootstrap.min.js"></script>
        <script src="dep/js/script.js"></script>
        <?php 
		if($message){
			echo "<script type=\"text/javascript\">mCra(\"".$message."\");</script>";
		}
		?>
	<script>
      $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>
  </body>
</html>