# 装箱算法

### 已知
* 1、买家除了买鲜花本身，还要为装鲜花所用的箱子付费，一个大箱、中箱或者小箱子的费用分别为Fd,Fz,Fx。
* 2、对于任何一种花X，都知道对应大中小箱子一满箱X花的支数分别为BXd，BXz，BXx。
* 3、已知买家购买了H1，H2，...., Hn种花的数量分别为NH1，NH2,...., NHn。

### 求解  
要用大中小三种箱子各多少个Nd，Nz，Nx，使得买家支付的箱子费用 Nd＊Fd + Nz*Fz + Nx*Fx 最少。

## 最直接的算法【并非最优】

### 例如
题目
 * 大箱：10元；中箱：8元；小箱：5元
 * 玫瑰花：大箱：10支；中箱8支；小箱：5支 // 月季：大箱：15支；中箱10支；小箱：5支
 * 买家购买：玫瑰花：59支；月季：58支
 
总体思路先分别把每种花往大箱装，然后往中箱或者小箱装。最后剩余的再一起打包装箱。
 * 先算出每种花占大中小箱的体积：玫瑰花体积：玫瑰花体积：大箱：1/10；中箱1/8；小箱：1/5 // 月季体积：大箱：1/15；中箱1/10；小箱：1/5
 * 接下来先装玫瑰花：大箱：59/10 // 中箱：9/8 // 小箱：1/5
 * 再装月季：大箱：58/15 // 中箱：13/10 // 小箱：3/5
 * 剩下1支玫瑰，3支月季，然后根据体积再判断能否直接用一个小箱、中箱或者大箱打包好
 
## 其他算法
还在探究中。。。。。 

 
