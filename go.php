<?php
/**
 * Created by PhpStorm.
 * User: CooLoongWu
 * Date: 2017-7-27
 * Time: 上午 9:33
 */

use Packing\Packing;

include 'Packing.php';

/**
 *
 * 已知条件：
 * 1、买家除了买鲜花本身，还要为装鲜花所用的箱子付费，一个大箱、中箱或者小箱子的费用分别为Fd,Fz,Fx。
 * 2、对于任何一种花X，都知道对应大中小箱子一满箱X花的支数分别为BXd，BXz，BXx。
 * 3、已知买家购买了H1，H2，...., Hn种花的数量分别为NH1，NH2,...., NHn。
 *
 * 求解：
 * 要用大中小三种箱子各多少个Nd，Nz，Nx，使得买家支付的箱子费用 Nd＊Fd + Nz*Fz + Nx*Fx 最少。
 */

//用户购买花的种类及数量
$buyNum = array(
    'redRose' => 99,
    'whiteRose' => 38,
    'colorRose' => 43,
    'carnation' => 49,
);

echo '从大箱开始装，所需价钱' . Packing::packing($buyNum);





