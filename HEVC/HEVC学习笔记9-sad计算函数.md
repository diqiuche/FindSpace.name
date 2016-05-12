#Introduction
##[HEVC学习笔记目录](http://www.findspace.name/easycoding/1434 )
本文主要简单追一下sad的计算函数，是如何调用，以及做简单注释。
#TZSearch
tzsearch函数是帧间预测中一个很重要的函数，以后再重新梳理这个函数。
在tzsearch中，可以看到start search部分，根据不同的配置，进行调用不同的search函数，`xTZ8PointDiamondSearch`，`xTZ8PointSquareSearch`，`xTZSearchHelp`等，这些函数又都是最后调用了`xTZSearchHelp`函数，
> xTZSearchHelp是当中最为重要的子函数之一。它实现最基本的功能：根据输入的搜索点坐标，参考图像首地址，原始图像首地址，以及当前PU大小等相关信息，计算出SAD，并与之前保存的最佳值进行比较，更新到目前为止的最佳值相关参数，如uiBestSad，搜索点坐标，搜索步长等。其他的函数如xTZ8PointSearch等搜索函数，最终都是调用xTZSearchHelp进行误差匹配的。

#setDistParam 设置sad函数
在tzsearchhelp里，
```
m_pcRdCost->setDistParam( pcPatternKey, piRefSrch, rcStruct.iYStride,  m_cDistParam );//!< 该函数主要职能是设置计算SAD的函数指针，
```
然后在下面的
```
      Distortion uiTempSad = m_cDistParam.DistFunc( &m_cDistParam );

```
这里调用函数进行计算。这里用的是函数指针。

而在setDistParam里，主要是`rcDistParam.DistFunc = m_afpDistortFunc[DF_SAD12];`类似这样的语句，在根据不同PU的大小进行设置函数。
找到`DF_SAD12`的定义的地方，在TypeDef.h里，集合DFunc的定义。
```
enum DFunc
{
  DF_DEFAULT         = 0,
...

  DF_SAD             = 8,      ///< general size SAD
  DF_SAD4            = 9,      ///<   4xM SAD
  DF_SAD8            = 10,     ///<   8xM SAD
  DF_SAD16           = 11,     ///<  16xM SAD
  DF_SAD32           = 12,     ///<  32xM SAD
  DF_SAD64           = 13,     ///<  64xM SAD
  DF_SAD16N          = 14,     ///< 16NxM SAD
...
  DF_TOTAL_FUNCTIONS = 64
};
```
而`m_afpDistortFunc`则是定义在这里的：
```
//TComRdCost.h about line 111
/// RD cost computation class
class TComRdCost
{
private:
  // for distortion
  FpDistFunc              m_afpDistortFunc[DF_TOTAL_FUNCTIONS]; // [eDFunc]
```
对于这个数组的初始化则是在这里，进行了函数和函数指针数组的绑定：
```
//TComRdCost.cpp line 120
// Initalize Function Pointer by [eDFunc]
Void TComRdCost::init()
{
  m_afpDistortFunc[DF_DEFAULT] = NULL;                  // for DF_DEFAULT

。。。
  m_afpDistortFunc[DF_SAD    ] = TComRdCost::xGetSAD;
  m_afpDistortFunc[DF_SAD4   ] = TComRdCost::xGetSAD4;
  m_afpDistortFunc[DF_SAD8   ] = TComRdCost::xGetSAD8;
  m_afpDistortFunc[DF_SAD16  ] = TComRdCost::xGetSAD16;
。。。
```
#xGetSAD函数
xGetSAD和函数xGetSAD4等的区别就是一次计算一行的几个元素，比如8x4的PU，宽8高4,那么应该设置为SAD8函数，每次计算一行的8个元素。对于sad函数，大概的注释
```
Distortion TComRdCost::xGetSAD4( DistParam* pcDtParam )
{
  if ( pcDtParam->bApplyWeight )
  {
    return TComRdCostWeightPrediction::xGetSADw( pcDtParam );
  }
  const Pel* piOrg   = pcDtParam->pOrg;//原图像的首地址
  const Pel* piCur   = pcDtParam->pCur;//当前参考帧PU块的首地址
  Int  iRows   = pcDtParam->iRows;//不知道为什么用rows，从代码来看应该是宽度
  Int  iSubShift  = pcDtParam->iSubShift;
  Int  iSubStep   = ( 1 << iSubShift );//每次计算一行的几个元素
  Int  iStrideCur = pcDtParam->iStrideCur*iSubStep;//原图像的跨度
  Int  iStrideOrg = pcDtParam->iStrideOrg*iSubStep;//当前参考帧图像的跨度

  Distortion uiSum = 0;

  for( ; iRows != 0; iRows-=iSubStep )
  {
    uiSum += abs( piOrg[0] - piCur[0] );
    uiSum += abs( piOrg[1] - piCur[1] );
    uiSum += abs( piOrg[2] - piCur[2] );
    uiSum += abs( piOrg[3] - piCur[3] );
//都是在整个帧中的地址，所以需要加上跨度
    piOrg += iStrideOrg;
    piCur += iStrideCur;
  }

  uiSum <<= iSubShift;
  return ( uiSum >> DISTORTION_PRECISION_ADJUSTMENT(pcDtParam->bitDepth-8) );
}
```