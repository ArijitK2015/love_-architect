<?php
	
	/*
	We are geneartion the image onfly
	*/
	
	
	$base_url  =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
	$base_url .=  "://".$_SERVER['HTTP_HOST'];
	$base_url .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
	
	$base_url	 = (!empty($base_url)) ? $base_url : $_SERVER['HTTP_HOST'];
	if(!defined(base_url)) define('base_url', $base_url);
	
	$current_path 	= (defined(FILEUPLOADPATH) && FILEUPLOADPATH != '') ? FILEUPLOADPATH : __DIR__.'/';
	
	$filename 	= isset($_REQUEST['img']) 	? $_REQUEST['img'] 		: '';
	$width 		= isset($_REQUEST['width']) 	? $_REQUEST['width'] 	: '';
	$height 		= isset($_REQUEST['height']) 	? $_REQUEST['height'] 	: '';
	
	$info 		= getimagesize(str_replace(base_url, $current_path, $filename));
     $destType 	= image_type_to_mime_type($info[2]);
	
	$type		= isset($_REQUEST['type']) 	? $_REQUEST['type'] 	: '';
	
	if(empty($type))
		thumbnail_new('', $filename, $width, $height);
	elseif($type == 'aspectratio')
		resizeAspectRatio($filename, '', $destType, $width, $height);
	elseif($type == 'aspectfit')
		resizeProportional($filename, '', $destType, $width, $height);
	elseif($type == 'cropthumb')
		createThumb($filename, '', $destType, $width, $height);
	elseif($type == 'cropthumbnew')
		generate_image_thumbnail($filename, '', $width, $height);
	else
		thumbnail_new('', $filename, $width, $height);
	
	//Added on 24th Nov 2015 - Arijit
	function generate_image_thumbnail($source_image_path, $thumbnail_image_path, $destWidth, $destHeight)
	{
		// Set output quality
		$imgQuality	= 80;
	
		$search 	= base_url;
		$replace 	= $current_path;
	
		$source_image_path = str_replace($search, $replace, $source_image_path);
	
		// For small target size, override with much higher image quality: 96
		if( $destWidth < 200 || $destHeight < 200 )
		{
		    $imgQuality = 99;
		}
	
		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));
		
		$info 	= getimagesize($source_image_path);
		$destType = image_type_to_mime_type($info[2]);
		
		list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
		
		switch ($source_image_type) {
			case IMAGETYPE_GIF:
				$source_gd_image = imagecreatefromgif($source_image_path);
				break;
			case IMAGETYPE_JPEG:
				$source_gd_image = imagecreatefromjpeg($source_image_path);
				break;
			case IMAGETYPE_PNG:
				$source_gd_image = imagecreatefrompng($source_image_path);
				break;
		}
		if ($source_gd_image === false) {
			return false;
		}
		
		$source_aspect_ratio 	= $source_image_width / $source_image_height;
		$thumbnail_aspect_ratio 	= $destWidth / $destHeight;
		
		if ($source_image_width <= $destWidth && $source_image_height <= $destHeight) {
		    $thumbnail_image_width 	= $source_image_width;
		    $thumbnail_image_height 	= $source_image_height;
		    
		} elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
		    $thumbnail_image_width 	= (int) ($destHeight * $source_aspect_ratio);
		    $thumbnail_image_height 	= $destHeight;
		    
		} else {
		    $thumbnail_image_width 	= $destWidth;
		    $thumbnail_image_height 	= (int) ($destWidth / $source_aspect_ratio);
		}
		
		$thumbnail_gd_image 		= imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
		
		switch ($source_image_type)
		{
		    case IMAGETYPE_PNG:
				imagecolortransparent($thumbnail_gd_image, imagecolorallocatealpha($thumbnail_gd_image, 0, 0, 0, 127));
				imagealphablending($thumbnail_gd_image, false);
				imagesavealpha($thumbnail_gd_image, true);
		  
				break;
		    case IMAGETYPE_GIF:
				imagecolortransparent($thumbnail_gd_image, imagecolorallocatealpha($thumbnail_gd_image, 0, 0, 0, 127));
				imagealphablending($thumbnail_gd_image, false);
				imagesavealpha($thumbnail_gd_image, true);
		
			   break;
		}
		
		imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
		
		//imagejpeg($thumbnail_gd_image, '', 90);
		
		// Output
		ob_start();
  
		// Test if type is png
		if( $destType == 'image/png' || $destType == 'image/x-png' )
		{
			header('Content-Type: image/png');
			imagepng($thumbnail_gd_image, '', $pngQuality);
		}
		elseif ( $destType == 'image/gif')
		{
			header('Content-Type: image/gif');
			imagegif( $thumbnail_gd_image, '');
		}
		else
		{
			header('Content-Type: image/jpeg');
			// We default to use jpeg
			imagejpeg($thumbnail_gd_image, '', $imgQuality);
		}
	}
	
	
	function thumbnail_new($destPath, $srcPath, $destWidth, $destHeight)
	{
		$search 	= base_url;
		$replace 	= $current_path;
	
		$srcPath = str_replace($search, $replace, $srcPath);
 
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
 
		resize_new( $srcPath , $destPath , $destType , $destWidth , $destHeight , $sourceX , $sourceY , $currentWidth , $currentHeight);
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
		$image = open( $srcPath , $destType );
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
  
		// Output
		ob_start();
  
		// Test if type is png
		if( $destType == 'image/png' || $destType == 'image/x-png' )
		{
			header('Content-Type: image/png');
			imagepng($image_p, $destPath, $pngQuality);
		}
		elseif ( $destType == 'image/gif')
		{
			header('Content-Type: image/gif');
			imagegif( $image_p, $destPath);
		}
		else
		{
			header('Content-Type: image/jpeg');
			// We default to use jpeg
			imagejpeg($image_p, $destPath, $imgQuality);
		}
  
		//$output = ob_get_contents();
		//ob_end_clean();
	}
	
	
	// Resize the given image to a dest path. Src must exist
	// If original size is smaller, do not resize just make a copy
	function resize($srcPath, $destPath, $destType, $destWidth, $destHeight, $sourceX	= 0, $sourceY	= 0, $currentWidth=0, $currentHeight=0)
	{
		// Set output quality
		$imgQuality	= 100;

		// For small target size, override with much higher image quality: 96
		if( $destWidth < 200 || $destHeight < 200 )
		{
			$imgQuality = 99;
		}

		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));

		// See if we can grab image transparency
		$image	= open( $srcPath , $destType );
		if (!is_resource($image))  {
		    echo 'Image resize fail.';
		    return false;
		}

		$transparentIndex	= imagecolortransparent( $image );

		// Create new image resource
		$image_p			= ImageCreateTrueColor( $destWidth , $destHeight );
		$background		= ImageColorAllocate( $image_p , 255, 255, 255 );

		// test if memory is enough
		if($image_p == FALSE)
		{
			echo 'Image resize fail. Please increase PHP memory';
			return false;
		}

		// Set the new image background width and height
		$resourceWidth		= $destWidth;
		$resourceHeight	= $destHeight;
		
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

		// Output
		ob_start();

		// Test if type is png
		if( $destType == 'image/png' || $destType == 'image/x-png' )
		{
			header('Content-Type: image/png');
			imagepng($image_p, null, $pngQuality);
		}
		elseif ( $destType == 'image/gif')
		{
			header('Content-Type: image/gif');
			imagegif( $image_p );
		}
		else
		{
			header('Content-Type: image/jpeg');
			// We default to use jpeg
			imagejpeg($image_p, null, $imgQuality);
		}

		//$output = ob_get_contents();
		//ob_end_clean();

		// @todo, need to verify that the $output is indeed a proper image data
		//return JFile::write( $destPath , $output );
	}

	// if dest height/width is empty, then resize propotional to origianl width/height
	function resizeProportional($srcPath, $destPath, $destType, $destWidth=0, $destHeight=0)
	{
		$search 	= base_url;
		$replace 	= $current_path;
	
		$srcPath = str_replace($search, $replace, $srcPath);
		
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

		//$imageEngine	= 'auto';
		//$magickPath	= 'imagick';

		// Use imageMagick if available
//		if( class_exists('Imagick') && !empty( $magickPath ) && ($imageEngine == 'auto' || $imageEngine == 'imagick') )
//		{
//               if ( file_exists($srcPath)) {                        			
//				$thumb = new Imagick();
//				$thumb->readImage($srcPath);
//				$thumb->resizeImage($destWidth,$destHeight, MAGICK_FILTER ,1);
//				$thumb->writeImage($destPath);
//				$thumb->clear();
//				$thumb->destroy();
//	
//				return true;
//               }
//		}
//		else if( !empty( $magickPath ) && !class_exists( 'Imagick' ) )
//		{
//			// Execute the command to resize. In windows, the commands are executed differently.
//			if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
//			{
//				$file		= rtrim( $config->get( 'magickPath' ) , '/' ) .'/convert.exe';
//				$command	= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) .'/convert.exe"';
//			}
//			else
//			{
//				$file		= rtrim( $config->get( 'magickPath' ) , '/' ) .'/convert';
//				$command	= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) .'/convert"';
//			}
//
//
//			if( file_exists( $file ) && function_exists( 'exec') )
//			{
//				$execute	= $command . ' -resize ' . $destWidth . 'x' . $destHeight . ' ' . $srcPath . ' ' . $destPath;                                
//				exec( $execute );
//
//				// Test if the files are created, otherwise we know the exec failed.
//				if( file_exists( $destPath ) )
//				{
//					return true;
//				}
//			}
//		}

		// IF all else fails, we try to use GD
		resize($srcPath, $destPath, $destType, $destWidth, $destHeight);
	}

	/**
	 * Method to create a thumbnail for an image
	 *
	 * @param	$srcPath	The original source of the image.
	 * @param	$destPath	The destination path for the image
	 * @param	$destType	The destination image type.
	 * @param	$destWidth	The width of the thumbnail.
	 * @param	$destHeight	The height of the thumbnail.
	 *
	 * @return	bool		True on success.
	 */
	function createThumb($srcPath, $destPath, $destType, $destWidth=250, $destHeight=250)
	{
		$search 	= base_url;
		$replace 	= $current_path;
	
		$srcPath = str_replace($search, $replace, $srcPath);
		
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

		// IF all else fails, we try to use GD
		resize( $srcPath , $destPath , $destType , $destWidth , $destHeight , $sourceX , $sourceY , $currentWidth , $currentHeight);
	}

	function getExtension( $type )
	{
		$type = strtolower($type);

		if( $type == 'image/png' || $type == 'image/x-png' )
		{
			return '.png';
		}
		elseif ( $type == 'image/gif')
		{
			return '.gif';
		}

		// We default to use jpeg
		return '.jpg';
	}

	function isValidType( $type )
	{
        $type = strtolower($type);
        $validType = array('image/png', 'image/x-png', 'image/gif', 'image/jpeg', 'image/pjpeg');

        return in_array($type, $validType );
	}

	function isMemoryNeededExceed($filename)
	{
		// Use imagemagick if available
		$magickPath		= 'Imagick';

		if( !empty( $magickPath ) && !class_exists( 'Imagick' ) ){
			$MB = pow(1024, 2);  // number of bytes in 1M
			$K64 = 65536;    // number of bytes in 64K
			$TWEAKFACTOR = 1.5;
			$imageInfo = @getimagesize($filename);
			if($imageInfo){
				$memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
													   * $imageInfo['bits']
													   * $imageInfo['channels'] / 8
										 + $K64
									   ) * $TWEAKFACTOR
									 );
				$memory_limit = ini_get('memory_limit') * pow(1024, 2);
				if ($memoryNeeded > $memory_limit){
					return false;
				}
			}
		}
		return true;
	}


	function isValid( $file )
	{
		$config			= CFactory::getConfig();

		// Use imagemagick if available
		$imageEngine 	= 'auto';
		$magickPath	= 'imagick';

		if( class_exists('Imagick') && ($imageEngine == 'auto' || $imageEngine == 'imagick' ) )
		{
			$thumb = new Imagick();

			try
			{
				$imageOk = $thumb->readImage($file);
				$thumb->destroy();
			}
			catch( Exception $e)
			{
				$imageOk	= false;
			}
			return $imageOk;
		}
		else if( !empty( $magickPath ) && !class_exists( 'Imagick' ) )
		{
			// Execute the command to resize. In windows, the commands are executed differently.
			if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' )
			{
				$identifyFile	= rtrim( $config->get( 'magickPath' ) , '/' ) .'/identify.exe';
				$command		= 'start /D"' . rtrim( $config->get( 'magickPath' ) , '/' ) . '" identify.exe -ping "' . $file . '"';
			}
			else
			{
				$identifyFile	= rtrim( $config->get( 'magickPath' ) , '/' ) .'/identify';
				$command		= '"' . rtrim( $config->get( 'magickPath' ) , '/' ) .'/identify" -ping "' . $file . '"';
			}

			if( file_exists( $identifyFile ) && function_exists( 'exec') )
			{
				$output		= exec( $command );

				// Test if there's any output, otherwise we know the exec failed.
				if( !empty( $output ) )
				{
					return true;
				}
			}
		}


		# JPEG:
		if( function_exists( 'imagecreatefromjpeg' ) )
		{
			$im = @imagecreatefromjpeg($file);
			if ($im !== false){ return true; }
		}

		if( function_exists( 'imagecreatefromgif' ) )
		{
			# GIF:
			$im = @imagecreatefromgif($file);
			if ($im !== false) { return true; }
		}

		if( function_exists( 'imagecreatefrompng' ) )
		{
			# PNG:
			$im = @imagecreatefrompng($file);
			if ($im !== false) { return true; }
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

	function getSize( $source )
	{
		$obj		= new stdClass();
		list( $obj->width , $obj->height) = getimagesize( $source );
		return $obj;
	}

	/*
	 * Resize the thumbnail to respect the aspect ratio
	 */
	function resizeAspectRatio($source, $destination, $destType, $thumb_width, $thumb_height)
	{
		// Set output quality
		$imgQuality	= 100;

		// For small target size, override with much higher image quality: 96
		if( $destWidth < 200 || $destHeight < 200 )
		{
			$imgQuality = 99;
		}

		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));
		
		// See if we can grab image transparency
		$image	= open( $source , $destType );
		if (!is_resource($image))  {
		    echo 'Image resize fail.';
		    return false;
		}

		$width = imagesx($image);
		$height = imagesy($image);

		$original_aspect = $width / $height;
		$thumb_aspect = $thumb_width / $thumb_height;

		if($original_aspect >= $thumb_aspect) {
		   // If image is wider than thumbnail (in aspect ratio sense)
		   $new_height = $thumb_height;
		   $new_width = $width / ($height / $thumb_height);
		} else {
		   // If the thumbnail is wider than the image
		   $new_width = $thumb_width;
		   $new_height = $height / ($width / $thumb_width);
		}

		$thumb 		= imagecreatetruecolor($thumb_width, $thumb_height);
		$background	= ImageColorAllocate( $thumb , 255, 255, 255 );
		
		$targetX = 0 - ($new_width - $thumb_width) / 2;
		$targetY = 0 - ($new_height - $thumb_height) / 2;
		
		$sourceX 	 = $sourceY = 0;
		
		$destWidth  = $new_width;
		$destHeight = $new_height;
		
		$currentWidth = $width;
		$currentHeight = $height;
		
		// Resize GIF/PNG to handle transparency
		if( $destType == 'image/gif' )
		{
			$colorTransparent = imagecolortransparent($image);
			imagepalettecopy($image, $thumb);
			imagefill($thumb, 0, 0, $colorTransparent);
			imagecolortransparent($thumb, $colorTransparent);
			imagetruecolortopalette($thumb, true, 256);
			imagecopyresized($thumb, $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth , $destHeight , $currentWidth , $currentHeight );
		}
		else if( $destType == 'image/png' || $destType == 'image/x-png')
		{
			// Disable alpha blending to keep the alpha channel
			imagealphablending( $thumb , false);
			imagesavealpha($thumb,true);
			$transparent	= imagecolorallocatealpha($thumb, 255, 255, 255, 127);

			imagefilledrectangle($thumb, 0, 0, $resourceWidth, $resourceHeight, $transparent);
			imagecopyresampled($thumb , $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth, $destHeight, $currentWidth, $currentHeight);
		}
		else
		{
			// Turn off alpha blending to keep the alpha channel
			imagealphablending( $thumb , false );
			imagecopyresampled( $thumb , $image, $targetX, $targetY, $sourceX, $sourceY, $destWidth , $destHeight , $currentWidth , $currentHeight );
		}		
		
		// Test if type is png
		if( $destType == 'image/png' || $destType == 'image/x-png' )
		{
			header('Content-Type: image/png');
			imagepng($thumb, null, $pngQuality);
		}
		elseif ( $destType == 'image/gif')
		{
			header('Content-Type: image/gif');
			imagegif( $thumb );
		}
		else
		{
			header('Content-Type: image/jpeg');
			// We default to use jpeg
			imagejpeg($thumb, null, $imgQuality);
		}
	}

	function autoRotate($imagePath, $orientation = null)
	{
		if(!$orientation) {
			// Read orientation data from original file
			$orientation = $this->getOrientation($imagePath);
		}

		// Rotate resized files ince it is smaller
		switch ($orientation) {
			case 1: // nothing
				break;

			case 2: // horizontal flip
				break;

			case 3: // 180 rotate left
				$this->rotate($imagePath, $imagePath, 180);
				break;

			case 4: // vertical flip
				break;

			case 5: // vertical flip + 90 rotate right
				break;

			case 6: // 90 rotate right
				$this->rotate($imagePath, $imagePath, -90);
				break;

			case 7: // horizontal flip + 90 rotate right
				break;

			case 8: // 90 rotate left
				$this->rotate($imagePath, $imagePath, 90);
				break;
		}
	}

	/**
	 * Rotate the source image and store it to dest path
	 * Return true if successful and false otherwise
	 */
	function rotate( $srcPath, $destPath, $degrees )
	{
		// Set output quality
		$imgQuality		= 100;

		$imageEngine 		= 'auto';
		$magickPath		= 'imagick';

		$info			= getimagesize( $srcPath );
		$imgType			= image_type_to_mime_type($info[2]);
		$rotate			= null;

		// Use imageMagick if available
		if( class_exists('Imagick') && ($imageEngine == 'auto' || $imageEngine == 'imagick' ) )
		{
			//$jconfig	= JFactory::getConfig();
			//$tmpPath	= $jconfig->getValue('config.tmp_path') .'/'. JFile::getName($srcPath);
			$tmpPath		= $app->getCfg('tmp_path') .'/'. JFile::getName($srcPath);

			$image 		= new Imagick();
			$image->readImage($srcPath);
			// ImageMagick seems to rotate it counter-clockwise, hence have
			// to multiply the degress by -1
			$image->rotateImage(new ImagickPixel(), $degrees * (-1));
			$image->writeImage($tmpPath);
			$image->clear();
			$image->destroy();

			JFile::move($tmpPath, $destPath);

			return true;
		}
		else if($imgType == 'image/png' && function_exists('imagecreatefrompng')){
			$source = imagecreatefrompng($srcPath);

			if($degrees == '90'){
				$rotatedImage = CImageHelper::rotatePNGImage($source);
			}else if($degrees == '-90'){
				$rotatedImage = CImageHelper::rotatePNGImage(CImageHelper::rotatePNGImage(CImageHelper::rotatePNGImage($source)));
			}

			ob_start();

			imagepng($rotatedImage);
			$output = ob_get_contents();

			ob_end_clean();

			// @todo, need to verify that the $output is indeed a proper image data
			return JFile::write( $destPath , $output );
		}
		else if (($imgType == 'image/jpeg') && function_exists('imagecreatefromjpeg') && function_exists('imagerotate'))
		{
			// @todo: Support rotation for other image type other than JPEG
			// Load
			$source = imagecreatefromjpeg($srcPath);

			// Rotate
			$rotate = imagerotate($source, $degrees, 0);

			if($rotate){
				// Output
				ob_start();

				// Test if type is png
// 				if( $destType == 'image/png' || $destType == 'image/x-png' )
// 				{
// 					imagepng( $image_p );
// 				}
// 				elseif ( $destType == 'image/gif')
// 				{
// 					imagegif( $image_p );
// 				}
// 				else
				{
					// We default to use jpeg
					imagejpeg($rotate, null, $imgQuality);
				}

				$output = ob_get_contents();
				ob_end_clean();

				// @todo, need to verify that the $output is indeed a proper image data
				return JFile::write( $destPath , $output );
			}
		}

		return false;

	}

	/**
	 * Rotate png image by 90 degree
	 */
	function rotatePNGImage($image)
	{
		$width = imagesx($image);
		$height = imagesy($image);
		$newImage = imagecreatetruecolor($height, $width);
		imagealphablending($newImage, false);//drawing color -> alpha channel information, replacing the destination pixel
		imagesavealpha($newImage, true);

		for($w=0; $w<$width; $w++){
		  for($h=0; $h<$height; $h++) {
			  $ref = imagecolorat($image, $w, $h);
			  imagesetpixel($newImage, $h, ($width-1)-$w, $ref);//assign width size to the height to produce a 90 degree
		  }
		}
		return $newImage;
	}

	/**
	 * Detect image Orientation. Return false if not found
	 */
	function getOrientation($srcPath)
	{

		// Make sure the function exist
		if(!function_exists('exif_read_data')){
			return false;
		}

		$exif = array();

		try {
			$exif = @exif_read_data($srcPath);
		} catch (Exception $e) {
			return false;
		}

		// See if orientation data is there
		if(!isset($exif['Orientation'])){
			return false;
		}
		return $exif['Orientation'];
	}

	/**
	 * Method to add watermark on existing image.
	 *
	 * @param	string	$backgroundImagePath	The path to the image that needs to be added with watermark.
	 * @param	string	$destinationPath		The path to the image output
	 * @param	string	$destinationType		The type of the output file
	 * @param	string	$watermarkImagePath		The path to the watermark image.
	 * @param	int		$positionX				The x position of where the watermark should be positioned.
	 * @param	int		$positionY				The y position of where the watermark should be positioned.
	 *
	 * @return	bool	True on sucess.
	 **/
	function addWatermark( $backgroundImagePath , $destinationPath , $destinationType , $watermarkImagePath , $positionX = 0 , $positionY = 0 , $deleteBackgroundImage = true )
	{
		// Set output quality
		$config		= CFactory::getConfig();
		$imgQuality	= $config->get('output_image_quality');
		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));

		$watermarkInfo	= getimagesize( $watermarkImagePath );
		$background		= CImageHelper::open( $backgroundImagePath , $destinationType );
		$watermark		= CImageHelper::open( $watermarkImagePath , $watermarkInfo['mime'] );
		list( $backgroundWidth , $backgroundHeight ) 		= getimagesize( $backgroundImagePath );

		// Get overlay image width and hight
		$watermarkWidth		= imagesx( $watermark );
		$watermarkHeight	= imagesy( $watermark );

		// Combine background image and watermark into a single output image
		imagecopy($background , $watermark , $positionX , $positionY , 0 , 0 , $watermarkWidth , $watermarkHeight);

		// Output
		ob_start();

		// Test if type is png
		if( $destinationType == 'image/png' || $destinationType == 'image/x-png' )
		{
			imagepng($background, null, $pngQuality);
		}
		elseif ( $destinationType == 'image/gif')
		{
			imagegif( $background );
		}
		else
		{
			imagejpeg($background, null, $imgQuality);
		}

		$output = ob_get_contents();
		ob_end_clean();

		// Delete old image
		if( file_exists( $backgroundImagePath ) && $deleteBackgroundImage )
		{
			JFile::delete( $backgroundImagePath );
		}

		// Free any memory from the existing image resources
		imagedestroy( $background );
		imagedestroy( $watermark );

		return JFile::write( $destinationPath , $output );
	}

	/**
	 * Retrieve the proper x and y position depending on the user's choice of the watermark position.
	 **/
	function getPositions( $location , $imageWidth , $imageHeight , $watermarkWidth , $watermarkHeight )
	{
		$position	= new stdClass();

		$position->x = null;
		$position->y = null;
		// @rule: Get the appropriate X/Y position for the avatar
		switch( $location )
		{
			case 'top':
				$position->x	= ($imageWidth / 2) - ( $watermarkWidth / 2 );
				$position->y	= 0;
				break;
			case 'bottom':
				$position->x	= ($imageWidth / 2) - ( $watermarkWidth / 2 );
				$position->y	= $imageHeight - $watermarkHeight;
				break;
			case 'left':
				$position->x	= 0;
				$position->y	= ( $imageHeight / 2 ) - ($watermarkHeight / 2);
				break;
			case 'right':
				$position->x 	= $imageWidth - $watermarkWidth;
				$position->y	= ( $imageHeight / 2 ) - ($watermarkHeight / 2);
				break;
		}
		return $position;
	}

	/**
	 * Retrieves the appropriate image file name which is already hashed.
	 *
	 * @param	string	$data	A unique data to be hashed
	 *
	 **/
	function getHashName( $data )
	{
		$name	= JApplication::getHash( $data );
		$name	= substr( $name , 0 , 24 );

		return $name;
	}