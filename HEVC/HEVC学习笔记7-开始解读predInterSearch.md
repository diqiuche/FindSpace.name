#Pre 
[HEVC学习笔记系列目录][0]
从这一节开始，才开始深入重点部分。接下来的几节，会比较详细的结合代码看。我讲述的方法遵循一贯的习惯：先看整体大概可能做什么，然后再分部分看，确认符合自己预期或者调整对框架的理解。
#`predInterSearch`
先接着看`checkInter_rd5_6`，抛去关于analysisMode的，整个函数就只剩下了8行：
```
void Analysis::checkInter_rd5_6(Mode& interMode, const CUGeom& cuGeom, PartSize partSize)
{
    interMode.initCosts();
    interMode.cu.setPartSizeSubParts(partSize);
    interMode.cu.setPredModeSubParts(MODE_INTER);
    int numPredDir = m_slice->isInterP() ? 1 : 2;

    uint32_t refMask[2] = { 0, 0 };
    predInterSearch(interMode, cuGeom, true, refMask);

    /* predInterSearch sets interMode.sa8dBits, but this is ignored */
   encodeResAndCalcRdInterCU(interMode, cuGeom);

}
```
核心就剩下了`predInterSearch()`
```
void Search::predInterSearch(Mode& interMode, const CUGeom& cuGeom, bool bChromaMC, uint32_t refMasks[2])
```
#主要过程
同样忽略B slice还有分析模式的判断，则主要过程是：
对下面用到的一些变量进行初始化，根据PU划分模式获得的块数来循环，每次循环都针对每一块PU，最后对每一种方式得出的结果进行比较，选出最优的结果，就是最小的cost，存到cu里。
在对每块pu的处理中，[先从几个candidates中按照标准选择两块，再从中挑一块作为MVP][1]，就是先确定一个点作为搜索的中心，然后扩展参数设置的merange，即searchwindow的大小，在这个searchwindow内进行参数设置的搜索方式（菱形搜索、四边形搜索、六边形搜索等等），搜索的时候就会更新cost，搜索完毕以后就已经记录了最佳的块。最终的块就是最佳匹配块。
#详细部分
`int numPredDir  = slice->isInterP() ? 1 : 2;`，B帧和P帧的区别在[第一篇笔记][1]里已经提过，其实最主要是预测的方向不同，P只能用前向预测，而B可以是多方向的，在x265中是双向的。
`int numPart     = cu.getNumPartInter();`是指当前PU划分的块数，取值范围1,2,4

来看pu的初始化和定义`PredictionUnit pu(cu, cuGeom, puIdx);`
```
struct PredictionUnit
{
    uint32_t     ctuAddr;      // raster index of current CTU within its picture
    uint32_t     cuAbsPartIdx; // z-order offset of current CU within its CTU
    uint32_t     puAbsPartIdx; // z-order offset of current PU with its CU
    int          width;
    int          height;

    PredictionUnit(const CUData& cu, const CUGeom& cuGeom, int puIdx);
};
```
raster index，实际上，在x265中，很多逻辑上的二维，都是直接一个起始指针加上stride得到的。很多正常情况下用二维数组表示的，都是用一维来表示的，例如a\[i\]\[j\]实际上就是a[i*rowStride+j],而它很多地方都没有定义过二维数组或者二级指针，推测是为了速度和灵活性，二级指针相对一级指针不好控制。
先![][2]：
```
template<int lx, int ly>
void sad_x4(const pixel* pix1, const pixel* pix2, const pixel* pix3, const pixel* pix4, const pixel* pix5, intptr_t frefstride, int32_t* res)
{
    res[0] = 0;
    res[1] = 0;
    res[2] = 0;
    res[3] = 0;
    for (int y = 0; y < ly; y++)
    {
        for (int x = 0; x < lx; x++)
        {
            res[0] += abs(pix1[x] - pix2[x]);
            res[1] += abs(pix1[x] - pix3[x]);
            res[2] += abs(pix1[x] - pix4[x]);
            res[3] += abs(pix1[x] - pix5[x]);
        }

        pix1 += FENC_STRIDE;
        pix2 += frefstride;
        pix3 += frefstride;
        pix4 += frefstride;
        pix5 += frefstride;
    }
}
```
上面的代码我觉得x265用的非常精彩，不仅限于代码的书写，还有调用的方法。因为我一直做的是菱形搜索算法的改变，所以对接下来的函数看的多，说的也会更加详细一点。
在上面的sdx_x4中，计算了菱形搜索中四个方向的参考帧中的参考PU块的sad值计算，sad值计算的公式在第一篇笔记中也已经说明了。而模板lx,ly则多样化了函数的调用，PU的大小从64x64到8x8都可以很容易的调用它，而且不必显示传入参数。
两个stride分别是fref(参考帧)的和fenc(编码帧)的，稍后会从头跟踪他们的来源。




[0]: http://www.findspace.name/easycoding/1434 
[1]: http://www.findspace.name/easycoding/1436
[2]: https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/03/0395d2dafc31c80cd307b9981726fdfc4eab0c47_full.jpg