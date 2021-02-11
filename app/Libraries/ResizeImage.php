<?php
namespace App\Libraries;

use Intervention\Image\ImageManagerStatic as Images;

class ResizeImage 
{
    public static function dimenssion($file, $ext, $route, $width = 0, $height = 0, $both = false)
    {
        // REDIMENSION DE LAS IMAGENES
        $dimensiones = getimagesize(public_path($route.$file));
        $width_image = $dimensiones[0];
        $height_image = $dimensiones[1];

        // Images::configure(array('driver' => 'imagick'));
     
        $resizer = Images::make(public_path($route.$file));
        
        $porcentaje = 20;
        $width_default = $dimensiones[0];

        if ($ext == 'png'){

            if($width_default > 3100){
                $porcentaje = 60;
            }else if($width_default > 2000 && $width_default < 3100){
                $porcentaje = 50;
            }else if($width_default > 1300 && $width_default < 2000){
                $porcentaje = 30;
            } else if ($width_default < 1024) {
                $porcentaje = 5;
            }

            $porcentaje = $porcentaje / 100;

            if($width > 0 && $width < $width_image && !$both){
                $percent = ($width * 100) / $width_image;
                $height = $height_image * ($percent / 100);
            }else if($width == 0 && $width < $width_image && !$both){
                $width = $dimensiones[0] - (($dimensiones[0] * $porcentaje) / 100);
                $height = $dimensiones[1] - (($dimensiones[1] * $porcentaje) / 100);
            }

            if($width == 0 && $height == 0 && !$both){
                $width = $dimensiones[0] - (($dimensiones[0] * $porcentaje) / 100);
                $height = $dimensiones[1] - (($dimensiones[1] * $porcentaje) / 100);
            }

            $resizer->resize($width, $height)->save(public_path($route.$file), $porcentaje);

        }elseif ($ext == 'jpg'){

            if($width_default > 3100){
                $porcentaje = 50;
            }else if($width_default > 2000 && $width_default < 3100){
                $porcentaje = 60;
            }else if($width_default > 1300 && $width_default < 2000){
                $porcentaje = 70;
            }

            if($width > 0 && $width < $width_image && !$both){
                $percent = ($width * 100) / $width_image;
                $height = $height_image * ($percent / 100);
            } else if($width == 0 && $width < $width_image && !$both){
                $width = $dimensiones[0] - (($dimensiones[0] * $porcentaje) / 100);
                $height = $dimensiones[1] - (($dimensiones[1] * $porcentaje) / 100);
            }

            if($width == 0 && $height == 0 && !$both){
                $width = $dimensiones[0] - (($dimensiones[0] * $porcentaje) / 100);
                $height = $dimensiones[1] - (($dimensiones[1] * $porcentaje) / 100);
            }

            // if($height > 0){
            //     $percent = ($height * 100) / $height_image;
            //     $width = $width * ($percent / 100);
            // }

            $resizer->resize($width, $height)->save(public_path($route.$file), $porcentaje);

        }
    }
}
