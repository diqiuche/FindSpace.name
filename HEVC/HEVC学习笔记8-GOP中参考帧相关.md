#Pre 
[HEVC学习笔记系列目录][0]
GOP一直没有理清楚，现在记笔记总结一下现有的理解和遇到的问题。有错误请指正。
#GOP
视频序列由若干时间连续的图像构成，在对其进行压缩时，先将视频序列分割为若干个小的图像组(Group Of Pictures,GOP).在视频编码中，存在两种GOP类型：封闭式GOP（Closed GOP）和开放式GOP（Open GOP），封闭式GOP如下图所示，每个GOP以IDR（Instantaneous Decoding Refresh）图像开始，各个GOP之间独立编解码。
![][1]
   开放式GOP如下图所示，第一个GOP的第一个帧内编码图像为IDR图像，后续GOP中的第一个帧内编码图像为non-IDR图像，也就是说，后面GOP中的帧间编码图像可以越过non-IDR图像，使用前一个GOP中的已编码图像做参考图像。
![][2]
#GOP参数
在HM的docs里software-manual.pdf里，有GOP Structure table一节，里面有详细的参数说明，这里仅对其中一部分进行说明，
在配置文件中Coding Structure部分，定义了有关GOP的设置。参数的具体意义：

|配置参数|意义|
|-|-|
|Type|条带类型，为I,B,P之一；|
|POC|GOP内图像的显示顺序，取值范围为1-GOPSize;|
|QPOffset|量化参数偏移量，用于计算本帧的实际量化参数；|
|QPFactor|用于率失真优化的权值；|
|tcOffsetDiv2|环路滤波器参数的偏移量；|
|betaOffsetDiv2|环路滤波器参数的偏移量；|
|temporal_id|当前帧所在的时域子层的序号；|
|num_ref_pics_active|参考图像列表(reference picture lists)L0和L1的大小；表明在编码过程中使用了多少个参考帧；|
|num_ref_pics|当前帧所保有的参考帧数，包括当前帧以及未来帧所用到的参考帧；|
|reference pictures|保存相对于当前帧POC的参考帧的POC，数量又num_ref_pics指定。|
|predict|定义inter_ref_pic_set_prediction_flag的值。0表示编码RPS不需要RPS预测，并忽略后面的deltaRIdx-1， deltaRPS等参数；1表示需要RPS预测，使用deltaRIdx-1, deltaRPS, num ref idcs和Reference idcs；2表示需要RPS预测，但仅使用deltaRIdx-1；|
|deltaRIdx-1|当前RPS索引同预测RPS索引的差值-1；|
|deltaRPS|预测RPS同当前RPS的POC之差；|
|num_ref_idcs|编码当前RPS的ref_idcs的数量；|
|reference_idcs|指定RPS间预测的ref_idcs。|

num_ref_pics_active，num_ref_pics，reference pictures这三个参数重点描述了参考帧的设置，以下是manual原文。
>**num_ref_pics_active**: Size of reference picture lists L0 and L1, indicating how many reference pictures in each direction that are used during coding.
**num_ref_pics**: The number of reference pictures kept for this frame. This includes pictures that are used for reference for the current picture as well as pictures that will be used for reference in the future.
**reference_pictures**: A space-separated list of num_ref_pics integers, specifying the POC of the  reference pictures kept, relative the POC of the current frame. The picture list shall be ordered,first with negative numbers from largest to smallest, followed by positive numbers from smallest to largest (e.g. -1 -3 -5 1 3). Note that any pictures not supplied in this list will be discarded and therefore not available as reference pictures later.

#Reference pictures
这里说明一下`reference_pictures`的数据是怎么来的，官方文档中已经写了，但是我感觉有些不明确。
![][5]
首先一个问题是GOPsize的大小，在上面这张图里是多少。按照POC的说明（Display order of the frame within a GOP, ranging from 1 to GOPSize.）但是图中的POC从0-8,那么GOP的size是9吗？还是8？官方文档里的`This coding structure is of size 4.`也没明白是什么意思。
我的判断，这里的举例是开放式GOP，第0帧是帧内预测的IDR图像，接下来就是GOPsize=4的GOP，但是为什么图中后面的POC是8.而且这个IDR帧分布在哪里，尚未清楚。后续补充。

POC就是实际播放时的顺序，decoder 顺序是解码的顺序，同样也是编码的顺序，因为**解码实际上是编码的逆过程**。I B P帧的区别在前面的文章里说过了，P帧仅参考一帧，B帧需要参考两帧。 
POC为4的是实际编/解码的第二帧，仅参考POC0,则POC4的reference pictures就是0-4=-4,即它前面的第四帧，POC2参考POC0和POC4,则它的refernec pictures是0-2=-2和4-2=2，即相对它自己的前面第二帧和后面第二帧。
POC1参考POC0和POC2，我的理解，但是它间接参考了POC4,所以它的参考帧序列是-1,1,3。官方手册里说`Frame3（POC1） is a special case: even though it only references pictures with POC 0 and 2, it also needs to include the picture with POC 4, which must be kept in order to be used as a reference picture in the future.`这里没懂。

而样例配置文件`encoder_lowdelay_P_main.cfg`里的GOPsize是4,配置的四个frame的reference pictures是-1 -5 -9 -13 ， -1 -2 -6 -10，-1 -3 -7 -11 ，-1 -4 -8 -12，很多都超出了自己GOP的范围，所以大多数情况下，都只参考前面一帧或两帧。
代码中的体现待补。

#后记
断了这么久才重新开更HEVC的东西，因为x265我原来想优化的部分，占比不如想象中高，而且代码太难读，所以转到了HEVC的官方标准实现HM。
#Reference
[编码结构之编码时的分层处理架构][3]
[【HEVC学习与研究】22、回顾：让人崩溃的GOP（下）——HEVC及其RPS][4]

[0]: http://www.findspace.name/easycoding/1434 
[1]: http://img.blog.csdn.net/20151120204156714
[2]: http://img.blog.csdn.net/20151120204246329
[3]: http://blog.csdn.net/frd2009041510/article/details/49951643
[4]: http://blog.sina.com.cn/s/blog_520811730101n5zw.html
[5]: http://www.findspace.name/wp-content/uploads/2016/01/gop.jpg