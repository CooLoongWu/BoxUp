<?php

/**
 * Created by PhpStorm.
 * User: CooLoongWu
 * Date: 2017-7-27
 * Time: 下午 2:00
 */

namespace BoxUp;
header("Content-Type: text/html;charset=utf-8");

include 'Config.php';

/**
 * 先往大箱装，然后装中箱或者小箱
 */
class FirstMaxBox
{

    private static $maxBoxNum = array();
    private static $midBoxNum = array();
    private static $minBoxNum = array();

    private static $fee = 0;

    private static $remainNum = array();

    public static function boxUp($buyNum)
    {
        foreach ($buyNum as $flowerName => $num) {
            self::printBuyMsg($flowerName, $num);
            self::boxUpByBox($flowerName, $num, 'max');
        }

        self::boxUpRemains($buyNum);
        return self::$fee;
    }

    private static function boxUpByBox($flowerName, $num, $type = 'max')
    {
        switch ($type) {
            //往大箱装
            case 'max':
                self::$maxBoxNum[$flowerName] = intval($num / Config::$flowerInBox[$flowerName][$type]);
                self::boxUpRemainFlower($flowerName, $num % Config::$flowerInBox[$flowerName][$type], 'mid');
                break;
            //往中箱装
            case 'mid':
                self::$midBoxNum[$flowerName] = intval($num / Config::$flowerInBox[$flowerName][$type]);
                self::boxUpRemainFlower($flowerName, $num % Config::$flowerInBox[$flowerName][$type], 'min');
                break;
            //往小箱装【余下的需要和其他的花放在一起打包】
            case 'min':
                self::$minBoxNum[$flowerName] = intval($num / Config::$flowerInBox[$flowerName][$type]);
                self::boxUpRemainFlower($flowerName, $num % Config::$flowerInBox[$flowerName][$type], 'remain');
                break;
            default:
                break;
        }
    }

    private static function boxUpRemainFlower($flowerName, $remain, $type)
    {
        $temp = ($type == "mid" ? "大" : ($type == "min" ? "中" : "小"));
        if (0 != $remain) {
            echo $temp . '箱装完剩余' . $flowerName . '花' . $remain . '朵<br>';
            if ('remain' == $type) {
                echo '=========================<br>';
                self::$remainNum[$flowerName] = $remain;
            }
            self::boxUpByBox($flowerName, $remain, $type);
        } else {
            echo $temp . '箱刚好装完所有' . $flowerName . '<br>';
            echo '=========================<br>';
        }
    }

    private static function boxUpRemains($buyNum)
    {
        $volumeInBox = array(
            'max' => 0,
            'mid' => 0,
            'min' => 0,
        );

        foreach (self::$remainNum as $flowerName => $num) {
            echo '剩余' . $flowerName . '花' . $num . '朵' . '；占大箱体积：'
                . (Config::getVolumeInBox($flowerName, 'max') * $num)
                . '；占中箱体积：'
                . (Config::getVolumeInBox($flowerName, 'mid') * $num)
                . '；占小箱体积：'
                . (Config::getVolumeInBox($flowerName, 'min') * $num)
                . '<br>';

            $volumeInBox['max'] += (Config::getVolumeInBox($flowerName, 'max') * $num);
            $volumeInBox['mid'] += (Config::getVolumeInBox($flowerName, 'mid') * $num);
            $volumeInBox['min'] += (Config::getVolumeInBox($flowerName, 'min') * $num);
        }

        echo '=========================<br>';
        self::printBoxUpMsg($buyNum);

        if ($volumeInBox['min'] <= 1) {
            echo '可用一个小箱装下所有剩余的花，价钱：' . Config::$boxFee['min'] . '<br>';
            self::$fee += Config::$boxFee['min'];
        } else if ($volumeInBox['mid'] <= 1) {
            echo '可用一个中箱装下所有剩余的花，价钱：' . Config::$boxFee['mid'] . '<br>';
            self::$fee += Config::$boxFee['mid'];
        } else if ($volumeInBox['max'] <= 1) {
            echo '可用一个大箱装下所有剩余的花，价钱：' . Config::$boxFee['max'] . '<br>';
            self::$fee += Config::$boxFee['max'];
        } else {
            echo '没办法只用一个箱子装下所有剩余的花朵，仍需计算。。。。。' . '<br>';
        }

        echo '=========================<br>';
    }

    private static function printBuyMsg($flowerName, $num)
    {
        echo '买家购买' . $flowerName . '花' . $num . "朵<br>";
        echo $flowerName . '花' . '在大箱中可装' . Config::$flowerInBox[$flowerName]['max'] . '朵<br>';
        echo $flowerName . '花' . '在中箱中可装' . Config::$flowerInBox[$flowerName]['mid'] . '朵<br>';
        echo $flowerName . '花' . '在小箱中可装' . Config::$flowerInBox[$flowerName]['min'] . '朵<br>';
    }

    private static function printBoxUpMsg($buyNum)
    {
        foreach ($buyNum as $flowerName => $num) {
            $maxBoxNum = isset(self::$maxBoxNum[$flowerName]) ? self::$maxBoxNum[$flowerName] : 0;
            $midBoxNum = isset(self::$midBoxNum[$flowerName]) ? self::$midBoxNum[$flowerName] : 0;
            $minBoxNum = isset(self::$minBoxNum[$flowerName]) ? self::$minBoxNum[$flowerName] : 0;

            $price = $maxBoxNum * Config::$boxFee['max'] + $midBoxNum * Config::$boxFee['mid'] + $minBoxNum * Config::$boxFee['min'];

            echo "装" . $flowerName . "需：大箱" .
                $maxBoxNum
                . "；中箱：" .
                $midBoxNum
                . "；小箱：" .
                $minBoxNum
                . '；价钱：' .
                $price
                . '<br>';

            self::$fee += $price;
        }
    }

}