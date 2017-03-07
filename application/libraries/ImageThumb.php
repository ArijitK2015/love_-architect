<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Library For Image Cropping and making image thumb with required height and width
 */

 class ImageThumb{
    
    	public function thumbnail_new($destPath, $srcPath, $destWidth, $destHeight)
	{
			$currentWidth = $destWidth;
			$currentHeight = $destHeight;
			list( $currentWidth , $currentHeight )	= getimagesize( $srcPath );
	 
			$info = getimagesize($srcPath);
			$destType = image_type_to_mime_type($info[2]);
			
			$origWidth 	= $currentWidth;
			$origHeight = $currentHeight;
			$sourceX = 0;
			$sourceY = 0;
		
		// Get the image size for the current original photo
		list( $currentWidth , $currentHeight )	= getimagesize( $srcPath );
 
		// Find the correct x/y offset and source width/height. Crop the image squarely, at the center.
		if( $currentWidth == $currentHeight )
		{
		   $sourceX = 0;
		   $sourceY = 0;
		}
		else if( $currentWidth > $currentHeight )
		{
		   $sourceX			= intval( ( $currentWidth - $currentHeight ) / 2 );
		   $sourceY 			= 0;
		   $currentWidth		= $currentHeight;
		}
		else
		{
		   $sourceX		= 0;
		   $sourceY		= intval( ( $currentHeight - $currentWidth ) / 2 );
		   $currentHeight	= $currentWidth;
		}
			
	   $this->resize_new( $srcPath , $destPath , $destType , $destWidth , $destHeight , $sourceX , $sourceY , $currentWidth , $currentHeight);
	}
	
	// if dest height/width is empty, then resize propotional to origianl width/height
	function resizeProportional($destPath, $srcPath, $destWidth=300, $destHeight=300)
	{
		$info 	= getimagesize($srcPath);
		$destType = image_type_to_mime_type($info[2]);
		
		list($currentWidth, $currentHeight) = getimagesize( $srcPath );

		//$config = CFactory::getConfig();
		//$app = JFactory::getApplication();

		if($currentWidth == 0 || $currentHeight==0 ){
			return false;
		}
		if($destWidth == 0)
		{
			// Calculate the width if the width is not set.
			$destWidth = intval($destHeight/$currentHeight * $currentWidth);
		}
		else
		{
			// Calculate the height if the width is set.
			$destHeight = intval( $destWidth / $currentWidth * $currentHeight);
		}

		// IF all else fails, we try to use GD
		$this->resize_new($srcPath, $destPath, $destType, $destWidth, $destHeight);
	}
	
	function resize_new($srcPath, $destPath, $destType, $destWidth, $destHeight, $sourceX	= 0, $sourceY	= 0, $currentWidth=0, $currentHeight=0)
	{            
		// Set output quality
		//$config		= CFactory::getConfig();
		$imgQuality	= 80;
 
		// For small target size, override with much higher image quality: 96
		if( $destWidth < 200 || $destHeight < 200 )
		{
			 $imgQuality = 99;
		}
 
		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));
 
		// See if we can grab image transparency
		$image = $this->open( $srcPath , $destType );
		if (!is_resource($image))  {
			echo 'Image resize fail.';
			return false;
		}
 
		$transparentIndex	= imagecolortransparent( $image );
 
		// Create new image resource
		$image_p			= ImageCreateTrueColor( $destWidth , $destHeight );
		$background			= ImageColorAllocate( $image_p , 255, 255, 255 );
 
		// test if memory is enough
		if($image_p == FALSE)
		{
			echo 'Image resize fail. Please increase PHP memory';
			return false;
		}
 
		// Set the new image background width and height
		$resourceWidth		= $destWidth;
		$resourceHeight		= $destHeight;
 
		if(empty($currentHeight) && empty($currentWidth))
		{
			list($currentWidth , $currentHeight) = getimagesize( $srcPath );
		}
		// If image is smaller, just copy to the center
		$targetX = 0;
		$targetY = 0;
 
		// If the height and width is smaller, copy it to the center.
		if( $destType != 'image/jpg' &&	$destType != 'image/jpeg' && $destType != 'image/pjpeg' && $destType == 'image/png' && $destType == 'image/x-png')
		{
			if( ($currentHeight < $destHeight) && ($currentWidth < $destWidth) )
			{
				$targetX = intval( ($destWidth - $currentWidth) / 2);
				$targetY = intval( ($destHeight - $currentHeight) / 2);
    
				// Since the
				$destWidth = $currentWidth;
				$destHeight = $currentHeight;
			}
		}
		
		//check image exif
		$orientation 		= 0;
		if( function_exists( 'exif_read_data' ))
		{
			$exif 		= exif_read_data($srcPath);
			//echo '<pre>'; print_r($exif); echo '</pre>';
			if(!empty($exif)) $orientation 	= (isset($exif['Orientation'])) ? $exif['Orientation'] : 0;
			//echo $orientation; die;
		}
		
		
		// Resize GIF/PNG to handle transparency
		if( $destType == 'image/gif' )
		{
			$colorTransparent = imagecolortransparent($image);
			imagepalettecopy($image, $image_p);
			imagefill($image_p, 0, 0, $colorTransparent);
			imagecolortransparent($image_p, $colorTransparent);
			imagetruecolortopalette($image_p, true, 256);
			imagecopyresized($image_p, $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth , $destHeight , $currentWidth , $currentHeight );
		}
		else if( $destType == 'image/png' || $destType == 'image/x-png')
		{
			// Disable alpha blending to keep the alpha channel
			imagealphablending( $image_p , false);
			imagesavealpha($image_p,true);
			$transparent		= imagecolorallocatealpha($image_p, 255, 255, 255, 127);
   
			imagefilledrectangle($image_p, 0, 0, $resourceWidth, $resourceHeight, $transparent);
			imagecopyresampled($image_p , $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth, $destHeight, $currentWidth, $currentHeight);
		}
		else
		{
			// Turn off alpha blending to keep the alpha channel
			imagealphablending( $image_p , false );
			imagecopyresampled( $image_p , $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth , $destHeight , $currentWidth , $currentHeight );
		}
 
		//Now Fix the Orientation
		switch($orientation) {
			case 3:
				$image_p = imagerotate($image_p, 180, 0);
				break;
			case 6:
				$image_p = imagerotate($image_p, -90, 0);
				break;
			case 8:
				$image_p = imagerotate($image_p, 90, 0);
				break;
		}
 
		// Output
		ob_start();
 
		// Test if type is png
		if( $destType == 'image/png' || $destType == 'image/x-png' )
		{
			//header('Content-Type: image/png');
			imagepng($image_p, $destPath, $pngQuality);
		}
		elseif ( $destType == 'image/gif')
		{
			//header('Content-Type: image/gif');
			imagegif( $image_p, $destPath);
		}
		else
		{
			//header('Content-Type: image/jpeg');
			// We default to use jpeg
			imagejpeg($image_p, $destPath);
		}
 
		//$output = ob_get_contents();
		ob_end_clean();
	}
	
	
	function generate_image_base64($source_image, $store_path, $destWidth, $destHeight, $x_axis, $y_axis, $h_axis, $w_axis, $do_thumb)
	{
		// Set output quality
		$imgQuality	= 80;
		$name		= '';
		// For small target size, override with much higher image quality: 96
		if( $destWidth < 200 || $destHeight < 200 )
		{
		    $imgQuality = 99;
		}
  
		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));
		
		$store_type = 'jpeg'; $is_uploaded = 0;
		
		//check image type
		$img_data_det 		= explode(';', $source_image);
		$image_type_det 	= (isset($img_data_det[0])) ? explode(':', $img_data_det[0]) : 'image/jpg';
		
		if($image_type_det[1] 	== 'image/jpg' || $image_type_det[1] == 'image/jpeg')
			$img = str_replace('data:image/jpeg;base64,', '', $source_image); 	$store_type = 'jpeg';
		if($image_type_det[1] 	== 'image/png')
			$img = str_replace('data:image/png;base64,', '', $source_image); 	$store_type = 'png';
		
		$name				= time() . '.'.$store_type;
		$img_to_store_path		= $store_path . time() . '.'.$store_type;
		$img_to_store_path_thumb	= $store_path.'thumb/' . time() . '.'.$store_type;
		
		$ifp 	= fopen($img_to_store_path, "wb"); 
		$data 	= explode(',', $source_image);
	 
		if(fwrite($ifp, base64_decode($img)))
			$is_uploaded = 1;
		
		fclose($ifp);
		
		if($is_uploaded == 1)
		{
			if($x_axis != '' && $y_axis != '' && $h_axis != '' && $w_axis!= '')
			{
				if($image_type_det[1] == 'image/jpg' || $image_type_det[1] == 'image/jpeg')
				{
					$im = imagecreatefromjpeg($img_to_store_path );
		
					$ini_x_size = getimagesize($img_to_store_path)[0];
					$ini_y_size = getimagesize($img_to_store_path)[1];
					
					//the minimum of xlength and ylength to crop.
					$crop_measure = min($ini_x_size, $ini_y_size);
					
					$to_crop_array = array('x' => $x_axis , 'y' => $y_axis, 'width' => $crop_measure, 'height'=> $crop_measure);
					$thumb_im = imagecrop($im, $to_crop_array);
					
					imagejpeg($thumb_im, $img_to_store_path, $imgQuality);
					
					if($do_thumb == 1)
					{
						$srcPath		= $img_to_store_path;
						$destPath1 	= $img_to_store_path_thumb;
						$destWidth1	= $destWidth;
						$destHeight1	= $destHeight;
						
						$this->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
					}
				}
				elseif($image_type_det[1] == 'image/png')
				{
					$im 			= @imagecreatefrompng($img_to_store_path);
					$ini_x_size 	= getimagesize($img_to_store_path)[0];
					$ini_y_size 	= getimagesize($img_to_store_path)[1];
					
					//the minimum of xlength and ylength to crop.
					$crop_measure = min($ini_x_size, $ini_y_size);
					
					$to_crop_array = array('x' => $x_axis , 'y' => $y_axis, 'width' => $crop_measure, 'height'=> $crop_measure);
					$thumb_im = imagecrop($im, $to_crop_array);
					
					imagepng($thumb_im, $img_to_store_path, $pngQuality);
					
					if($do_thumb == 1)
					{
						$srcPath		= $img_to_store_path;
						$destPath1 	= $img_to_store_path_thumb;
						$destWidth1	= $destWidth;
						$destHeight1	= $destHeight;
						$this->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
					}
				}	
			}
			else
			{	
				if($do_thumb == 1)
				{
					$srcPath		= $img_to_store_path;
					$destPath1 	= $img_to_store_path_thumb;
					$destWidth1	= $destWidth;
					$destHeight1	= $destHeight;
					
					$this->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
				}
			}
		}
		
		return $name;
	}
	
	
	function open($file , $type)
	{
		// @rule: Test for JPG image extensions
		if( function_exists( 'imagecreatefromjpeg' ) && ( ( $type == 'image/jpg') || ( $type == 'image/jpeg' ) || ( $type == 'image/pjpeg' ) ) )
		{

			$im	= @imagecreatefromjpeg( $file );

			if( $im !== false ) { return $im; }
		}

		// @rule: Test for png image extensions
		if( function_exists( 'imagecreatefrompng' ) && ( ( $type == 'image/png') || ( $type == 'image/x-png' ) ) )
		{
			$im	= @imagecreatefrompng( $file );

			if( $im !== false ) { return $im; }
		}

		// @rule: Test for png image extensions
		if( function_exists( 'imagecreatefromgif' ) && ( ( $type == 'image/gif') ) )
		{
			$im	= @imagecreatefromgif( $file );

			if( $im !== false ) { return $im; }
		}

		if( function_exists( 'imagecreatefromgd' ) )
		{
			# GD File:
			$im = @imagecreatefromgd($file);
			if ($im !== false) { return true; }
		}

		if( function_exists( 'imagecreatefromgd2' ) )
		{
			# GD2 File:
			$im = @imagecreatefromgd2($file);
			if ($im !== false) { return true; }
		}

		if( function_exists( 'imagecreatefromwbmp' ) )
		{
			# WBMP:
			$im = @imagecreatefromwbmp($file);
			if ($im !== false) { return true; }
		}

		if( function_exists( 'imagecreatefromxbm' ) )
		{
			# XBM:
			$im = @imagecreatefromxbm($file);
			if ($im !== false) { return true; }
		}

		if( function_exists( 'imagecreatefromxpm' ) )
		{
			# XPM:
			$im = @imagecreatefromxpm($file);
			if ($im !== false) { return true; }
		}

		// If all failed, this photo is invalid
		return false;
	}
    
}
?>