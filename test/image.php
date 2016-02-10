<?php
/**
 * Image resize
 * @param int $width
 * @param int $height
 */
function resize($width, $height){
  /* Get original image x y*/
  list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
  /* calculate new image size with ratio */
  $ratio = max($width/$w, $height/$h);
  $h = ceil($height / $ratio);
  $x = ($w - $width / $ratio) / 2;
  $w = ceil($width / $ratio);
  /* new file name */
  $path = 'uploads/'.$width.'x'.$height.'_'.$_FILES['image']['name'];
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
      imagejpeg($tmp, $path, 100);
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


// settings
$max_file_size = 9000000; // 200kb
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
// thumbnail sizes
$sizes = array(100 => 100, 150 => 150);
$target_dir="uploads/";
$target_filename="";


if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_FILES['image'])) {
  if( $_FILES['image']['size'] < $max_file_size ){
    // get file extension
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $valid_exts)) {
      /* resize image */
      foreach ($sizes as $w => $h) {
        $files[] = resize($w, $h);
      }

    } else {
      $msg = 'Unsupported file';
    }
  } else{
    $msg = 'Please upload image smaller than 200KB';
  }
}
?>
<html>
<head>
  <title>Image resize while uploadin</title>
<head>
<body>
  <!-- file uploading form -->
  <form action="" method="post" enctype="multipart/form-data">
    <label>
      <span>Choose image</span>
      <input type="file" name="image" accept="image/*" />
    </label>
    <input type="submit" value="Upload" />
  </form>
</body>
</html>