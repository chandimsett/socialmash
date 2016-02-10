<?php
//include('database-connect.php');
//include('session.php');
/**
 * Image resize
 * @param int $width
 * @param int $height
 */
$width=0;
$height=0;
//This function creates the resized image by taking $width and $height as the newly created resolution
function resize($path){
  global $width,$height;
  /* Get original image x y*/
  list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
  /* calculate new image size with ratio */
  $ratio = max($width/$w, $height/$h);
  $h = ceil($height / $ratio);
  $x = ($w - $width / $ratio) / 2;
  $w = ceil($width / $ratio);
  /* new file name */
  //$path = 'uploads/'.$width.'x'.$height.'_'.$_FILES['image']['name'];
  /* read binary data from image file */
  $imgString = file_get_contents($_FILES['image']['tmp_name']);
  /* create image from string */
  $image = imagecreatefromstring($imgString);
  $tmp = imagecreatetruecolor($width, $height);
  imagecopyresampled($tmp, $image,
    0, 0,
    $x, 0,
    $width, $height,
    $w, $h);
  /* Save image */
  switch ($_FILES['image']['type']) {
    case 'image/jpeg':
      imagejpeg($tmp, $path, 85);
      break;
    case 'image/png':
      imagepng($tmp, $path, 0);
      break;
    case 'image/gif':
      imagegif($tmp, $path);
      break;
    default:
      exit;
      break;
  }
  return $path;
  /* cleanup memory */
  imagedestroy($image);
  imagedestroy($tmp);
}
function aspect_ratio($image_width,$image_height,$frame_width,$frame_height)//Corrects the aspect ratio of the image according to the frame given
{
  global $height,$width;
  if($image_width>=$image_height&&$image_width>$frame_width)
  {
    $width=$frame_width;
    $height=$frame_width*($image_height/$image_width);
  }
  else if($image_height>=$image_width&&$image_height>$frame_height)
  {
    $height=$frame_height;
    $width=$frame_height*($image_width/$image_height);
  }
  else{
    $height=$image_height;
    $width=$image_width;
  }
  $width=(int) $width; $height=(int) $height;
}
// settings
$max_file_size = 9000000; // 200kb
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
// thumbnail sizes
//$sizes = array(100 => 100, 150 => 150,500=>500);
//$target_dir="uploads/";
//$target_filename="";


if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_FILES['image'])) {
  $action=mysqli_real_escape_string($con,$_POST['action']);//determines what kind of image being uploaded profilepicture/cover/post
  if( $_FILES['image']['size'] < $max_file_size ){
    // get file extension
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $valid_exts)) {
      /* resize image 
      foreach ($sizes as $w => $h) {
        $files[] = resize($w, $h);
      }*/
      
      if($action=="profile-picture")//change the image type for the old profile pic in database if a new profile picture is uploaded
      mysqli_query($con,"UPDATE images SET type='profile-images' WHERE uId=$loggedin_id ");

      mysqli_query($con,"INSERT INTO images(uId,format,type) VALUES($loggedin_id,'$ext','$action')");
      $imageId=mysqli_insert_id($con);
      $path="uploads/".$imageId.".".$ext;
      list($image_width, $image_height) = getimagesize($_FILES['image']['tmp_name']);
      $frame_width=400;
      $frame_height=400;
      aspect_ratio($image_width, $image_height,$frame_width,$frame_height);
      $file=resize($path);

      if($action=="profile-picture"){//create another copy of the image with a  thumbnail
      $path="uploads/thumbnails/".$imageId.".".$ext;
      list($image_width, $image_height) = getimagesize($_FILES['image']['tmp_name']);
      $frame_width=100;
      $frame_height=100;
      aspect_ratio($image_width, $image_height,$frame_width,$frame_height);
      $file=resize($path);
      }

     /* list($image_width, $image_height) = getimagesize($_FILES['image']['tmp_name']);
      $frame_width=100;
      $frame_height=100;
      aspect_ratio($image_width, $image_height,$frame_width,$frame_height);
      $file=resize();*/


    } else {
      $msg = 'Unsupported file';
    }
  } else{
    $msg = 'Please upload image smaller than 200KB';
  }
}
?>
