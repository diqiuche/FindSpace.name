#Introduction
##[HEVC学习笔记目录](http://www.findspace.name/easycoding/1434 )
本文主要梳理在编码时，CTU的划分，如果获取CTU的内存地址，如果获取任意像素点所属的ctu及地址。
本文HM的参考版本：
r4445 大版本号为16.5
由于[HM移植到arm平台补丁](http://www.findspace.name/res/1206)的原因，我的代码一直保持在该版本。
# CTU的划分
默认配置下CTU=64x64,而有些视频比如1920×1080并非是64的整数倍，所以实际上CTU的划分要向上取整
而在代码中体现在
```cpp
// TEncGOP.cpp line 994
Void TEncGOP::compressGOP( Int iPOCLast, Int iNumPicRcvd, TComList<TComPic*>& rcListPic,
                           TComList<TComPicYuv*>& rcListPicYuvRecOut, std::list<AccessUnit>& accessUnitsInGOP,
                           Bool isField, Bool isTff, const InputColourSpaceConversion snr_conversion, const Bool printFrameMSE )
```

