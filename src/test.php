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

t("neighbors test:");

$neighbors = $instance->neighbors($geohash);
var_export($neighbors);

t("decode test:");

var_export($instance->decode($geohash));

t("bound test:");

var_export($instance->neighbors('zzzzzz'));
