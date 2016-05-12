


+ 视频压缩时，先分割为若干个小的图像组（Group Of Pictures，GOP）
+ 每个GOP又被划分为多个片（Slice）
+ 一幅图像可以被分割为一个或者多个Slice
+ 每个slice由一个或多个片段（Slice Segement，SS）组成
+ 一个SS在编码时，先被分割为相同大小的树形结构单元（Coding Tree Unit，CTU）
+ 每个CTU包括一个亮度属性编码块（Coding Tree Block，CTB）和两个色差CTB，每个CTU按照四叉树分割成不同类型的编码单元（Coding Unit，CU）
+ 一幅图像不仅可以划分为若干个Slice（条带状），也可以划分为若干个Tile（水平和垂直方向分割，一个矩形区域就是一个Tile），

+ CTB的大小L×L，L=16,32,64；
+ 一个CTB可以作为一个CB，也可以四叉树进一步划分。
+ CB的大小L×L，L=8,16,32,64

+ 预测单元(Prediction Unit，PU)是针对CU来说的，最后得出的是CU的一部分或者CU，这个根据PU的划分来说。

+ 亮度CTB和色度CTB均可以看为CTB的属性，随着CTB的分割而分割

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
![][1]

##运动估计
提取当前图像运动信息的过程，目的是为当前块在参考图像中找一个最佳匹配块。常用的匹配准则：最小均方误差(Mean Square Error,MSE),最小平均绝对误差(Mean Absolute Difference,MAD)和最大匹配像素数(Matching-Pixel Count,MPC).为了简化计算，一般用绝对误差和(Sum of Absolute Difference,SAD)来代替MAD。
SAD准则：

MVD=MVc-MVp  多数情况下接近0
MVc:当前块的MV
MVp：前一个已编码块的mv
MVP：MV prediction 预测MV
#论文：
HEVC Encoding Optimization Using Multi-core  TCSVT

#Tips
shared memery limition:48kb
①减小块合并的依赖：
HEVC中的块合并功能可以通过时空邻域信息获取预测方向、参考索引和运动矢量等运动信息。在该模式中，merge_idx表示该从哪一个候选中获取运动信息，该变量采用截断一元编码。理论上其最大长度cMax应设为PU中用于合并的候选列表的长度。但是这需要建立列表以获取表长度，增加解析过程的依赖性。另外，建立候选块列表所需的运算量也相当大，因此该方法效果不佳。
在HEVC中cMax在条带头中标记，并不依赖与表的长度。为了弥补定长cMax带来的效率损失，在表长度不足的情况下，将联合/零合并候选块加入表中。
②减小运动矢量预测的依赖：
在未开启块合并模式下，运动矢量由邻域块的MV进行预测，并通过MVP和MVD记录。在H.264中采用了单一MV预测，采用左、上和右上三个方向的邻域块的中值作为运动矢量预测值；在HEVC中采用了“先进运动矢量预测”（AMVP）的方法，从时间和空间邻域中选定多个候选块数据，经过优化处理，保留两个较优的候选块。mvp−l0−flag标识哪一个候选的MV选作了MVP，并且即使列表中只有一个候选块该标识也会存在。默认候选为0矢量，因此列表永不为空。

mvp:2086 search.cpp

printf("mv: %.4f\n",sqrt(cu.m_mv[0]->x*cu.m_mv[0]->x+cu.m_mv[0]->y*cu.m_mv[0]->y));

./x265 --fps 60 --input-res 416x240 /home/ubuntu/BQSquare_416x240_60.yuv -o outvideo  --csv stat-416x240-60 --csv-log-level 3 --frame-threads 16 --pools "" --ref 4 --merange 128 --me 4>a.txt

./x265 --fps 50 --input-res 832x480 /home/ubuntu/BasketBall_832x480_50.yuv -o outvideo  --csv stat-832x480-50 --csv-log-level 3  --ref 4 --merange 128 --me 4  --frames 20 >a.txt

BasketBall_832x480_50.yuv
7.3号版本

#推荐
推荐书籍：
ISBN： 9787121246999
新一代高效视频编码H.265/HEVC:原理、标准与实现
论文：
Overview of the High Efﬁciency Video Coding 
HEVC的概览
直接[google学术][2]就可以，

把要编码的块分成4x4的小块进行计算sad，保存计算的sad值。
























[1]: /home/find/Dropbox/Findspace.name/HEVC/puSplit.png
[2]: http://www.findspace.name/res/72 "google学术"

<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default"></script>