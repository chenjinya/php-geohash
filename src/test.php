<?php
/**
 * Created by PhpStorm.
 * User: jinya
 * Date: 2017/12/8
 * Time: 下午4:52
 */

require __DIR__ . '/GeoHash.php';
function t($str){
    echo "\n\e[32m$str\033[0m\n";
}
$lng = "116.30833710877987";
$lat = "40.0639144661348";
$instance = new GeoHash();

t("sample:");
var_dump([
    "lng" => $lng,
    "lat" => $lat,
]);
t("encode test:");
$geohash = $instance->encode($lng, $lat);
var_export($geohash);
t("bound test:");
var_export($instance->neighbors('zzzzzz'));

t("decode test:");


$precision = 30;
$binLng = $instance->binEncode($lng, $instance::LNG_MIN, $instance::LNG_MAX, $precision);
$binLat = $instance->binEncode($lat, $instance::LAT_MIN, $instance::LAT_MAX, $precision);

//var_dump([$binLng, $binLat]);

$binCombine = $instance->binMerge($binLng, $binLat);

//var_dump([$binCombine]);
$arrDecs = $instance->binToDecs($binCombine);

//var_dump([$arrDecs]);

$geoHash = $instance->decsToBase32($arrDecs);

//var_dump([$geoHash]);


$decs = $instance->base32ToDecs($geoHash);

//var_dump($decs);

$bin = $instance->decsToBin($decs);
//var_dump([$bin]);

$binSeparate = $instance->binSeparate($bin);
//var_dump([$binSeparate]);

var_dump([
    $instance->binDecode($binSeparate[0], $instance::LNG_MIN, $instance::LNG_MAX),
    $instance->binDecode($binSeparate[1], $instance::LAT_MIN, $instance::LAT_MAX)
]);