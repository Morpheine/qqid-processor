<?php
/**
 * Created by PhpStorm.
 * User: brent
 * Date: 2015-04-23
 * Time: 2:57 PM
 */
class ImageGenerator
{
    /**
     * @param $year
     * @param $semester
     * @return bool
     */
    public function buildImg($year, $semester)
    {
        // Generate an image with a size of 15px x 15px
        $my_img = imagecreate(15, 15);

        // Determine the semester, and set the background/text colours to match
        if ($semester == 'Winter') {
            $background = imagecolorallocate($my_img, 149, 201, 239);
            $text_colour = imagecolorallocate($my_img, 0, 0, 0);
        } elseif ($semester == 'Fall') {
            $background = imagecolorallocate($my_img, 183, 25, 0);
            $text_colour = imagecolorallocate($my_img, 255, 255, 255);
        } elseif ($semester == 'Spring/Summer') {
            $background = imagecolorallocate($my_img, 180, 221, 58);
            $text_colour = imagecolorallocate($my_img, 0, 0, 0);
        } elseif ($semester == 'none') {
            $year = 'NA';
            $background = imagecolorallocate($my_img, 221, 221, 221);
            $text_colour = imagecolorallocate($my_img, 209, 16, 16);
        }
        // Set the image text to the year, with the correct text colour
        imagestring($my_img, 2, 2, 1, $year, $text_colour);

        // Draw the image to the screen
        imagepng($my_img);

        // Clean up
        imagecolordeallocate($text_color);
        imagecolordeallocate($background);
        imagedestroy($my_img);
    }
}
