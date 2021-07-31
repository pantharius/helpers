<?php

namespace JDOUnivers\Helpers;

class Format{
    
    public static function sizeStringToBytes($size){
        $size = trim($size);
        $last = strtolower($size[strlen($size)-1]);
        $size = substr($size, 0, -1);
        switch($last) {
            // Le modifieur 'G' est disponible depuis PHP 5.1.0
            case 'g':
            $size *= 1024;
            case 'm':
            $size *= 1024;
            case 'k':
            $size *= 1024;
        }
        
        return $size;
    }
}