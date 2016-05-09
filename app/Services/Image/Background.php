<?php
namespace App\Services\Image;

use GeoPattern\GeoPattern;

class Background
{
    public static function fromString($string)
    {
        $geopattern = new GeoPattern();
        $geopattern->setString($string);

        return $geopattern->toDataURI();
    }
}
