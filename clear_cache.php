<?php 

$cacheDir = 'cache';
if (file_exists($cacheDir) and is_dir($cacheDir)) {
	$justNow = new DateTime("now");
	$cacheDate = new DateTime(date ("Y-m-d H:i:s.", filemtime($cacheDir)));
	$interval = $justNow->diff($cacheDate);
	if($interval->i>5){
		if( deleteDirAndFilesInIt($cacheDir) ){
		}
	}
}
