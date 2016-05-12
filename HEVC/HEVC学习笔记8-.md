#Pre 
[HEVC学习笔记系列目录][0]
上一节讲了`PredictionUnit pu(cu, cuGeom, puIdx);`，新建了pu变量进行了简单的初始化，现在要列用它对MotionEstimaion变量进行初始化以及对pu进一步的初始化：
`m_me.setSourcePU(*interMode.fencYuv, pu.ctuAddr, pu.cuAbsPartIdx, pu.puAbsPartIdx, pu.width, pu.height);`
#setSourcePU
```
/* Called by Search::predInterSearch() or --pme equivalent, chroma residual might be considered */
void MotionEstimate::setSourcePU(const Yuv& srcFencYuv, int _ctuAddr, int cuPartIdx, int puPartIdx, int pwidth, int pheight)
{
    ...
    sad_x4 = primitives.pu[partEnum].sad_x4;
    ...
    ctuAddr = _ctuAddr;
    absPartIdx = cuPartIdx + puPartIdx;
    blockwidth = pwidth;
    blockOffset = 0;
    ...
    /* copy PU from CU Yuv */
    fencPUYuv.copyPUFromYuv(srcFencYuv, puPartIdx, partEnum, bChromaSATD);
}
```
sad_x4是函数指针，`primitives.pu[partEnum].sad_x4`则已经根据pu的大小对sad函数进行了设置，lx，ly已经设置了。后面讲到sad时会详细看。
省略部分代码，看`copyPUFromYuv`这个函数，
#copyPUFromYuv
```
/* This version is intended for use by ME, which required FENC_STRIDE for luma fenc pixels */
void Yuv::copyPUFromYuv(const Yuv& srcYuv, uint32_t absPartIdx, int partEnum, bool bChroma)
{	
    X265_CHECK(m_size == FENC_STRIDE && m_size >= srcYuv.m_size, "PU buffer size mismatch\n");
    ...
    const pixel* srcY = srcYuv.m_buf[0] + getAddrOffset(absPartIdx, srcYuv.m_size);
    primitives.pu[partEnum].copy_pp(m_buf[0], m_size, srcY, srcYuv.m_size);
    ...
}
//F3跳转到函数的定义
// Copy portion of srcYuv into ME prediction buffer
void   copyPUFromYuv(const Yuv& srcYuv, uint32_t absPartIdx, int partEnum, bool bChroma);

```
这里srcYuv就是fecYuv，当前编码的CU所属的帧，m_buf[0]是luma值，

##getAddrOffset的定义
```
static int getAddrOffset(uint32_t absPartIdx, uint32_t width)
    {
        int blkX = g_zscanToPelX[absPartIdx];
        int blkY = g_zscanToPelY[absPartIdx];
        return blkX + blkY * width;
    }
```
而absPartIdx往回追，从`copyPUFromYuv`传入absPartIdx，在调用copyPUFromYuv的setSourcePU里传入puPartIdx，在调用setSourcePU时，传入的是pu.puAbsPartIdx,pu的设置在`PredictionUnit pu(cu, cuGeom, puIdx);`里，在他的构造函数里，`cu.getPartIndexAndSize(puIdx, puAbsPartIdx, width, height);`，
```
void CUData::getPartIndexAndSize(uint32_t partIdx, uint32_t& outPartAddr, int& outWidth, int& outHeight) const
{
    int cuSize = 1 << m_log2CUSize[0];
    int partType = m_partSize[0];

    int tmp = partTable[partType][partIdx][0];
    outWidth = ((tmp >> 4) * cuSize) >> 2;
    outHeight = ((tmp & 0xF) * cuSize) >> 2;
    outPartAddr = (partAddrTable[partType][partIdx] * m_numPartitions) >> 4;
}
```
找到了，这里的outPartAddr经过一系列传递，最终到了前面getAddrOffset的absPartIdx,`m_numPartitions = NUM_4x4_PARTITIONS >> (depth * 2);`，而partAddrTable
```
// Partition Address table.
// First index is partitioning mode. Second index is partition address.
const uint32_t partAddrTable[8][4] =
{
    { 0x00, 0x00, 0x00, 0x00 }, // SIZE_2Nx2N.
    { 0x00, 0x08, 0x08, 0x08 }, // SIZE_2NxN.
    { 0x00, 0x04, 0x04, 0x04 }, // SIZE_Nx2N.
    { 0x00, 0x04, 0x08, 0x0C }, // SIZE_NxN.
    { 0x00, 0x02, 0x02, 0x02 }, // SIZE_2NxnU.
    { 0x00, 0x0A, 0x0A, 0x0A }, // SIZE_2NxnD.
    { 0x00, 0x01, 0x01, 0x01 }, // SIZE_nLx2N.
    { 0x00, 0x05, 0x05, 0x05 }  // SIZE_nRx2N.
};
```
在调用`m_partSize`的地方，可以看到有一个switch模块，这个模块说明了`m_partSize`中的内容是`SIZE_2Nx2N`这样的东西，而`SIZE_2Nx2N`已经在前面说过了，与其他几种划分模式构成了划分模式的集合，index从0开始。`NUM_4x4_PARTITIONS`是256,读者可以自己追下这个的值是怎么来的，很简单。而256是16x16,就是说64x64的CTU中含有256个4x4的小块，而这个partAddrTable则就是当前PU块在256中的坐标，当然是一维坐标。
假设我们现在正在追64x64的块，pu划分模式是SIZE_2NxN,则depth是0,`m_numPartitions=256`，一个CU分成了两块PU，则第一块的起始坐标outPartAddr=0 ，第二块的partAddrTable\[1\]\[1\]=8，outPartAddr=(8×256)>>4=128，可以对照[HEVC学习笔记3-PU划分及CTU结构][1]的图来检查,是正确的，注意现在是一维空间。

对一些变量，我会选择性的详细说明追踪过程，并不是说其他的部分就一定不重要，如果不看，可能会阻碍对其他的理解，**代码不是看十遍就能读懂的**。

回到getAddrOffset，里面的width是指定的最到的CU的大小（即CTU大小）默认是64x64，width=64.则
```
blkX=0;
blkY=32;
return 32*64
```

[0]: http://www.findspace.name/easycoding/1434 
[1]:  http://www.findspace.name/easycoding/1453