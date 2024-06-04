<?php

namespace ImageCompressor;

class ImageCompressor
{
    /**
     * Compress and resize an image
     *
     * @param string $source The path to the source image.
     * @param string $destination The path to save the resized image.
     * @param int $newWidth The new width for the resized image.
     * @param int $newHeight The new height for the resized image.
     * @param int $quality The quality of the resized image (0-100).
     * @return bool
     */
    public function compress($source, $destination, $newWidth, $newHeight, $quality)
    {
        // Get image info
        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
            // Convert PNG to JPEG
            imagejpeg($image, $destination, $quality);
            imagedestroy($image);
            return true;
        } else {
            return false;
        }

        // Get original dimensions
        list($width, $height) = getimagesize($source);

        // Calculate aspect ratio
        $aspectRatio = $width / $height;

        // Adjust the new dimensions to maintain the aspect ratio
        if ($newWidth / $newHeight > $aspectRatio) {
            $newWidth = $newHeight * $aspectRatio;
        } else {
            $newHeight = $newWidth / $aspectRatio;
        }

        // Resize the image
        $resizedImageResource = imagescale($image, $newWidth, $newHeight, IMG_BICUBIC);

        // Save the resized image
        imagejpeg($resizedImageResource, $destination, $quality);

        // Free memory associated with the image resource
        imagedestroy($resizedImageResource);
        imagedestroy($image);

        return true;
    }
}