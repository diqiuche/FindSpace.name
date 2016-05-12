#Tips

+ luma亮度 chroma色度，一个CTU=一个亮度CTB+2个色度CTB
+ 连续播放图像每秒超过24帧以上时，人眼无法辨别单幅的静态画面，画像序列看上去是平滑连续的视觉效果，这样连续的画面叫做视频
+ 量化（QUantization）是指将信号的连续取值（或大量可能的离散取值）映射为有限多个离散幅值的过程，实现信号取值多对一的映射。在视频编码中，残差信号经过离散余弦变换（DCT）之后，变换系数往往具有较大的动态范围。因此对变换系数进行量化可以有效的减少信号取值空间，进而获得更好的压缩效果。同时，由于多对一的映射机制，量化过程不可避免地会引入失真，它也是视频编码中产生失真的根本原因。
+ For uniprediction, a picture can be selected from either of these lists. For biprediction, two pictures are selected—one from each list.


x265 的命名规范
https://bitbucket.org/multicoreware/x265/wiki/Coding


HM
TEncGOP.cpp 1506  Allocate some coders, now the number of tiles are known.
Tile的数量在这里知道的。

getNumTileColumnsMinus1
getNumTileRowsMinus1



在HEVC中，CU是最基本的编码单元，每个CU由1个亮度CB、2个色度CB以及相应的语法元素组成。CB是之前已经分割好的CTB根据块中的图像内容而自适应划分的（划分规则：相对比较平坦的区域采用大尺寸的CB，而细节多的区域则采用较小尺寸的CB）。通常情况下，CB的形状是正方形，亮度分量CB的尺寸可以由8x8大小到亮度CTB的大小，色度CB的尺寸可以由4x4大小到色度CTB的大小（也就是说，亮度CTB的尺寸是亮度CB的最大可支持的尺寸；色度CTB的尺寸是色度CB的最大可支持的尺寸）。
CU可以分为两类：跳过型CU（Skipped CU）和普通CU。跳过型CU只能采用帧间预测模式，而且产生的运动向量和图像的残差信息不需要传送给解码器；普通CU则可以采用帧内预测和帧间预测两种方式进行预测，然后对残差数据以及附加的控制信息进行编码。
通常，在图像的右边界和下边界，一些CTU可能会覆盖部分超出图像边界的区域，这时CTU四叉树会自动分割，减小CB尺寸，使整个CB刚好进入图像。
（3）、每个CU还可以进一步分割成一个预测单元（PU）和变换单元（TU）。
PU是包含了预测信息的基本单元。PU包括了亮度PU、色度PU和相应的预测语法。一个CU可以包含一个或多个PU，PU的类型可以是跳过、帧内和帧间。
（4）、TU是变换和量化的基本单元，TU的尺寸可以大于PU，但不能超过CU。HEVC只定义了方形的TU，尺寸为4x4/8x8/16x16/32x32。每个CU可以包含一个或多个TU。


