#Pre
[HEVC学习笔记系列目录][2]
视频压缩时，先分割为若干个小的图像组（Group Of Pictures，GOP）,每个GOP又被划分为多个片（Slice）,一幅图像可以被分割为一个或者多个Slice,每个slice由一个或多个片段（Slice Segement，SS）组成,一个SS在编码时，先被分割为相同大小的树形结构单元（Coding Tree Unit，CTU）,每个CTU包括一个亮度属性编码块（Coding Tree Block，CTB）和两个色差CTB，
每个CTU按照四叉树分割成不同类型的编码单元（Coding Unit，CU）,
![][1]
一幅图像不仅可以划分为若干个Slice（条带状），也可以划分为若干个Tile（水平和垂直方向分割，一个矩形区域就是一个Tile），
在HEVC标准中，CTU最大是64x64，在x265中，可以用`--ctu, -s <64|32|16>`来配置ctu的大小，也可以通过`--min-cu-size <64|32|16|8>`来配置最小的cu的大小。

#PU的划分模式
对于一个2N×2N的CU来说
##帧内预测
2N×2N和N×N两种
##帧间预测
4种对称模式

也就是说PU最小4×4，最大64×64

+ 2N×2N(skip、intra模式)
+ 2N×N
+ N×2N
+ N×N(intra模式)这是一个测试

4种非对称模式

+ 2N×uU(上下1:3)
+ 2N×nD(上下3:1)
+ nL×2N(左右1:3)
+ nR×2N(左右3:1)

![][0]

[0]: http://www.findspace.name/wp-content/uploads/2015/08/puSplit.png
[1]: http://www.findspace.name/wp-content/uploads/2015/08/quadtree.jpg
[2]: http://www.findspace.name/easycoding/1434 