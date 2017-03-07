<?php

namespace MoySklad\Components\Fields;

use MoySklad\Exceptions\InvalidUrlException;

class ImageField extends AbstractFieldAccessor{
    /**
     * @param $url
     * @param null $fileName
     * @return ImageField
     * @throws InvalidUrlException
     */
    public static function createFromUrl($url, $fileName = null){
        if ( filter_var($url, FILTER_VALIDATE_URL) === false ){
            throw new InvalidUrlException($url);
        }
        $image = file_get_contents($url);
        return static::createFromExternal($image, $url, $fileName);
    }

    /**
     * @param $path
     * @param null $fileName
     * @return ImageField
     * @throws \Exception
     */
    public static function createFromPath($path, $fileName = null){
        /*
         * Php will throw if no file exist
         */
        $image = file_get_contents($path);
        return static::createFromExternal($image, $path, $fileName);
    }

    /**
     * @param $imageBinary
     * @param $sourcePath
     * @param $fileName
     * @return static
     */
    private static function createFromExternal($imageBinary, $sourcePath, $fileName){
        if ( !$fileName ){
            $splitSrc = explode('/', $sourcePath);
            $fileName = $splitSrc[count($splitSrc) - 1];
        }
        return new static([
            "filename" => $fileName,
            "content" => base64_encode($imageBinary)
        ]);
    }
}