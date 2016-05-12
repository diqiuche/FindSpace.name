[TOC]

<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default"></script>

#HEVC帧间预测
预测编码（Prediction Coding）是指利用已编码的一个或几个样本值，根据某种模型或方法，对当前的样本值进行预测，并对样本真实值和预测值之间的差值进行编码。视频预测编码技术主要分为两大类：

+ 帧内预测，即利用当前图像内已经编码的像素生成预测值
+ 帧间预测，即利用当前图像之间已经编码图像的重建像素生成预测值

帧间预测：主要原理是为当前图像的每个像素块在之前已经编码的图像中找一个最佳匹配块，该过程称为运动估计（Motion Estimation,ME），其中，用于预测的图像称为参考图像（Reference Frame），参考块到当前像素块的位移称为运动向量（Motion Vector,MV）当前像素块与参考块的差值称为预测残差（Prediction Residual）。
现在有三种类型的图像：I P B，I图像只使用帧内编码，而P可以利用帧间预测编码，且只能是由前面图像推出后面图像
>MVD=MVc-MVp
MVc当前编码块的MV，MVp前一个已经编码块的MV，MVD为二者差值（大多数情况下MVD接近于0）

B图像可以使用三种预测方式：前向预测（Forward Prediction），后向预测（Backward Prediction），双向预测（Bi-direction Prediction）.
#关键技术
##运动估计（Motion Estimation）
大多数视频序列中，相邻图像内容非常相似，因此只需要将当前图像中运动物体的运动信息传给解码器。ME就是指提取当前图像运动信息的过程。
基于块的运动表示法（将图像分成大小不同的像素块，只要块大小选择合适，则各个块的运动形式可以看成是统一的）兼顾了运动估计精度和复杂度，是视频编码国际标准的核心技术。
###1.运动估计准则
常用的有：

+ 最小均方误差（Mean Square Error,MSE）
+ 最小平均绝对误差（Mean Absolute Difference,MAD）
+ 最大匹配像素数（Matching-Pixel Count,MPC）
+ 绝对误差和(Sum Of Absolute Difference,SAD)
+ 最小变换域绝对误差和(Sum Of Absolute Transformed Difference,SATD)

一般用SAD或者SATD。SAD不含乘除法，且便于硬件实现，因而使用最广泛。实际中，在SAD基础上还进行了别的运算来保证失真率。
块大小为MxN，\\(f_i\\)和\\(f_{i-1}\\)分别表示当前图像和参考图像的像素值，x,y分别表示MV的水平分量和垂直分量。

$$SAD(x,y)=\sum_{m=1}^{M}\sum_{n=1}^{N}|f_i(m,n)-f_{i-1}(m+x,n+y)|$$
###2.搜索算法
常见的搜索算法（x265中的算法）：
>\--me <integer|string\>
Motion search method. Generally, the higher the number the harder the ME method will try to find an optimal match. Diamond search is the simplest. Hexagon search is a little better. Uneven Multi-Hexegon is an adaption of the search method used by x264 for slower presets. Star is a three step search adapted from the HM encoder: a star-pattern search followed by an optional radix scan followed by an optional star-search refinement. Full is an exhaustive search; an order of magnitude slower than all other searches but not much better than umh or star.
+ dia 菱形
+ hex (default) 六边形
+ umh 可变半径六边形搜索(非对称十字六边形网络搜索)
+ star 星型
+ full 全搜索

主要说明两种搜索算法（后面的博客还会通过代码和图片详细说明）：

+ 全搜索： 所有可能的位置都计算两个块的匹配误差，相当于原块在搜索窗口内一个像素一个像素点的移动匹配
+ 菱形搜索： 在x265中实际是十字搜索，仅对菱形对角线十字上的块进行搜索

HM的则是全搜索和TZSearch以及对TZSearch的优化的搜索。TZSearch算法的大体步骤：

1. 确定起始搜索点。采用AMVP技术来确定起始搜索点，AMVP会给出若干个候选预测MV，编码器从中选择率失真代价最小的作为预测MV，并用其所指向的位置作为起始搜索点。
2. 以步长为1开始，在search window内进行菱形搜索，其中步长以2的整数次幂递增，选出率失真代价最小的点作为该步骤的搜索结果
3. 若步骤2中最优结果步长为1，则在该点周围进行两点搜索（不再赘述）
4. 若步骤2中最优结果步长大于某个阀值，则以该点为最优点为中心，在一定范围内进行全搜索，选择率失真代价最小的点作为新的最优点。
5. 以步骤4的最优点最为新的起始搜索点，重复2～4,细化搜索，当相邻两次细化搜索得到的最优点一致时停止细化搜索，此时的MV则为最终MV。

而x265则省去了很多步骤，后面详细说。

##MV预测
有时域和空域两种MV的预测方式，主要看空域。
HEVC在预测方面提出了两种新的技术--Merge && AMVP (Advanced Motion Vector Prediction)
都使用了空域和时域MV预测的思想，通过建立候选MV列表，选取性能最优的一个作为当前PU的预测MV，二者的区别：

+ Merge可以看成一种编码模式，在该模式下，当前PU的MV直接由空域或时域上临近的PU预测得到，不存在MVD，而AMVP可以看成一种MV预测技术，编码器只需要对实际MV与预测MV的差值进行编码，因此是存在MVD的。
+ 二者候选MV列表长度不同，构建候选MV列表的方式也有所区别


###Merge
![][0]
图中便是5个PU，但是标准规定最多四个，则列表按照A1-->B1-->B0-->A0-->(B2)的顺序建立，B2为替补，即当其他有一个或者多个不存在时，需要使用B2的运动信息。
###AMVP
而AMVP的选择顺序,左侧为A0-->A1-->scaled A0-->scaledA1 上方为B0-->B1--B2-->(scaled B0-->scaled B1-->scaled B2)，其中scaled A0表示将A0的MV进行比例伸缩。
>Merge技术
Merge模式会为当前PU建立一个MV候选列表，列表中存在5个候选MV及其对应的参考图像。通过遍历这5个候选MV，并进行率失真代价的计算，最终选取率失真代价最小的一个作为该Merge模式的最优MV。若边解码端依照相同的方式建立该候选列表，则编码器只需要传输最优MV在候选列表中的索引即可，这样大幅度节省了运动信息的编码比特数。Merge模式建立的MV候选列表中包含了空域和时域两种情形，而对于B Slice则包含组合列表的方式。
AMVP（Advanced Motion Vector Prediction）技术
AMVP利用空域、时域上运动矢量的相关性，为当前PU建立了候选预测MV列表。编码器从中选出最优的预测MV，并对MV进行差分编码；解码端通过建立相同的列表，仅需要运动矢量残差（MVD）与预测MV在该列表中的序号即可算出当前PU的MV。
类似于Merge模式，AMVP候选MV列表也包含空域和时域两种情形，不同的是AMVP列表长度仅为2。


然而，x265并不在乎标准，我们要的就是速度，所以在x265的代码中，只能看到它使用AMVP且对应的变量是

|图中的代号|x265中代码变量中包含|
|-|-|
|B2|ABOVE_LEFT|
|B1|ABOVE|
|B0|ABOVE_RIGHT|
|A1|LEFT|
|A0|BELLOW_LEFT|

且对左侧和上侧分别if-else，选出两个。

[0]: http://www.findspace.name/wp-content/uploads/2015/08/H265Merge.png "H265Merge"