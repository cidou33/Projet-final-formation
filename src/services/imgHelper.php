<?php

namespace App\services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class imgHelper extends AbstractController
{
    public function img($imgBrut)
    {
        //dd($imgBrut);
        $imagename = $imgBrut->getClientOriginalName();
        $imagepath = $imagename;
        //Ceci est le nouveau fichier que vous enregistrez
        $save = "images/" . $imagepath;
        $info = $imgBrut->getSize();
        $mime = $imgBrut->getClientMimeType();
        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                break;

            case 'image/svg':
                $image_create_func = 'imagecreatefromsvg';
                $image_save_func = 'imagesvg';
                break;

            default:
                throw new Exception('Unknown image type.');
        }
        $extension = str_replace("image/", "", $mime);
        list($width, $height) = getimagesize($imgBrut);
        $modwidth = 300;  //target width
        $diff = $width / $modwidth;
        $modheight = $height / $diff;
        $tn = imagecreatetruecolor($modwidth, $modheight);
        $img = $image_create_func($imgBrut);
        imagecopyresampled($tn, $img, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
        $image_save_func($tn, '');

        //traitement de l'img
        $imgBrut = $img;

        return $img;
    }


}