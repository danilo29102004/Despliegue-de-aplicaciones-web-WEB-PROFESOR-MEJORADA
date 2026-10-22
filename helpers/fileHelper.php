<?php

class fileHelper{

    private const FOLDER = __DIR__ . '/../uploads/';

    public static function uploadFile($origin,$destiny){
        $name = basename($destiny);
        if(!is_dir(self::FOLDER)){
            mkdir(self::FOLDER, 0755, true);
        }

        if(move_uploaded_file($origin, self::FOLDER . $name)){
            return true;
        }
        return false;
    }   

}   
?>