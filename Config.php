<?php
/**
 * Created by PhpStorm.
 * User: CooLoongWu
 * Date: 2017-7-27
 * Time: 下午 2:05
 */

namespace BoxUp;


class Config
{
    //每种箱子的价格
    public static $boxFee = array(
        'max' => 10.20,
        'mid' => 8.30,
        'min' => 5.34,
    );

    //每种箱子可装花的数量
    public static $flowerInBox = array(
        'redRose' => array(
            'max' => 65,
            'mid' => 50,
            'min' => 25,
        ),
        'whiteRose' => array(
            'max' => 65,
            'mid' => 50,
            'min' => 25,
        ),
        'colorRose' => array(
            'max' => 50,
            'mid' => 30,
            'min' => 20,
        ),
        'carnation' => array(
            'max' => 80,
            'mid' => 65,
            'min' => 40,
        ),
    );

    //每种花在箱子中所占体积
    public static function getVolumeInBox($flowerName, $boxType)
    {
        //return fmod(floatval(1), self::$flowerInBox[$flowerName][$boxType]);
        return round(1 / self::$flowerInBox[$flowerName][$boxType], 2);
    }


}