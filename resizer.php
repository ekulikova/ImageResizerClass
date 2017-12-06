<?php
include ('ClassImageResizer.php');

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

list($func, $dir,$w,$h, $skip_small)= array_slice($argv,1,5);

function resizeDir($func,$dir,$w,$h,$skip_small){
	$files = scandir($dir);
 
	foreach($files as $f){
		$full_name = $dir.'/'.$f;
			
		if(is_dir($full_name)){
			
			if($f!='.' && $f!='..'){
				echo "dir ok $full_name \n";
				resizeDir($func,$full_name,$w,$h,$skip_small);
			}
			
		} else {
			
			try{
				$img = new imageResizer($full_name);
				
				switch($func){
					
					case 'resize':
						$img->resize($w,$h);
						break;
					case 'resizeToHeight':
						$img->resizeToHeight($h,$skip_small);
						break;
					case 'resizeToWidth':
						$img->resizeToWidth($w,$skip_small);
						break;
					case 'resizeToHeightWidth':
						$img->resizeToHeightWidth($w,$h,$skip_small);
						break;
					default:
						die "Bad function name $func \n";
				}
				
				$img->save($full_name);
			}
			catch(ImageResizerException $e){
				echo $e;
			}
				
		}
		
	}
}

resizeDir($func,$dir,$w,$h,$skip_small);
 
 