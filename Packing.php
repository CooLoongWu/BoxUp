<?php

/**
 * Created by PhpStorm.
 * User: CooLoongWu
 * Date: 2017-7-27
 * Time: 下午 2:00
 */

namespace Packing;
header("Content-Type: text/html;charset=utf-8");

/**
 * 先往大箱装，然后装中箱或者小箱
 */
class Packing
{

    //运费(元/Kg)
    private static $freight = [
        'airplane' => 6,
        'car' => 4,
        'train' => 3,
    ];

    private static $freightType = 'car';

    //每种箱子最大可运重量(Kg)
    private static $boxBearing = array(
        'max' => 80,
        'mid' => 60,
        'min' => 40,
    );

    //每种箱子的打包费用(元)
    private static $boxFee = array(
        'max' => 70,
        'mid' => 60,
        'min' => 50,
    );

    //每种箱子可装花的数量
    private static $flowerInBox = array(
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

    //==================================================上面为参数，需要传过来

    private static $maxBoxNum = array();
    private static $midBoxNum = array();
    private static $minBoxNum = array();

    private static $fee = 0;

    private static $remainNum = array();

    public static function packing($buyNum)
    {
        foreach ($buyNum as $flowerName => $num) {
            self::printBuyMsg($flowerName, $num);

            self::packingByBox($flowerName, $num);
        }

        self::packingRemainder($buyNum);
        return self::$fee;
    }

    /**
     * 装箱（按照箱子类型开始装，默认从大箱开始）
     * @param $flowerName
     * @param $num
     * @param string $type
     *
     * 花的ID
     * 购买的花的数量
     * 箱子的类型
     */
    private static function packingByBox($flowerName, $num, $type = 'max')
    {
        switch ($type) {
            //往大箱装
            case 'max':
                self::$maxBoxNum[$flowerName] = intval($num / self::$flowerInBox[$flowerName][$type]);
                self::packingNext($flowerName, $num % self::$flowerInBox[$flowerName][$type], 'mid');
                break;
            //往中箱装
            case 'mid':
                self::$midBoxNum[$flowerName] = intval($num / self::$flowerInBox[$flowerName][$type]);
                self::packingNext($flowerName, $num % self::$flowerInBox[$flowerName][$type], 'min');
                break;
            //往小箱装
            case 'min':
                self::$minBoxNum[$flowerName] = intval($num / self::$flowerInBox[$flowerName][$type]);
                self::packingNext($flowerName, $num % self::$flowerInBox[$flowerName][$type], 'remain');
                break;
            default:
                break;
        }
    }

    /**
     * 下一步打包
     * @param $flowerName
     * @param $remain
     * @param $type
     *
     * 花的ID
     * 剩下的数量
     * 箱子类型，如果装完小箱还有剩余那么可以将余下的花朵和其他的一起打包
     */
    private static function packingNext($flowerName, $remain, $type)
    {
        $temp = ($type == 'mid' ? "大" : ($type == 'min' ? "中" : "小"));
        if (0 != $remain) {
            echo $temp . '箱装完剩余' . $flowerName . '花' . $remain . '朵<br>';
            if ('remain' == $type) {
                echo '=========================<br>';
                self::$remainNum[$flowerName] = $remain;
            }
            self::packingByBox($flowerName, $remain, $type);
        } else {
            echo $temp . '箱刚好装完所有' . $flowerName . '<br>';
            echo '=========================<br>';
        }
    }

    /**
     * 打包剩余的花朵
     * @param $buyNum
     */
    private static function packingRemainder($buyNum)
    {
        $volumeInBox = array(
            'max' => 0,
            'mid' => 0,
            'min' => 0,
        );

        foreach (self::$remainNum as $flowerName => $num) {
            echo '剩余' . $flowerName . '花' . $num . '朵' . '；占大箱体积：'
                . (self::getVolumeInBox($flowerName, 'max') * $num)
                . '；占中箱体积：'
                . (self::getVolumeInBox($flowerName, 'mid') * $num)
                . '；占小箱体积：'
                . (self::getVolumeInBox($flowerName, 'min') * $num)
                . '<br>';

            $volumeInBox['max'] += (self::getVolumeInBox($flowerName, 'max') * $num);
            $volumeInBox['mid'] += (self::getVolumeInBox($flowerName, 'mid') * $num);
            $volumeInBox['min'] += (self::getVolumeInBox($flowerName, 'min') * $num);
        }

        echo '=========================<br>';
        self::printPackingMsg($buyNum);

        if ($volumeInBox['min'] <= 1) {
            echo '可用一个小箱装下所有剩余的花，打包费用：' . self::$boxFee['min'] . '<br>';
            self::$fee += self::$boxFee['min'];
            self::$fee += self::getFreightByBox('min', self::$freightType);
        } else if ($volumeInBox['mid'] <= 1) {
            echo '可用一个中箱装下所有剩余的花，打包费用：' . self::$boxFee['mid'] . '<br>';
            self::$fee += self::$boxFee['mid'];
            self::$fee += self::getFreightByBox('mid', self::$freightType);
        } else if ($volumeInBox['max'] <= 1) {
            echo '可用一个大箱装下所有剩余的花，打包费用：' . self::$boxFee['max'] . '<br>';
            self::$fee += self::$boxFee['max'];
            self::$fee += self::getFreightByBox('max', self::$freightType);
        } else {
            echo '没办法只用一个箱子装下所有剩余的花朵，仍需计算。。。。。' . '<br>';
        }

        echo '=========================<br>';
    }

    /**
     * 打印购买信息
     * @param $flowerName
     * @param $num
     *
     * 花的ID
     * 数量
     */
    private static function printBuyMsg($flowerName, $num)
    {
        echo '买家购买' . $flowerName . '花' . $num . "朵<br>";
        echo $flowerName . '花' . '在大箱中可装' . self::$flowerInBox[$flowerName]['max'] . '朵<br>';
        echo $flowerName . '花' . '在中箱中可装' . self::$flowerInBox[$flowerName]['mid'] . '朵<br>';
        echo $flowerName . '花' . '在小箱中可装' . self::$flowerInBox[$flowerName]['min'] . '朵<br>';
    }

    /**
     * 打印打包信息
     * @param $buyNum
     * 购买信息
     */
    private static function printPackingMsg($buyNum)
    {
        $allMaxBoxNum = 0;
        $allMidBoxNum = 0;
        $allMinBoxNum = 0;

        foreach ($buyNum as $flowerName => $num) {
            $maxBoxNum = isset(self::$maxBoxNum[$flowerName]) ? self::$maxBoxNum[$flowerName] : 0;
            $midBoxNum = isset(self::$midBoxNum[$flowerName]) ? self::$midBoxNum[$flowerName] : 0;
            $minBoxNum = isset(self::$minBoxNum[$flowerName]) ? self::$minBoxNum[$flowerName] : 0;

            //打包装箱费用
            $price1 = $maxBoxNum * self::$boxFee['max'] + $midBoxNum * self::$boxFee['mid'] + $minBoxNum * self::$boxFee['min'];

            //运输费用
            $price2 = 0;
            $allMaxBoxNum += $maxBoxNum;
            $allMidBoxNum += $midBoxNum;
            $allMinBoxNum += $minBoxNum;

            echo "装" . $flowerName . "需：大箱" .
                $maxBoxNum
                . "；中箱：" .
                $midBoxNum
                . "；小箱：" .
                $minBoxNum
                . '；打包费用：' .
                $price1
                . '<br>';

            self::$fee += ($price1 + $price2);
        }

        echo "共需：大箱" .
            $allMaxBoxNum . "，运费：" . ($allMaxBoxNum * self::getFreightByBox("max", "car"))
            . "；中箱：" .
            $allMidBoxNum . "，运费：" . ($allMidBoxNum * self::getFreightByBox("mid", "car"))
            . "；小箱：" .
            $allMinBoxNum . "，运费：" . ($allMinBoxNum * self::getFreightByBox("min", "car"))
            . '<br>';

        self::$fee += ($allMaxBoxNum * self::getFreightByBox("max", "car")
            + $allMidBoxNum * self::getFreightByBox("mid", "car")
            + $allMinBoxNum * self::getFreightByBox("min", "car")
        );
    }

    /**
     * 每种花在箱子中所占体积（精确到小数点后两位）
     * @param $flowerName
     * @param $boxType
     * @return float
     *
     * 花的ID
     * 箱子类型
     * 返回该花占箱子的体积
     */
    private static function getVolumeInBox($flowerName, $boxType)
    {
        return round(1 / self::$flowerInBox[$flowerName][$boxType], 4);
    }

    private static function getFreightByBox($boxType, $freightType)
    {
        return round(self::$boxBearing[$boxType] / self::$freight[$freightType], 4);
    }
}