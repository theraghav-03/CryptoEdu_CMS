<?php
session_start();

// Generate a random CAPTCHA code (4 characters)
$captcha_code = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 4);

$_SESSION['captcha'] = $captcha_code;

$image = imagecreate(100, 40); 
$background_color = imagecolorallocate($image, 255, 255, 255);  
$text_color = imagecolorallocate($image, 0, 0, 0);  

// Draw the CAPTCHA text on the image
imagettftext($image, 20, 0, 10, 30, $text_color, $font, $captcha_code);

header("Content-type: image/png");

imagepng($image);

imagedestroy($image);
?>
