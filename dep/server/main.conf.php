<?php
require_once("defines.php");
error_reporting(0);
//We start sessions

/******************************************************
------------------Required Configuration---------------
Please edit the following variables so the members area
can work correctly.
******************************************************/

//We log to the DataBase
$jr = redef("",'localhost','','');
redef("dbcon","naijimtp_kunsana",$jr,0) or die("Could not select database");

//Email
$info = 'info@kunsana.com';
$help = 'help@kunsana.com';
$marketing = 'marketing@kunsana.com';


$shortcut = '<link rel="shortcut icon" href="bins/logoico.fw.png" />';
$base = '<base href="http://www.kunsana.com"  />';
$baseLink = 'http://www.kunsana.com';
?>