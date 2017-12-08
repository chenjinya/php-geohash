<?php
/**
 * Created by PhpStorm.
 * User: jinya
 * Date: 2017/12/7
 * Time: 下午2:36
 */

class GeoHash {

    const LNG_MAX = 180;
    const LNG_MIN = -180;
    const LAT_MAX = 90;
    const LAT_MIN = -90;
    const BASE32_CHARACTER = '0123456789bcdefghjkmnpqrstuvwxyz';
    const BIT_LEN = 5;

    public function encode($lng, $lat, $precision = 30){

        $binLng = $this->binEncode($lng, self::LNG_MIN, self::LNG_MAX, $precision);
        $binLat = $this->binEncode($lat, self::LAT_MIN, self::LAT_MAX, $precision);

        $binCombine = $this->binMerge($binLng, $binLat);
        $arrDecs = $this->binToDecs($binCombine);
        return $this->decsToBase32($arrDecs);
    }

    public function decode($base32) {

        $decs = $this->base32ToDecs($base32);
        $bin  = $this->decsToBin($decs);
        list($binLng, $binLat) = $this->binSeparate($bin);
        return [$this->binDecode($binLng, self::LNG_MIN, self::LNG_MAX), $this->binDecode($binLat, self::LAT_MIN, self::LAT_MAX)];
    }

    public function binOperation($bin, $op){
        $len = strlen($bin);
        $dec = bindec($bin);
        $dec = $dec + $op;

        if($op > 0) {
            if($dec > pow(2, $len) - 1)  return false;
        } else {
            if($dec < 0) return false;
        }
        $bin = decbin($dec);
        return str_pad($bin, $len, '0', STR_PAD_LEFT);
    }


    public function neighbors($base32, $neighborsDirection = [
        'top',
        'top-left',
        'top-right',
        'left',
        'self',
        'right',
        'bottom',
        'bottom-left',
        'bottom-right',
    ]){

        $ret = [];
        foreach($neighborsDirection as $direction) {
            $ret[$direction] = $this->calNeighbor($base32, $direction);
        }
        return $ret;

    }

    public function calNeighbor ($base32, $direction){

        $bin = $this->decsToBin(
            $this->base32ToDecs($base32)
        );

        list($binLng, $binLat) = $this->binSeparate($bin);

        if(strstr($direction, 'left')) {
            $binLng = $this->binOperation($binLng, -1);
        } elseif (strstr($direction, 'right')) {
            $binLng = $this->binOperation($binLng, 1);
        }

        if(strstr($direction, 'top')) {
            $binLat = $this->binOperation($binLat, 1);
        } elseif (strstr($direction, 'bottom')) {
            $binLat = $this->binOperation($binLat, -1);
        }

        if(false == $binLng || false == $binLat) {
            return false;
        }
        $bin = $this->binMerge($binLng, $binLat);
        $decs = $this->binToDecs($bin);
        $neighborHash = ($this->decsToBase32($decs));
        return $neighborHash ;
    }



    public function decsToBase32($arrDecs){
        $count = count($arrDecs);
        $hash = '';
        for($i = 0; $i < $count; $i ++) {
            $num = $arrDecs[$i];
            $hash  .= substr(self::BASE32_CHARACTER, $num, 1);
        }
        return $hash;
    }

    public function base32ToDecs($base32){
        $count = strlen($base32);
        $decs = [];
        for($i = 0; $i < $count; $i ++) {
            $character = $base32[$i];
            $decs[] = strpos(self::BASE32_CHARACTER, $character);
        }
        return $decs;
    }

    public function binToDecs($bin){
        $bitCount = strlen($bin);
        $numCount = ceil($bitCount / self::BIT_LEN);
        $bin = str_pad($bin, self::BIT_LEN * $numCount, '0', STR_PAD_RIGHT);

        $decs = [];
        for($i = 0; $i < $numCount; $i++) {
            $shortBin = substr($bin, 0, self::BIT_LEN);
            $bin = substr($bin, self::BIT_LEN);
            $decs[] = bindec($shortBin);

        }
        return $decs;
    }

    public function decsToBin($decs){
        $decsCount = count($decs);
        $bin = '';
        for($i = 0; $i < $decsCount; $i++) {
            $shortBin = decbin($decs[$i]);
            $shortBin = str_pad($shortBin, self::BIT_LEN , '0', STR_PAD_LEFT);
            $bin .= $shortBin;
        }
        return $bin;
    }


    public function binMerge($binLng, $binLat){


//        echo "\$binLng $binLng\n\$binLat $binLat \n";
        $lngBitCount = strlen($binLng);
        $latBitCount = strlen($binLat);
        $len = $lngBitCount + $latBitCount;
        $combineBits = "";
        $switch = true;
        for($i = 0 ;$i < $len; $i ++) {
            if($switch) {
                $combineBits .= substr($binLng, 0, 1);
                $binLng = substr($binLng, 1);
            } else {
                $combineBits .= substr($binLat,0, 1);
                $binLat = substr($binLat, 1);
            }
            $switch = !$switch;
        }

        return $combineBits;
    }

    public function binSeparate($bin){

        $len = strlen($bin);

        $binLng = "";
        $binLat = "";
        $switch = true;
        for($i = 0 ;$i < $len; $i ++) {
            if($switch) $binLng .= $bin[$i];
            else $binLat .= $bin[$i];
            $switch = !$switch;

        }
        return [
            $binLng, $binLat
        ];
    }


    public function binEncode($number, $min, $max, $bitCount = 30){

        if($bitCount === 0) {
            return "";
        }
        $mid = ( $min + $max ) / 2;
        if($number >= $mid) {
            return "1" . $this->binEncode($number, $mid, $max, $bitCount - 1);
        } else {
            return "0" . $this->binEncode($number, $min, $mid, $bitCount - 1);
        }
    }

    public function binDecode($bin, $min, $max){

        $firstBin = substr($bin, 0, 1);
        $bin = substr($bin, 1);
        $mid = ($min + $max) / 2;
        if (strlen($bin) === 0) {
            return $mid;
        }

        if($firstBin === "1") {
            return $this->binDecode($bin, $mid, $max);
        } else {
            return $this->binDecode($bin, $min, $mid);
        }
    }
}
