<?php
session_start();

$captcha_code = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 4);

$_SESSION['captcha'] = $captcha_code;

$image = imagecreate(100, 40);  // Image dimensions
$background_color = imagecolorallocate($image, 255, 255, 255);  
$text_color = imagecolorallocate($image, 0, 0, 0);  
imagestring($image, 5, 10, 10, $captcha_code, $text_color);
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
