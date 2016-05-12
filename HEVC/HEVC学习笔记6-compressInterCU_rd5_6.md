#Pre 
[HEVC学习笔记系列目录][0]
两个if构成了`compressInterCU_rd5_6`函数的主要内容，来看如果当前不继续划分四叉树会出现什么情况。
#if (mightNotSplit)

还是先看大体过程：
两个initSubCU
然后根据earlySkip进行PU的划分，可以从`if (!earlySkip)`的内部看到很多`PRED_2Nx2N`等的字眼，符合PU的划分。
Eclipse中在打括号对的其中一个上，Ctrl+shift+p可以跳转到大括号的另外一半上。
##if (!earlySkip)
可以看到几乎都是这样的格式：
```
md.pred[PRED_2Nx2N].cu.initSubCU(parentCTU, cuGeom, qp);
checkInter_rd5_6(md.pred[PRED_2Nx2N], cuGeom, SIZE_2Nx2N);
checkBestMode(md.pred[PRED_2Nx2N], cuGeom.depth);
```
还有几个条件判断，判断是否是B Slice（m_slice->m_sliceType == B_SLICE），判断是否开启了对半划分的模式（m_param->bEnableRectInter）和非对称划分模式（m_slice->m_sps->maxAMPDepth > depth），还一个BFrame的帧内。
仅关注P帧的标准划分模式（8种）
##earlySkip
```
--early-skip, --no-early-skip
Measure full CU size (2Nx2N) merge candidates first; if no residual is found the analysis is short circuited. Default disabled
```
简单的说就是在划分2Nx2N的块的时候，是否先对2Nx2N的块进行PU的划分，否则就先进行四叉树划分。
#md 
`md.pred[PRED_2Nx2N].cu.initSubCU(parentCTU, cuGeom, qp);`在initSubCU中可以看到大量的赋值
```
uint32_t depth = cuGeom.depth;
ModeDepth& md = m_modeDepth[depth];
ModeDepth m_modeDepth[NUM_CU_DEPTH];
#define NUM_CU_DEPTH  4
```
md是`m_modeDepth[depth]`的引用，查看`m_modeDepth`调用的地方，发现没有赋值的，除了有一个`m_modeDepth[0].fencYuv.copyFromPicYuv(*m_frame->m_fencPic, ctu.m_cuAddr, 0);`这个copy后面还会有类似的，后面说。然后再看md在下面用了很多次，其实都是在最上面的那三条的格式里面调用的，可以推测是通过md对`m_modeDepth[depth]`进行的操作，况且md还是个引用。
##ModeDepth的定义
```
    struct ModeDepth
    {
        Mode           pred[MAX_PRED_TYPES];
        Mode*          bestMode;
        Yuv            fencYuv;
        CUDataMemPool  cuMemPool;
    };
```
`fenc`个人理解是frame encode的缩写，后面还有`fref`，是frame reference的缩写。通过`md.pred[PRED_2Nx2N]`等可以看出是对PU划分模式的区分，再看下`MAX_PRED_TYPES`的定义，
```
    enum {
        PRED_MERGE,
        PRED_SKIP,
        PRED_INTRA,
        PRED_2Nx2N,
        PRED_BIDIR,
        PRED_Nx2N,
        PRED_2NxN,
        PRED_SPLIT,
        PRED_2NxnU,
        PRED_2NxnD,
        PRED_nLx2N,
        PRED_nRx2N,
        PRED_INTRA_NxN, /* 4x4 intra PU blocks for 8x8 CU */
        PRED_LOSSLESS,  /* lossless encode of best mode */
        MAX_PRED_TYPES
    };
```
看到14种都出来了，但是，实际上，多了几个我没在书上的见到的。没有去理会。

#if (mightSplit && (!md.bestMode || !md.bestMode->cu.isSkipped(0)))
其实这里我有点疑惑，根据标准，四叉树是要全部都走一遍的，但是看这里的判断条件，是可以提前终止的。等以后有机会再去看一下上一篇里面那个宏函数的定义吧。
四叉树划分的大体过程也很明确，`for (uint32_t subPartIdx = 0; subPartIdx < 4; subPartIdx++)`划分成四块。
注意`compressInterCU_rd5_6(parentCTU, childGeom, zOrder, nextQP);`递归调用了自身。

[0]: http://www.findspace.name/easycoding/1434 