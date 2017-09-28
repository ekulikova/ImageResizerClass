<?php
namespace ImageResizer;

class imageResizer{
	private $image;
	private $source;
	private $width;
	private $height;
	private $type;
	
	
	public function __construct($source){
		
		 if (!is_file($filename))) {
            throw new ImageResizerException('File does not exist');
        }
        
		$this->source=$source;
		list($this->width, $this->height, $this->type) = getimagesize($source);
		
		if(!$this->wigdth || !$this->height){
			throw new ImageResizerException('File is not an image');
		}
				 
		if( $this->type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($source);       
		} elseif( $this->type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($source);       
		} elseif( $this->type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($source);       
		} else {
			throw new ImageResizerException('Unsupported image type');
		}
		
		if (!$this->image) {
            throw new ImageResizerException('Could not load image');
        }
				 
	}
		
	public function __destruct(){
		return imagedestroy($this->image);
	}
	
	public function update($new_image){
		
		$this->image=$new_image;
		$this->height=imagesy($this->image);
		$this->widht=imagesx($this->image);
		
	}
		
	public function save($filename,$compression=75,$permissions=null){
		
		$filename or $filename=$this->source;
		
		if( $this->type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image,$filename,$compression);       
		} elseif( $this->type == IMAGETYPE_GIF ) {
			imagegif($this->image,$filename);       
		} elseif( $this->type == IMAGETYPE_PNG ) {
			imagepng($this->image,$filename);       
		}       
			
		if( $permissions != null) {
			chmod($filename,$permissions);       
		}
			
	}
		
	public function resize($new_width,$new_height){
			
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height);
		$this->update($new_image);
				
	}
	
	public function resize_save_proportion($w,$h,$skip_small=1){
		
		if($skip_small && $this->width<=$w && $this->height<=$h){
			return;
		}
		else{
			$ratio = max($this->width/$w,$this->height/$h);
			$new_width=$this->width/$ratio;
			$new_height=$this->height/$ratio;
			echo "$ratio $new_width,$new_height \n";
			$this->resize($new_width,$new_height);
		}
		
	}
	
	public static function resizeDir($dir,$new_width,$new_height,$skip_small=1,$func_name='resize'){
	echo "$func_name \n";
		$files = scandir($dir);
 
		foreach($files as $f){
			$full_name = $dir.'/'.$f;
			
			if(is_dir($full_name)){
				if($f!='.' && $f!='..'){
					//imageResizer::resizeDir($full_name);
				}
			} else { echo "$full_name \n";
				$img = new imageResizer($full_name);
				$img->$func_name($new_width,$new_height,$skip_small);
				$img->save($full_name);
			}
		
		}
	}
	
}

class ImageResizerException extends \Exception
{
}