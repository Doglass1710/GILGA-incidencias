<?php

namespace App;

use Intervention\Image\ImageManager;
use Image;

class funciones
{
    public function comprime_img($nombrefoto,$image_path){
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            $ruta= storage_path('app/img_sisa/'.$image_path_name);
            Image::make($image_path->getRealPath())
               ->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
               })->save($ruta,60);
            return $image_path_name;             
        }else{
            return false;
        }
    }
}
