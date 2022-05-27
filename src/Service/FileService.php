<?php

namespace App\Service;

class FileService {
    public static function remove($filename, $dir){
        try{
            unlink($dir . '/' . $filename);
        }catch (\Exception $e){
            return  "Error in removing the image ";
        }
    }
}