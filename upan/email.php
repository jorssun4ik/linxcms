<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */
if (isset($_GET['email'])){
    $email = $_GET['email'];
$size = 2; 
$im = imagecreate(imagefontwidth($size)*strlen($email), imagefontheight($size)); 
$bg = imagecolorallocate($im, 255, 255, 255); 
$black = imagecolorallocate($im, 0, 0, 0); 
imagecolortransparent($im,$bg); 
imagestring($im, $size, 0, 0, $email, $black); 
header('Content-type: image/png'); 
imagepng($im); 
}
?>