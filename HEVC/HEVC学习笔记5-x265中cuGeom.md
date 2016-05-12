#Pre 
[HEVC学习笔记系列目录][0]
从compressCTU里面，根据rd值划分了不同的编码帧内CU的方法,rd设置为5或6才符合HEVC标准，则接下来进行函数`compressInterCU_rd5_6`的解析。
```
void Analysis::compressInterCU_rd5_6(const CUData& parentCTU, const CUGeom& cuGeom, uint32_t &zOrder, int32_t qp)
```
#`compressInterCU_rd5_6`函数大体
两个`if`作为主要部分：

+ `if (mightNotSplit)`
+ `if (mightSplit && (!md.bestMode || !md.bestMode->cu.isSkipped(0)))`

很明显，字面意义：看当前CTU是否仍需要划分，这里的划分是说四叉树划分。四叉树划分最多三层，也就是说标准中，CU最小是8x8，最大64x64。
而这两个flag的定义主要与cuGeom有关。
```
bool mightSplit = !(cuGeom.flags & CUGeom::LEAF);
bool mightNotSplit = !(cuGeom.flags & CUGeom::SPLIT_MANDATORY);
```
#cuGeom
![][1]
Eclipse中F3跳转到cuGem的定义，cuGeom是一个结构体：
```
struct CUGeom
{
    enum {
        INTRA           = 1<<0, // CU is intra predicted
        PRESENT         = 1<<1, // CU is not completely outside the frame
        SPLIT_MANDATORY = 1<<2, // CU split is mandatory if CU is inside frame and can be split
        LEAF            = 1<<3, // CU is a leaf node of the CTU
        SPLIT           = 1<<4, // CU is currently split in four child CUs.
    };
    
    // (1 + 4 + 16 + 64) = 85.
    enum { MAX_GEOMS = 85 };

    uint32_t log2CUSize;    // Log of the CU size.
    uint32_t childOffset;   // offset of the first child CU from current CU
    uint32_t absPartIdx;    // Part index of this CU in terms of 4x4 blocks.
    uint32_t numPartitions; // Number of 4x4 blocks in the CU
    uint32_t flags;         // CU flags.
    uint32_t depth;         // depth of this CU relative from CTU
};
```
注释很清楚，（x265中注释不多，但是很多地方注释得很必要），`MAX_GEONS=85`,其中，这里的`(1 + 4 + 16 + 64) = 85.`因为最多可以划分3层，如果地一层不划分，有1块64x64的大CU，划分则就有四个32x32的CU，则再下一层有16个16x16的CU，最底下有64个8x8的CU。
depth则是当前CU在zorder上与CTU的距离，64x64的大CU则就是0。
而flags，在eclipse中Ctrl+shift+G，搜索项目中对选中变量/函数的使用的地方。
则会发现在/source/common/cudata.cpp里面有`CU_SET_FLAG`的字眼，跳过去：
```cpp
void CUData::calcCTUGeoms(uint32_t ctuWidth, uint32_t ctuHeight, uint32_t maxCUSize, uint32_t minCUSize, CUGeom cuDataArray[CUGeom::MAX_GEOMS])
```
不贴详细代码了，三重循环：
```
    for (uint32_t log2CUSize = g_log2Size[maxCUSize], rangeCUIdx = 0; log2CUSize >= g_log2Size[minCUSize]; log2CUSize--)
        for (uint32_t sbY = 0; sbY < sbWidth; sbY++)
            for (uint32_t sbX = 0; sbX < sbWidth; sbX++)
```
g_log2Size，是对Size的log2取值:
```
const uint8_t g_log2Size[MAX_CU_SIZE + 1] =
{
    0, 0, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3,
    4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4,
    5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5,
    5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5,
    6
};
```
在循环里面是对cuGeom实例的各种变量赋值，明显是进行四叉树结构的一些initialization.至于划分flag,我没有细究`#define CU_SET_FLAG(bitfield, flag, value) (bitfield) = ((bitfield) & (~(flag))) | ((~((value) - 1)) & (flag))`，这个宏定义函数，根据HEVC标准，所有的分支都要走，每个CTU都要划分三层，然后根据划分过程中，结果的优劣来判定到底划分与否，把信息写入到编码块中。

[0]: http://www.findspace.name/easycoding/1434 
[1]: http://www.findspace.name/wp-content/uploads/2015/08/quadtree.jpg