#Pre
还是利用博客来记录自己学习HEVC的历程吧。
因为是2015年五六月份才开始接触，7月份放假才有完整的时间学习，所以很多东西可能理解并不深刻，**甚至会有错误**,欢迎各位指点，互相学习。
每篇博客基本都会在最后的Reference给出参考的文章，主要参考一本书：
>《新一代高效视频编码H.265/HEVC原理标准与实现》 万帅 杨付正 编著 电子工业出版社
ISBN 9787121246999

~~我主要看的是HEVC帧间预测，x265的代码，但是网上对它的解读实在太少，大部分都是HM的。x265和HM的代码实现差别还是很大的，而且x265比HM快10-100倍，有人做过比较：[Comparison of open-source HEVC encoders][0]。最后决定用x265来实现idea。~~
**update**：
由于一些原因x265没有达到理想的状态，所以转到了HM。不过x265代码的解读也还会写，HM会多一些。对HM则可能会看更多部分。

我已经假定读者已经对HEVC有所了解，但是可能某些地方不是很清楚或者对于代码实现不清楚，因此在文章中会尽量不做概念赘述，但是一些必要的概念都会给出说明。
会陆续把自己整理的东西发上来。
#[x265常用参数配置][3]
#HEVC帧间预测
##[HEVC学习笔记1-帧间预测编码][1]
主要是对帧间预测的重点的简要介绍
##[HEVC学习笔记2-x265][2]
对x265项目结构的主要说明，以及移植到arm平台的补丁文件
##[HEVC学习笔记3-PU划分及CTU结构][4]
CTU的四叉树划分，以及PU的八种划分结构
##[HEVC学习笔记4-x265中编码CTU][5]
analysis.cpp中compressCTU函数的解析
##[HEVC学习笔记5-x265中cuGeom][6]
四叉树划分结构的记录
##[HEVC学习笔记6-compressInterCU_rd5_6][7]
编码CU的函数，包括PU的划分和四叉树的划分。
##[HEVC学习笔记7-开始解读predInterSearch][8]
从这一节开始，才开始深入重点部分。接下来的几节，会比较详细的结合代码看。我讲述的方法遵循一贯的习惯：先看整体大概可能做什么，然后再分部分看，确认符合自己预期或者调整对框架的理解。
##[HEVC学习笔记8-GOP中参考帧相关][11]
介绍了GOP中参考帧相关的内容，比如GOP结构，GOP在配置文件中的体现，以及重点对参考帧的设置等。
##[HEVC学习笔记9-sad计算函数]( http://www.findspace.name/easycoding/1544)
本文主要简单追一下sad的计算函数，是如何调用，以及做简单注释。

[text200x90]

---
UPDATE 2015.11.30
暂停更新了好久，因为一些原因。现在转向了HM，不过x265的也会再更新几篇。
一些零碎的片段，还没法写成完整文章的，我一般都发在了以下几个地方：
##[写在了leanote上的笔记集合][10]
##http://hevc.leanote.com/ 


[0]: https://damienschroeder.wordpress.com/2014/10/10/comparison-of-open-source-hevc-encoders/ "Comparison of open-source HEVC encoders"
[1]: http://www.findspace.name/easycoding/1436
[2]: http://www.findspace.name/easycoding/1452
[3]: http://www.findspace.name/easycoding/1428 
[4]: http://www.findspace.name/easycoding/1453
[5]: http://www.findspace.name/easycoding/1455 
[6]: http://www.findspace.name/easycoding/1456
[7]: http://www.findspace.name/easycoding/1457 
[8]: http://www.findspace.name/easycoding/1459

[9]: https://plus.google.com/u/0/collection/gTnpv
[10]: http://hevc.leanote.com/ 
[11]: http://www.findspace.name/easycoding/1527