<?php
session_start();
require_once("main.conf.php");
require_once("defines.php");
function validate($user,$key,$jr){
	$y = redef("query","select*from user where sessionkey = '$key'",$jr,0);
	$yi = redef("mCount",$y,$jr,0);
	if($yi > 0){
		while($vi = redef("fetch",$y,$jr,0)){
			$id = $vi['id'];
			$email = $vi['user'];
			$vm = Mcrypt($email);
		}
		$r2 = redef("query","select*from session_tab where sessionStr = '$key' and user = '$user'",$jr,0);
		if(redef("mCount",$r2,$jr,0) > 0){
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}
function validate2($user,$key,$jr){
	$y = redef("query","select*from artists where sessionkey = '$key'",$jr,0);
	$yi = redef("mCount",$y,$jr,0);
	if($yi > 0){
		while($vi = redef("fetch",$y,$jr,0)){
			$id = $vi['id'];
		}
		return false;
	}
	else{
		return false;
	}
}
function validateMod($a,$b,$c){
	$y = redef("query","select*from mods where uname ='$a' and sessionId = '$b'",$jr,0);
	$y = redef("mCount",$y,$jr,0);
	if($y > 0){
		return true;
	}
	else{
		session_destroy();
		return false;
	}
}
function escapeAll($ji){
	$needles  = array("/",".",",","<",">","?","/",":",";","@","'","~","#","[","]","{","}","¬","`","!",'"',"£","$","%","^","&","*","(",")","_","=","|","\\");
	$newJ = str_replace($needles,"",$ji);
	$newJ = str_replace(" ","-",$newJ);
	return $newJ;
}
function GrabSrc($imsrc,$gender){
	$k = explode("/",$imsrc);
	if(in_array("avatar",$k)){
		if($gender=="Female"){
			$rsrc = "media/avatar/female.png";
		}
		else{
			$rsrc = "media/avatar/male.png";
		}
	}
	else{
		$rsrc = $imsrc;
	}
	return $rsrc;
}
function Mcrypt($str){
	$outstr = "";
	$st = sha1($str);
	$len = strlen($st);
	$asciMap = array(len);
	$start = 0;
	$genC = 0;
	while ($start < $len){
		$asciMap[$start] = tener(ord(substr($st,$start,1)));
		//echo $asciMap[$start]."<br/>";
		$start++;
	}
	$xi = array($len/4);
	$start = 0;
	while($start < $len){
		$a = $asciMap[$start];
		$b = $asciMap[$start + 1];
		$c = $asciMap[$start + 2];
		$d = $asciMap[$start + 3];
		$sumer = 0;
		$sub = 0;
		while($sumer < 4){
			$t1 = 0;
			$t2 = 0;
			$t3 = 0;
			$t4 = 0;
			while($sub < 4){
				if($sumer == 0){
					if($sub == 0){
						$t1 = $t1 + substr($a,0,1);
					}
					elseif($sub == 1){
						$t1 = $t1 + substr($b,1,1);
					}
					elseif($sub == 2){
						$t1 = $t1 + substr($c,2,1);
					}
					else{
						$t1 = $t1 + substr($d,3,1);
					}
				}
				elseif($sumer == 1){
					if($sub == 0){
						$t2 = $t2 + substr($b,0,1);
					}
					elseif($sub == 1){
						$t2 = $t2 + substr($c,1,1);
					}
					elseif($sub == 2){
						$t2 = $t2 + substr($d,2,1);
					}
					else{
						$t2 = $t2 + substr($a,3,1);
					}
				}
				elseif($sumer == 2){
					if($sub == 0){
						$t3 = $t3 + substr($c,0,1);
					}
					elseif($sub == 1){
						$t3 = $t3 + substr($d,1,1);
					}
					elseif($sub == 2){
						$t3 = $t3 + substr($a,2,1);
					}
					else{
						$t3 = $t3 + substr($a,3,1);
					}
				}
				elseif($sumer == 3){
					if($sub == 0){
						$t4 = $t4 + substr($d,0,1);
					}
					elseif($sub == 1){
						$t4 = $t4 + substr($a,1,1);
					}
					elseif($sub == 2){
						$t4 = $t4 + substr($b,2,1);
					}
					else{
						$t4 = $t4 + substr($c,3,1);
					}
				}
					$sub++;
			}
				$sub = 0;
				$xi[$genC] = $t1 + $t2 + $t3 + $t4;
				$outstr = $outstr."".$xi[$genC];
				$genC++;
				$sumer++;
			}
			//echo $xi[$start] +$xi[$start+1] +$xi[$start+2] + $xi[start+3]."<br/>";
		$start = $start + 4;
	}
	return $outstr;	
}
function tryMail($sender, $message, $to, $subject){
	$sentmail = false;
	$headers = "From: ".$sender."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$sentmail = mail($to,$subject,$message,$headers);
	return $sentmail;
}
function tener($binInt)
{
	$Quad = "";
	$binstack = array(8);
	$start = 128;
	$counter = 0;
	while ($start >= 1){
		if($start <= $binInt){
			$binInt = $binInt - $start;
			$binstack[$counter] = 1;
		}
		else{
			$binstack[$counter] = 0;
		}
		//echo $binstack[$counter];
		$start = $start/2;
		$counter++;
	}
	$start = 0;
	$binstack = array_reverse($binstack);
	while($start < 8){
		if($binstack[$start] == 1 && $binstack[$start+1] == 0){
			$Quad = $Quad."1";
		}
		elseif($binstack[$start] == 0  && $binstack[$start+1] == 1){
			$Quad = $Quad."3";
		}
		elseif($binstack[$start] == 0 && $binstack[$start + 1] == 0){
			$Quad = $Quad."0";
		}
		else{
			$Quad = $Quad."4";
		}
		//echo $binstack[$start]."and".$binstack[$start+1]."<br/>";
		$start = $start + 2;
	}
	return $Quad;
}
function validatestr($str,$type){
	$right = true;
	//$message = "";
	//$responses = array($right, $message);
	if ($type == "str")
	{
		$right = preg_match("/^[0-9]+$/",$str);
	}
	elseif($type == "anum")
	{
		$right = preg_match("/^[0-9a-zA-Z]+$/",$str);
	}
	elseif($type == "num")
	{
		$right = preg_match("/^[0-9]+$/",$str);
	}
	elseif($type == "mail")
	{
		list($userName, $mailDomain) = explode("@", $str);
		$right = checkdnsrr($mailDomain, "MX");
		if($right)
		{
			$q = redef("query","select*from kins where email = '$str'",$jr,0);
			if(redef("mCount",$q) > 0)
			{
				$right = false;
				$message = eM("email address exists");
			}
			else
			{
				$right = true;
			}
		}
		else
		{
			$message = em("email is invalid");
			$right = true;
		}
	}
	return $right;
}
function validatestrstr($str,$typed){
	//$right = true;
	$message = "";
	if ($typed == "str")
	{
		$right = preg_match("/^[0-9]+$/",$str);
	}
	else if($typed == "anum")
	{
		$right = preg_match("/^[0-9a-zA-Z]+$/",$str);
	}
	else if($typed == "num")
	{
		$right = preg_match("/^[0-9]+$/",$str);
	}
	elseif($typed == "mail")
	{
		//echo "kkk";
		list($userName, $mailDomain) = explode("@", $str);
		$right = checkdnsrr($mailDomain, "MX");
		if($right)
		{
			$q = redef("query","select*from kins where email = '$str'",$jr,0);
			if(redef("mCount",$q) > 0)
			{
				$right = false;
				$message = eM("email address exists");
			}
			else
			{
				$right = true;
			}
		}
		else
		{
			$message = em("email is invalid");
			$right = false;
		}
	}
	$responses = array($right, $message);
	return $responses;
}
function punctuate($pri)
{
	$r = strrev($pri);
	$len = strlen($r);
	$start = 0;
	$nstr  = "";
	$l = 0;
	while ($start < $len)
	{
		if($l == 2 && ($start+1) < $len)
		{
			$nstr = ",".substr($r,$start,1).$nstr;
			$iS = true;
		}
		else
		{
			$nstr = substr($r,$start,1).$nstr;
		}
		$start++;
		if (!$iS)
		{
			$l++;
		}
		else
		{
			$l = 0;
			$iS = false;
		}
	}
return $nstr;
}

function pricer($pri)
{
	$r = strrev($pri);
	$len = strlen($r);
	$start = 0;
	$nstr  = "";
	$l = 0;
	while ($start < $len)
	{
		if($l == 2 && ($start+1) < $len)
		{
			$nstr = ",".substr($r,$start,1).$nstr;
			$iS = true;
		}
		else
		{
			$nstr = substr($r,$start,1).$nstr;
		}
		$start++;
		if (!$iS)
		{
			$l++;
		}
		else
		{
			$l = 0;
			$iS = false;
		}
	}
return "&#8358;".$nstr;
}



function wM($str){
	return '<div style=\'padding:12px; opacity:.92\'><div style=\'color:#F48622; font-size:16px; background:#FBE9BD; border-left:#F48622 thick solid; width:50%; padding:15px; font-family:Helvetica Neue,Helvetica,Arial,sans-serif;\'><i class=\'fa fa-exclamation-circle\'></i> '.$str.'</div></div>';
}
function eM($str)
{
	//$nstr = '<div style="background:#d9534f; color:#fff; padding:12px; font-size:14px; width:70%; border-radius:8px;" align="center">'.$str.'</div>';
	$nstr = '<div style=\'padding:12px;\'><div align=\'left\' style=\'color:#ED050B; opacity:.92;width:50%; font-size:15px; background:#F9B4B0; border-left:#ED050B thick solid; padding:15px; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; \' ><i class=\'fa fa-warning\'></i> '.$str.'</div></div>';
	return $nstr;
}
function thumber($jstr){
	$y = explode("/",$jstr);
	if(count($y) < 3){
		$tsr = $y[0]."/thumbs/".$y[1];
	}
	else{	
		$tsr = $y[0]."/".$y[1]."/thumbs/".$y[2];
	}
	return $tsr;
}
function timer($tstr)
{
	$ret = "";
	$pass_time = time() - $tstr;
	if($pass_time < 2){
		$ret = "<i class='fa fa-clock'></i> about a second ago";
	}
	else if($pass_time > 1 and $pass_time < 60){
		$ret = "<i class='fa fa-clock'></i> less than a minute ago";
	}
	else{
		$r = ceil($pass_time/60);
		if ($r >= 1 and $r < 60)
		{
			// within a minute;
			if ($r > 1)
			{
				$ret = "<i class='fa fa-clock'></i> about ".$r." minutes ago";
			}
			else
			{
				$ret = "<i class='fa fa-clock'></i> about a minute ago";
			}
		}
		elseif($r >= 60 and $r < 1440)
		{
			// within an hour
			if(($r/60) > 1)
			{
				$ret = "<i class='fa fa-clock'></i> about ".ceil($r/60)." hours ago";
			}
			else
			{
				$ret = "<i class='fa fa-clock'></i> about a hour ago";
			}
		}
		elseif($r >= 1440 and $r < 10080)
		{
			//within a day
			if(($r/1440) > 1)
			{
				$ret = "<i class='fa fa-clock'></i> about ".ceil($r/1440)." days ago";
			}
			else
			{
				$ret = "<i class='fa fa-clock'></i> about a day ago";
			}
		}
		elseif($r >= 10080 and $r < 40320)
		{
			//within a week
			if(($r/10080) > 1)
			{
				$ret = "<i class='fa fa-clock'></i> about ".ceil($r/10080)." weeks ago";
			}
			else
			{
				$ret = "<i class='fa fa-clock'></i> about a week ago";
			}
		}
		elseif ($r >= 40320 and $r < 483840)
		{
			//within a month
			if(($r/40320) > 1)
			{
				$ret = "<i class='fa fa-clock'></i> about ".ceil($r/40320)." months ago";
			}
			else
			{
				$ret = "<i class='fa fa-clock'></i> about a month ago";
			}
		}
		else
		{
			//within a year;
			if(($r/483840) > 1)
			{
				$ret = "<i class='fa fa-clock'></i> about ".ceil($r/483840)." years ago";
			}
			else
			{
				$ret = "<i class='fa fa-clock'></i> about a year ago";
			}
		}
	}
	return $ret;
}
function sM($str)
{
	//$nstr = '<div style="background:#5cb85c; color:#fff; padding:12px; font-size:14px; width:70%; border-radius:8px;" align="center">'.$str.'</div>';
	$nstr = '<div style=\'padding:12px;\'><div style=\'color:#2B8E11; width:50%; opacity:.92; font-size:16px; background:#BCF8AD; border-left:#2B8E11 thick solid; padding:15px; font-family:Helvetica Neue,Helvetica,Arial,sans-serif;\'><i class=\'fa fa-check-square-o\'></i> '.$str.'</div></div>';
	return $nstr;
}
function webst()
{
	$rUri = $_SERVER['PHP_SELF'];
	
	$sec = mysql_query("select*from webstst where pname = '$rUri'") or die (mysql_error());
	if(mysql_num_rows($sec) == 1)
	{
		while ($da = mysql_fetch_array($sec))
		{
			$counta = $da['counter'];
		}
		$totalC = $counta + 1;
		$upd = mysql_query("Update webstst set counter = '$totalC' where pname ='$rUri'");
	}
	else
	{
		$i = mysql_query("insert into webstst (pname,counter) values ('$rUri', 1)");
	}

if($_COOKIE['artist']){
	$v = mysql_real_escape_string($_COOKIE['artist']);
	$za = mysql_query("select*from artists where username = '$v'");
	while($goo = mysql_fetch_array($za))
	{
		$dir = $goo['psurfed'];
	}
	$dir++;
	$p = mysql_query("update artists set psurfed = $dir where username = '$v'");
}
}
function state()
{
	$se = redef("query","select*from artists",0,0);
	while($d= redef("fetch",$se,0,0))
	{
		$j = substr($d['city'],2,strlen($d['city']));
		$id = $d['id'];
		$x = redef("query","update artists set city='$j' where id = '$id'",0,0);
	}
}
function pregs()
{
	preg_match("/^[0-9a-zA-Z]+$/","kiuh", $var);
	if(preg_match("/^[0-9a-zA-Z]+$/","kiuh/,.;;p="))
	{
		echo 'ty';
	}
}
function gRef($str){
	$j = explode("/",$str);
	$n = explode("/",$_SERVER['PHP_SELF']);
	if($j[2] == $n[2]){
		$ref = $str;
	}
	else{
		$ref = "index";
	}
	return $ref;
}
function thumbnail($nna, $img, $source, $dest, $maxw, $maxh ) {  
//$source = http://localhost/nam/dest/afo/eliazino987984820/;
    $jpg = $source.$img;
	$x = explode('.',$img);
	$pio = sizeof($x) -1;
	$format = $x[$pio];
	$cont = true;
    if( $jpg ) {
        list( $width, $height  ) = getimagesize( $jpg );
		if ( strtoupper($format) == "JPEG" || strtoupper($format) == "JPG")
		{
        $source = imagecreatefromjpeg( $jpg );
		}
		else if (strtoupper($format) == "PNG")
		{
			$source = imagecreatefrompng($jpg);
		}
		else if (strtoupper($format == "GIF"))
		{
			$source = imagecreatefromgif($jpg);
		}
		else if (strtoupper($format == "BMP"))
		{
			$source = imagecreatefromwbmp($jpg);
		}
		else
		{
			//echo 'shiiiii';
			$cont = false;
		}
        if( $maxw >= $width && $maxh >= $height ) {
            $ratio = 1;
        }elseif( $width > $height ) {
            $ratio = $maxw / $width;
        }else {
            $ratio = $maxh / $height;
        }
		if ($cont)
		{
			$thumb_width = round( $width * $ratio );
			$thumb_height = round( $height * $ratio );
			$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
			imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height );
			$path = $dest.$nna;
			imagejpeg( $thumb, $path, 75);
		}
    }
	if ($cont)
	{
		imagedestroy( $thumb );
		imagedestroy( $source );
		//unlink($jpg);
	}
}
function watermarker($tmp_f, $name, $new_name, $new_dir)
{
	ob_start();
    $disp_width_max=150;                    // used when displaying watermark choices
    $disp_height_max=80;                    // used when displaying watermark choices
    $edgePadding=15;                        // used when placing the watermark near an edge
    $quality=100;                           // used when generating the final image
    $default_watermark='watermark.png';  // the default image to use if no watermark was chosen
        
            // be sure that the other options we need have some kind of value
            /*if(!isset($_POST['save_as']))*/ $_POST['save_as']='jpeg';
            /*if(!isset($_POST['v_position']))*/ $_POST['v_position']='bottom';
            /*if(!isset($_POST['h_position']))*/ $_POST['h_position']='left';
            /*if(!isset($_POST['wm_size']))*/ $_POST['wm_size']='.2';
            /*if(!isset($_POST['watermark']))*/ $_POST['watermark']=$default_watermark;
        
            // file upload success
            $size=getimagesize($tmp_f); //$size=getimagesize($_FILES['watermarkee']['tmp_name']);
            if($size[2]==2 || $size[2]==3){
                // it was a JPEG or PNG image, so we're OK so far
                
                $original=$tmp_f; //$original=$_FILES['watermarkee']['tmp_name'];
                /*$target_name=date('YmdHis').'_'.
                    // if you change this regex, be sure to change it in generated-images.php:26
                    preg_replace('`[^a-z0-9-_.]`i','',$_FILES['watermarkee']['name']);*/
					$target_name= $new_name;
               
                $target=$new_dir."".$new_name; //$target=dirname(__FILE__).'/results/'.$target_name;
                $watermark='dep/media/'.$_POST['watermark'];
				//$watermark=dirname(__FILE__).'/watermarks/'.$_POST['watermark'];
                $wmTarget=$watermark.'.tmp';

                $origInfo = getimagesize($original); 
                $origWidth = $origInfo[0]; 
                $origHeight = $origInfo[1]; 

                $waterMarkInfo = getimagesize($watermark);
                $waterMarkWidth = $waterMarkInfo[0];
                $waterMarkHeight = $waterMarkInfo[1];
        
                // watermark sizing info
                if($_POST['wm_size']=='larger'){
                    $placementX=0;
                    $placementY=0;
                    $_POST['h_position']='right';
                    $_POST['v_position']='bottom';
                	$waterMarkDestWidth=$waterMarkWidth;
                	$waterMarkDestHeight=$waterMarkHeight;
                    
                    // both of the watermark dimensions need to be 5% more than the original image...
                    // adjust width first.
                    if($waterMarkWidth > $origWidth*1.05 && $waterMarkHeight > $origHeight*1.05){
                    	// both are already larger than the original by at least 5%...
                    	// we need to make the watermark *smaller* for this one.
                    	
                    	// where is the largest difference?
                    	$wdiff=$waterMarkDestWidth - $origWidth;
                    	$hdiff=$waterMarkDestHeight - $origHeight;
                    	if($wdiff > $hdiff){
                    		// the width has the largest difference - get percentage
                    		$sizer=($wdiff/$waterMarkDestWidth)-0.05;
                    	}else{
                    		$sizer=($hdiff/$waterMarkDestHeight)-0.05;
                    	}
                    	$waterMarkDestWidth-=$waterMarkDestWidth * $sizer;
                    	$waterMarkDestHeight-=$waterMarkDestHeight * $sizer;
                    }else{
                    	// the watermark will need to be enlarged for this one
                    	
                    	// where is the largest difference?
                    	$wdiff=$origWidth - $waterMarkDestWidth;
                    	$hdiff=$origHeight - $waterMarkDestHeight;
                    	if($wdiff > $hdiff){
                    		// the width has the largest difference - get percentage
                    		$sizer=($wdiff/$waterMarkDestWidth)+0.05;
                    	}else{
                    		$sizer=($hdiff/$waterMarkDestHeight)+0.05;
                    	}
                    	$waterMarkDestWidth+=$waterMarkDestWidth * $sizer;
                    	$waterMarkDestHeight+=$waterMarkDestHeight * $sizer;
                    }
                }else{
	                $waterMarkDestWidth=round($origWidth * floatval($_POST['wm_size']));
	                $waterMarkDestHeight=round($origHeight * floatval($_POST['wm_size']));
	                if($_POST['wm_size']==1){
	                    $waterMarkDestWidth-=2*$edgePadding;
	                    $waterMarkDestHeight-=2*$edgePadding;
	                }
                }

                // OK, we have what size we want the watermark to be, time to scale the watermark image
                resize_png_image($watermark,$waterMarkDestWidth,$waterMarkDestHeight,$wmTarget);
                
                // get the size info for this watermark.
                $wmInfo=getimagesize($wmTarget);
                $waterMarkDestWidth=$wmInfo[0];
                $waterMarkDestHeight=$wmInfo[1];

                $differenceX = $origWidth - $waterMarkDestWidth;
                $differenceY = $origHeight - $waterMarkDestHeight;

                // where to place the watermark?
                switch($_POST['h_position']){
                    // find the X coord for placement
                    case 'left':
                        $placementX = $edgePadding;
                        break;
                    case 'center':
                        $placementX =  round($differenceX / 2);
                        break;
                    case 'right':
                        $placementX = $origWidth - $waterMarkDestWidth - $edgePadding;
                        break;
                }

                switch($_POST['v_position']){
                    // find the Y coord for placement
                    case 'top':
                        $placementY = $edgePadding;
                        break;
                    case 'center':
                        $placementY =  round($differenceY / 2);
                        break;
                    case 'bottom':
                        $placementY = $origHeight - $waterMarkDestHeight - $edgePadding;
                        break;
                }
       
                if($size[2]==3)
                    $resultImage = imagecreatefrompng($original);
                else
                    $resultImage = imagecreatefromjpeg($original);
                imagealphablending($resultImage, TRUE);
        
                $finalWaterMarkImage = imagecreatefrompng($wmTarget);
                $finalWaterMarkWidth = imagesx($finalWaterMarkImage);
                $finalWaterMarkHeight = imagesy($finalWaterMarkImage);
        
                imagecopy($resultImage,
                          $finalWaterMarkImage,
                          $placementX,
                          $placementY,
                          0,
                          0,
                          $finalWaterMarkWidth,
                          $finalWaterMarkHeight
                );
                
                if($size[2]==3){
                    imagealphablending($resultImage,FALSE);
                    imagesavealpha($resultImage,TRUE);
                    imagepng($resultImage,$target,9);
                }else{
                    imagejpeg($resultImage,$target,$quality); 
                }

                imagedestroy($resultImage);
                imagedestroy($finalWaterMarkImage);
				return true;
			}
}
function resize_png_image($img,$newWidth,$newHeight,$target){
    $srcImage=imagecreatefrompng($img);
    if($srcImage==''){
        return FALSE;
    }
    $srcWidth=imagesx($srcImage);
    $srcHeight=imagesy($srcImage);
    $percentage=(double)$newWidth/$srcWidth;
    $destHeight=round($srcHeight*$percentage)+1;
    $destWidth=round($srcWidth*$percentage)+1;
    if($destHeight > $newHeight){
        // if the width produces a height bigger than we want, calculate based on height
        $percentage=(double)$newHeight/$srcHeight;
        $destHeight=round($srcHeight*$percentage)+1;
        $destWidth=round($srcWidth*$percentage)+1;
    }
    $destImage=imagecreatetruecolor($destWidth-1,$destHeight-1);
    if(!imagealphablending($destImage,FALSE)){
        return FALSE;
    }
    if(!imagesavealpha($destImage,TRUE)){
        return FALSE;
    }
    if(!imagecopyresampled($destImage,$srcImage,0,0,0,0,$destWidth,$destHeight,$srcWidth,$srcHeight)){
        return FALSE;
    }
    if(!imagepng($destImage,$target)){
        return FALSE;
    }
    imagedestroy($destImage);
    imagedestroy($srcImage);
    return TRUE;
}
function updater($user, $value)
{
	$sql = mysql_query("select * from artists where username = '$user' ") or die (mysql_error());
					while ($da = mysql_fetch_assoc($sql))
					{
						$fa = $da['star'];
					}
					$fa = $fa + $value;
					$xa = mysql_query("update artists set star = '$fa' where username = '$user'");
}
function ranker($val)
{
	$j = strlen($val);
	$rank = "";
	if($j == 1)
	{
		$last = $val;
	}
	else
	{
		$last = substr($val,$j-1,1);
		$first = substr($val,0,1);
	}
	if ($first == "1")
	{
		$rank = $val."<sup class='sc'>th</sup>";
	}
	else
	{
		if($last == "1")
		{
			$rank = $val."<sup class='sc'>st</sup>";
		}
		else if ($last == "2")
		{
			$rank = $val."<sup class='sc'>nd</sup>";
		}
		else if($last == "3")
		{
			$rank = $val."<sup class='sc'>rd</sup>";
		}
		else
		{
			$rank = $val."<sup class='sc'>th</sup>";
		}
	}
	return $rank;
}
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 30){
 $imgsize = getimagesize($source_file);
 $width = $imgsize[0];
 $height = $imgsize[1];
 $mime = $imgsize['mime'];
 switch($mime){
  case 'image/gif':
   $image_create = "imagecreatefromgif";
   $image = "imagegif";
   break;
  case 'image/png':
   $image_create = "imagecreatefrompng";
   $image = "imagepng";
   $quality = 3;
   break;
  case 'image/jpeg':
   $image_create = "imagecreatefromjpeg";
   $image = "imagejpeg";
   $quality = 30;
   break;
  default:
   return false;
   break;
 }
 $dst_img = imagecreatetruecolor($max_width, $max_height);
 $src_img = $image_create($source_file);
 $width_new = $height * $max_width / $max_height;
 $height_new = $width * $max_height / $max_width;
 //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
 if($width_new > $width){
  //cut point by height
  $h_point = (($height - $height_new) / 2);
  //copy image
  imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
 }else{
  //cut point by width
  $w_point = (($width - $width_new) / 2);
  imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
 }
 $image($dst_img, $dst_dir, $quality);
 if($dst_img)imagedestroy($dst_img);
 if($src_img)imagedestroy($src_img);
}

?>