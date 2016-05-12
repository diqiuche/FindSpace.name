#Pre
接下来直接看x265中对CTU块的编码，从对一个CTU的编码开始，CTU的四叉树划分，PU的划分，sad值计算，然后选择最佳匹配块，我将尽可能把我的理解以正确的方式写出来，欢迎大家批评指正。
[HEVC学习笔记系列目录][0]
我的x265的版本(hg log查看到的)：
```
changeset:   10798:79f4906e9cb8
tag:         tip
user:        Steve Borho <steve@borho.org>
date:        Sat Jul 11 12:31:21 2015 -0500
summary:     test: correctly report 12bit builds
```

#x265中analysis.cpp函数compressCTU
```
Mode& Analysis::compressCTU(CUData& ctu, Frame& frame, const CUGeom& cuGeom, const Entropy& initialContext)
```
参数中的ctu就是当前要编码的CTU，frame就是当前ctu所在的frame，而cuGeom则是CTU的四叉树结构。
#函数的大体过程
首先是初始化从传入参数中来的信息到当前命名空间的变量，个人认为是便于程序模块化编程。
然后进行slice类型判断，看是进行帧内预测还是帧间预测。
最后返回经过预测之后得到的当前CTU的最佳的四叉树划分模式和PU划分模式已经得到的sad值。
#compressCTU函数中重要的函数或变量

+ `m_param->analysisMode`，m_param是在运行x265时输入的参数配置，分析模式默认关闭，跳过。
+ `ProfileCUScope(ctu, totalCTUTime, totalCTUs);`则主要是为了记录日志，记录时间等信息。
+ `compressIntraCU(ctu, cuGeom, zOrder, qp);`帧内编码，zOoder是当前编码的CU在CTU的四叉树上的z轴的坐标，就是记录处于第几层

#rdLevel
下面的则比较重要，
`rdLevel`,对应配置参数的`--rd`，Level of RDO in mode decision。官方手册说明：
```
The higher the value, the more exhaustive the analysis and the more rate distortion optimization is used. The lower the value the faster the encode, the higher the value the smaller the bitstream (in general). Default 3
```
|Level|Description|
|-|-|
|0|sa8d mode and split decisions, intra w/ source pixels|
|1|recon generated (better intra), RDO merge/skip selection|
|2|RDO splits and merge/skip selection|
|3|RDO mode and split decisions, chroma residual used for sa8d|
|4|Currently same as 3|
|5|Adds RDO prediction decisions|
|6|Currently same as 5|

RDO Rate Distortion Optimation (率失真优化),可以理解为图像的质量，但实际上，这里影响的更多的是压缩的比率。
**手册在这里说的并不清楚！！**仅仅说rd数字越大越详细，但！实际上，它的详细和amp以及rect两个配置参数有关。
##amp&&rect
+ asymmetric motion partitioning (amp) 
+ rectangular (rect)

```
--rect, --no-rect
Enable analysis of rectangular motion partitions Nx2N and 2NxN (50/50 splits, two directions). Default disabled

--amp, --no-amp
Enable analysis of asymmetric motion partitions (75/25 splits, four directions). At RD levels 0 through 4, AMP partitions are only considered at CU sizes 32x32 and below. At RD levels 5 and 6, it will only consider AMP partitions as merge candidates (no motion search) at 64x64, and as merge or inter candidates below 64x64.

The AMP partitions which are searched are derived from the current best inter partition. If Nx2N (vertical rectangular) is the best current prediction, then left and right asymmetrical splits will be evaluated. If 2NxN (horizontal rectangular) is the best current prediction, then top and bottom asymmetrical splits will be evaluated, If 2Nx2N is the best prediction, and the block is not a merge/skip, then all four AMP partitions are evaluated.

This setting has no effect if rectangular partitions are disabled. Default disabled
```
rect就是对半分的那两种模式，默认竟然是disabled！
amp就是前面说的[划分模式][1]中非对称的四种模式，在rdlevel是0..4的时候，只有在CU大小是32x32及一下才开启。
怪不得x265快！
而且运行时通过设置参数开启rect和amp以及设置rd为5之后，会明显发现速度慢了一倍不止！

而且x265也直接根据rd的级别单独写了`compressInterCU_rd5_6`和`compressInterCU_rd0_4`，还一个`compressInterCU_dist`，是`Use multiple threads to measure CU mode costs.`，但是我的开发板没有配置成功thread部分。这个后面再讲。

[0]: http://www.findspace.name/easycoding/1434 
[1]: http://www.findspace.name/easycoding/1453