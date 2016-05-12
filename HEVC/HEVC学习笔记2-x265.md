#Pre
HEVC仅仅是一个标准，而落实到代码上，官方给出了HM，截止目前（2015.8.27）为止，版本已经到了16.6。
[HEVC官网](https://hevc.hhi.fraunhofer.de)
[HM移植到Arm平台][0]

而x265则是民间的实现，而且支持多线程，速度比HM快很多，但是也忽略了HEVC的一些标准细节。
官网：[x265.org](http://x265.org/)
docs:[x265 docs](http://x265.readthedocs.org/en/default/)

这里有篇国外的评测：[Comparison of open-source HEVC encoders][1]

后面的文章都是在arm开发板（4-Plus-1 quad-core ARM Cortex A15 CPU）ubuntu14.04(armv7l 64位)上做的工作，代码的修改则在我的笔记本Ubuntu14.04（intel Core i5 x86_64）.
#1. x265项目结构
```
.
├── build
│   ├── linux//linux下的makefile存放的文件夹
│   │   ├── x265//编译出来的主程序
│   │   └── ...
│   ├── msys
│   ├── README.txt
│   ├── vc10-x86//win下的
│   ├── ...
│   ├── vc9-x86_64
│   └── xcode
├── COPYING
├── decoder
├── doc
├── readme.rst
├── source//源代码
│   ├── cmake
│   ├── CMakeLists.txt
│   ├── common
│   │   ├── ...
│   │   ├── common.cpp
│   │   ├── common.h
│   │   ├── cudata.cpp
│   │   ├── cudata.h
│   │   ├── frame.cpp
│   │   ├── framedata.cpp
│   │   ├── framedata.h
│   │   ├── frame.h
│   │   ├── param.cpp
│   │   ├── param.h
│   │   ├── pixel.cpp
│   │   ├── predict.cpp
│   │   ├── predict.h
│   │   ├── primitives.cpp
│   │   ├── primitives.h
│   │   ├── slice.cpp
│   │   ├── slice.h
│   │   ├── yuv.cpp
│   │   └── yuv.h
│   ├── compat
│   ├── encoder//编码模块
│   │   ├── analysis.cu
│   │   ├── ...
│   │   ├── analysis.h
│   │   ├── encoder.cpp
│   │   ├── encoder.h
│   │   ├── frameencoder.cpp
│   │   ├── frameencoder.h
│   │   ├── motion.cpp
│   │   ├── motion.h
│   │   ├── search.cpp
│   │   └── search.h
│   ├── filters
│   ├── input
│   ├── LICENSE
│   ├── output
│   ├── profile
│   ├── README.md
│   ├── test
│   ├── x265cli.h
│   ├── x265_config.h.in
│   ├── x265.cpp
│   ├── x265.def.in
│   ├── x265.h
│   ├── x265.pc.in
│   └── x265.rc.in
└── test
```
源代码都是source文件夹下面，编译则是`build/linux/` 在这下面make即可，会生成可执行的二进制文件。编译的速度很快。
#2. arm移植
请对照这个[补丁][2]的内容修改对应的文件
#3. tips
导入eclipse里，可能会有一些错误提示，不必理会。



[0]: http://www.findspace.name/res/1206 
[1]: https://damienschroeder.wordpress.com/2014/10/10/comparison-of-open-source-hevc-encoders/ 
[2]: http://www.findspace.name/adds/arm.patch 