<?php

// Set the width and height of the avatar image
$avatarWidth = 500;
$avatarHeight = 500;

// Get the avatar components from the cookies
$skin = isset($_COOKIE['avatar_skin']) ? '/var/www/html/' . $_COOKIE['avatar_skin'] : '';
$eyes = isset($_COOKIE['avatar_eyes']) ? '/var/www/html/' . $_COOKIE['avatar_eyes'] : '';
$mouth = isset($_COOKIE['avatar_mouth']) ? '/var/www/html/' . $_COOKIE['avatar_mouth'] : '';

// Create a new image
$avatar = imagecreatetruecolor($avatarWidth, $avatarHeight);

// Make the background color transparent
$transparent = imagecolorallocatealpha($avatar, 0, 0, 0, 127);
imagefill($avatar, 0, 0, $transparent);
imagesavealpha($avatar, true);

// Load the skin, eyes, and mouth images
$skinImage = imagecreatefrompng($skin);
$eyesImage = imagecreatefrompng($eyes);
$mouthImage = imagecreatefrompng($mouth);

// Copy the skin image onto the avatar image
imagecopy($avatar, $skinImage, 0, 0, 0, 0, $avatarWidth, $avatarHeight);

// Copy the eyes image onto the avatar image
imagecopy($avatar, $eyesImage, 0, 0, 0, 0, $avatarWidth, $avatarHeight);

// Copy the mouth image onto the avatar image
imagecopy($avatar, $mouthImage, 0, 0, 0, 0, $avatarWidth, $avatarHeight);

// Output the image as a PNG file
header('Content-Type: image/png');
imagepng($avatar);

// Free up memory
imagedestroy($avatar);
imagedestroy($skinImage);
imagedestroy($eyesImage);
imagedestroy($mouthImage);
?>