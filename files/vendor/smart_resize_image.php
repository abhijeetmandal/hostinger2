<?php
  function smart_resize_image($file,
                              $width              = 0,
                              $height             = 0,
                              $proportional       = true,
                              $crop               = true,
                              $output             = 'browser',
                              $crop_ratio         = '1:2') {
      
    if ( $height <= 0 && $width <= 0 ) return false;

    # Setting defaults and meta
    $info                         = getimagesize($file);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    $offsetX                      = 0;
    $offsetY                      = 0;
    list($width_old, $height_old) = $info;

    # Calculating proportionality
    if ($proportional && !$crop) {
        if      ($width  == 0)  $factor = $height/$height_old;
        elseif  ($height == 0)  $factor = $width/$width_old;
        else                    $factor = min( $width / $width_old, $height / $height_old );

        $final_width  = round( $width_old * $factor );
        $final_height = round( $height_old * $factor );
    } else if ($proportional) {
        // Ratio cropping
        $crop_ratio      = explode(':', (string) $crop_ratio);
        if (count($crop_ratio) == 2) {
            $ratioComputed = $width_old / $height_old;
            $crop_ratioComputed  = (float) $crop_ratio[0] / (float) $crop_ratio[1];
            
            if ($ratioComputed < $crop_ratioComputed) {
                // Image is too tall so we will crop the top and bottom
                $origHeight = $height_old;
                $height_old     = $width_old / $crop_ratioComputed;
                $offsetY    = ($origHeight - $height_old) / 2;

                $height = $height / $crop_ratioComputed;
            }
            else if ($ratioComputed > $crop_ratioComputed) {
                // Image is too wide so we will crop off the left and right sides
                $origWidth  = $width_old;
                $width_old      = $height_old * $crop_ratioComputed;
                $offsetX    = ($origWidth - $width_old) / 2;

                $width = $width / $crop_ratioComputed;
            }
        }

        $final_width = $width;
        $final_height = $height;
    } else {
        $final_width = ( $width <= 0 ) ? $width_old : $width;
        $final_height = ( $height <= 0 ) ? $height_old : $height;
    }

    # Loading image to memory according to type
    switch ( $info[2] ) {
        case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
        case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
        case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
        default: return false;
    }
    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);

      if ($transparency >= 0) {
        $transparent_color  = imagecolorsforindex($image, $trnprt_indx);
        $transparency       = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }

    imagecopyresampled($image_resized, $image, 0, 0, $offsetX, $offsetY, $final_width, $final_height, $width_old, $height_old);

    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination
    switch ( $info[2] ) {
      case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output);   break;
      case IMAGETYPE_PNG:   imagepng($image_resized, $output);    break;
      default: return false;
    }

    return true;
  }
?>