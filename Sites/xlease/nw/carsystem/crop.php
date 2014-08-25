<?php
session_start();
include("../../config/config.php");
// ideally one day this can do more than one image... 
// they would be stacked up to crop all at once in 
// Impromptu.. thus returning an array
date_default_timezone_set('UTC');

ini_set('display_errors',1);
ini_set('log_errors',1);
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('CURR_DIR', dirname(__FILE__) . DS);
define('UPLOAD_DIR', CURR_DIR . $_SESSION['croppath']. DS);

foreach($_POST['imgcrop'] as $k => $v) {

	$targetPath = UPLOAD_DIR;
	$thumnailspath = $_SESSION['thumnailspath'] . $v['filename'];
	$targetFile =  str_replace('//','/',$targetPath) . $v['filename'];
	$postid = $_SESSION['postID'];
	$imagename = $v['filename'];
	$date = date("Y-m-d H:i:s");
	
	//crop our image..	
	require "gd_image.php";
	$gd = new GdImage();

	$gd->crop($targetFile, $v['x'], $v['y'], $v['w'], $v['h']);
	
	$ar = $gd->getAspectRatio($v['w'], $v['h'], 280, 0);
	$thumnailspath = $gd->createName($thumnailspath,"");
	$gd->copy($targetFile, $thumnailspath);
	$gd->resize($thumnailspath, $ar['w'], $ar['h']);
	$sql="insert into carsystem.\"productImage\"(\"postID\",\"imageName\",\"uploadDate\") values('$postid','$imagename','$date');";
	pg_query($sql);
	
	//generate thumb or whatever else you like...
}

echo "1";
