<?php

require_once 'RecursiveImageResizer.php';

use EKulikova\RecursiveImageResizer;
use EKulikova\RecursiveImageResizerException;


if (version_compare(PHP_VERSION, '7.0.0') >= 0 && !class_exists('PHPUnit_Framework_TestCase')) {
	class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

class RecursiveImageResizerTest extends PHPUnit_Framework_TestCase
{

	private $structure = array(
		'dirs' => [],
		'files' => [],
	);

	private $orig_width = 500;
	private $orig_height = 500;
	private $img_type = 'jpeg';

  /**
	 * Helpers
	 */

   private function getDirName($currentDepth){

      $tmp_dir = sys_get_temp_dir();

      for($i = 1; $i<=$currentDepth; $i++){
        $tmp_dir .= '/RecursiveImageResizer'.$i;
      }

      return $tmp_dir;
   }

	 private function getTempFile($dirName)
	 {
		 $tmp_file = tempnam($dirName, 'RecursiveImageResizerTest');

		 return $tmp_file;
	 }

	 private function createImage($dirName){

		 $image = imagecreatetruecolor($this->orig_width, $this->orig_height);

		 $filename = $this->getTempFile($dirName);

		 $output_function = 'image' . $this->img_type;

		 if( $output_function($image, $filename)){

			 $this->structure['files'][] = $filename;

			 return $filename;

		 }

		 return false;

	 }

	 private function createImages($dirName, $quantity){

		 for( $i=1; $i<=$quantity; $i++ ){

			 	$this->createImage($dirName);

		 }

	 }

	 private function createDir($dirName){

		 if( mkdir($dirName) ){

				 $this->structure['dirs'][] = $dirName;

				 return $dirName;

		 }

		 return false;

	 }

   private function createStructure($depth, $quantity){

      for($i = 1; $i <= $depth; $i++){
        
				$dirName = $this->getDirName($i);

				if ( $this->createDir( $dirName ) ){

					$this->createImages($dirName, $quantity);

				}

      }

   }

	 private function destroyStructure(){

		 	// foreach delete all files

			//foreach delete all dirs
			foreach( array_reverse( $this->structure['dirs'] ) as $dir ){

					rmdir($dir);

			}

	 }

   /**
   * Tests
   */

   public function testGetImages(){

      $dir = $this->createStructure(3, 2);

      /*$rec = new RecursiveImageResizer($dir);

      $images = $rec->getImages(0);

      $this->assertEquals( count($images) ,6 );
*/

var_dump ($this->structure);

			//$this->destroyStructure();
   }

  /**
  * Resize tests
  */
/*
  public function testResize(){

      //create structure
      $dir = $this->createStructure();

      $rec = new RecursiveImageResizer($dir);

      $rec->resize(100, 100, 0);

      // check result

      //destroy structure

  }
*/
}