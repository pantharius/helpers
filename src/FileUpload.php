<?php

/**
 * Fichier de la classe FileUpload
 */

namespace JDOUnivers\Helpers;

class FileUpload {

    const OK = 0;
    const TOO_BIG_FILE = 1;
    const INVALID_EXTENSION = 2;
    const TRANSFER_ERROR = 3;
    const MOVE_ERROR = 4;

    public static function moveFileUpload($fileNamePost, $fileName, $directoryDestination, $sizeMax, $widthMax, $heightMax, $uploadType = null) {

        $tmpName = $_FILES[$fileNamePost]['tmp_name'];
        if (!file_exists($tmpName))
            return self::TRANSFER_ERROR;
        if ($_FILES[$fileNamePost]['error'] != 0)
            return self::TRANSFER_ERROR . "-" . $_FILES[$fileNamePost]['error'];

        if ($_FILES[$fileNamePost]['size'] > $sizeMax)
            return self::TOO_BIG_FILE . "-" . $sizeMax;

        $extension = strtolower(pathinfo($_FILES[$fileNamePost]['name'], PATHINFO_EXTENSION));
        $chemin = $directoryDestination . $fileName . "." . $extension;

        if ($uploadType == 'img') {

            $source_properties = getimagesize($tmpName);
            $image_type = $source_properties[2];
            $isSuccess = false;

            if ($image_type == IMAGETYPE_JPEG) {
                $image_resource_id = imagecreatefromjpeg($tmpName);
                $target_layer = self::fn_resize($image_resource_id, $source_properties[0], $source_properties[1], $widthMax, $heightMax);
                $isSuccess = imagejpeg($target_layer, $chemin);
            } else if ($image_type == IMAGETYPE_GIF) {
                $image_resource_id = imagecreatefromgif($tmpName);
                $target_layer = self::fn_resize($image_resource_id, $source_properties[0], $source_properties[1], $widthMax, $heightMax);
                $isSuccess = imagegif($target_layer, $chemin);
            } else if ($image_type == IMAGETYPE_PNG) {
                $image_resource_id = imagecreatefrompng($tmpName);
                $target_layer = self::fn_resize($image_resource_id, $source_properties[0], $source_properties[1], $widthMax, $heightMax);
                $isSuccess = imagepng($target_layer, $chemin);
            } else
                return self::INVALID_EXTENSION;

            if ($isSuccess)
                return self::OK;
            else
                return self::MOVE_ERROR;
        } else if ($uploadType == 'audio')
            $extensions_valides = array('mp3', 'wav');
        else if ($uploadType == 'video')
            $extensions_valides = array('avi', 'mp4');
        else
            $extensions_valides = null;

        if ($extensions_valides != null && !in_array($extension, $extensions_valides))
            return self::INVALID_EXTENSION;


        if (move_uploaded_file($tmpName, $chemin))
            return self::OK;
        else
            return self::MOVE_ERROR;
    }

    private static function fn_resize($image_resource_id, $img_width, $img_height, $target_width, $target_height) {

        $target_ratio = $target_width / $target_height;
        $src_ratio = $img_width / $img_height;
        
        $resized_width = ($target_ratio < $src_ratio) ? ($img_width / ($img_height / $target_height)) : $target_width;
        $resized_height = ($target_ratio > $src_ratio) ? ($img_height / ($img_width / $target_width)) : $target_height;

        $resized_img = imagecreatetruecolor($resized_width, $resized_height);
        imagecopyresampled($resized_img, $image_resource_id, 0, 0, 0, 0, $resized_width, $resized_height, $img_width, $img_height);

        $final_img = imagecreatetruecolor($target_width, $target_height);
        imagecopyresampled($final_img, $resized_img, 0, 0, ($resized_width - $target_width) / 2, ($resized_height - $target_height) / 2, $target_width, $target_height, $target_width, $target_height);

        return $final_img;
    }
}