B：Bi-predictive（双向预测，即B帧或B条带，B条带中的CU可以采用帧内或帧间预测编码，每个预测块采用双向预测方式进行预测，B条带编码时同时使用参考图像列表0和参考图像列表1）
BLA：Broken Link Access
CABAC：Context-based Adaptive Binary Arithmetic Coding
CB：Coding Block（编码块，CB是之前已经分割好的CTB根据块中的图像内容而自适应划分的，一般来说，相对比较平滑的区域往往采用大尺寸的CB，而细节多的区域则采用较小尺寸的CB，以亮度CB为例，尺寸可以由8x8到CTB的大小，也即亮度CTB的尺寸是亮度CB的最大可支持的尺寸）
CBR：Constant Bit Rate
CRA：Clean Random Access
CPB：Coded Picture Buffer（编码图像缓冲区）
CTB：Coding Tree Block（编码树块，用来表示视频帧中相互独立的区域，即视频帧被分割成若干个互不重叠的CTB，HEVC中可以采用四叉树的方式，将CTB分割成更小的块，CTB的尺寸由编码器选择，可以比传统的宏块尺寸大，亮度CTB的尺寸可以是16x16/32x32/64x64，尺寸越大，压缩效果越好）
CTU：Coding Tree Unit（编码树单元，HEVC的基本处理单元，一个CTU中包括亮度CTB、2个色度CTB和相应的语法元素）
CU：Coding Unit（编码单元，HEVC中最基本的编码单元，与H.264标准中宏块的作用类似，每个CU由1个亮度CB、2个色度CB和相应的语法元素组成，CU可以分为两类，跳过型CU和普通CU，Skipped CU是一种与H.264/AVC标准中的跳过宏块类似的编码单元，不仅只能采用帧间预测模式，而且产生的运动向量和图像的残差信息不需要传送给解码端；而普通CU则可以采用帧内和帧间两种方式进行预测，然后对残差数据以及附加的控制信息进行编码）
CVS：Coded Video Sequence
DBF：Deblocking Filter（去块效应滤波器，DBF处于HEVC的解码循环中，是重建样本在被写入解码图像缓冲区之前需要经过的一个处理步骤，还有一个步骤是SAO，DBF的作用与H.264标准类似，是为了去除块效应，但只适应于块边界上的样本，但相比H.264，其决策与滤波过程被大大简化了，更易于并行处理）
DPB：Decoded Picture Buffer（解码图像缓冲区）
DUT：Decoder Under Test
EG：Exponential-Golomb
FIFO：First-In, First-Out（先进先出）
FIR：Finite Impulse Response
FL：Fixed-Length（固定长度）
GDR：Gradual Decoding Refresh
HRD：Hypothetical Reference Decoder
HSS：Hypothetical Stream Scheduler
I ：Intra（帧内或I条带，I条带内的所有编码单元都采用帧内预测进行编码）
IDR：Instantaneous Decoding Refresh
IRAP：Intra Random Access Point
LPS：Least Probable Symbol
LSB：Least Significant Bit
MPS：Most Probable Symbol
MSB：Most Significant Bit
NAL：Network Abstraction Layer（网络提取层）
P：Predictive（预测或P帧，除了I条带的编码类型，P条带中的一些CU还可以采用帧间预测进行编码，每个PB采用单向预测方式进行预测，P条带编码时只利用参考图像列表0）
PB：Prediction Block（预测块，当预测模式选择为帧内时，对于所有的块尺寸，PB尺寸和CB尺寸相同；当预测模式选择帧间时，则需要指明亮度和色度CB是否被分成1个、2个或4个PB，只有当CB尺寸等于所允许的最小尺寸时才被分成4个PB）
PPS：Picture Parameter Set
PU：Prediction Unit（预测单元，是包含了预测信息的基本单元，一个PU包括亮度和色度PB和相关预测语法，一个CU可以包含一个或者多个PU，PU的类型可以是跳过、帧内、帧间）
RADL：Random Access Decodable Leading (Picture)
RASL：Random Access Skipped Leading (Picture)
RBSP：Raw Byte Sequence Payload
RPS：Reference Picture Set（参考图像集）
SAO：Sample Adaptive Offset（样点自适应补偿，SAO是HEVC新引入的，SAO滤波器被自适应地用于所有满足特定条件的样本）
SEI：Supplemental Enhancement Information（补充的增强信息）
SODB：String Of Data Bits
SPS ：Sequence Parameter Set
STSA：Step-wise Temporal Sub-layer Access（逐步级TSA）
TB：Transform Block（变换块，CB可以采用四叉树分割方式递归的分割成若干个TB，注意：与先前的标准不同，对于帧间预测CU，HEVC允许TB跨越多个PB，以此最大限度地提高TB分割的编码效率）
TR：Truncated Rice
TSA ：Temporal Sub-layer Access（时间子层访问）
TU：Transform Unit（变换单元，是变换和量化的基本单元，TU的尺寸可以大于PU但不能超过CU，HEVC只定义了方形的TU，尺寸为NxN大小，N=4 、8、16、32，每一个CU可以包含一个或者多个TU）
UUID：Universal Unique Identifier
VBR：Variable Bit Rate
VCL：Video Coding Layer（视频编码层）
VPS：Video Parameter Set（视频参数集）
VUI ：Video Usability Information（视频可用性信息，与之前的SEI一起可以提供视频显示所需的各种有用信息，包括视频图像的时序、视频信号的颜色空间、3D立体帧封装信息和其他显示提示信息等）


reference_pictures: A space-separated list of num_ref_pics integers, specifying thePOC of the  reference pictures kept,relative the POC ofthe current frame. The picture list shall be ordered, first with negative numbers from largest to smallest,followed by positive numbers from smallest to largest (e.g. -1 -3 -5 1 3). Note that any pictures not supplied in this list willbe discarded and therefore not available as reference pictures later.