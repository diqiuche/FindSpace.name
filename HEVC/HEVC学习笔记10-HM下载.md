#Introduction
##[HEVC学习笔记目录](http://www.findspace.name/easycoding/1434 )
# HEVC官网

https://hevc.hhi.fraunhofer.de/
其中里面的Documents一节是文档数据，
HEVC reference software就是HM的相关了。
HEVC reference software documentation里 [HM software manual][0]是HM手册

# 代码源

官方其中一个源是

https://hevc.hhi.fraunhofer.de/svn/svn_HEVCSoftware/

svn管理，branches是分支，里面主要是稳定版本，tags则就倾向于稳定版之前的一些测试分支。
我一般选择从tags里找特定的版本，或者从branches里下HM-dev
##下载
debian系列安装svn：
```
sudo apt-get install subversion
```
下载hm-dev
```
svn checkout https://hevc.hhi.fraunhofer.de/svn/svn_HEVCSoftware/branches/HM-dev/
```
##代码结构
```
.
├── build //根据不同平台或者ide做的makefile等
├── cfg //标准配置文件
├── compat
├── COPYING
├── doc //文档！很重要
├── HM.xcodeproj
├── README
└── source //代码
```
在linux下手动编译，进入`/build/linux/`,然后运行`make`进行编译即可。注意看编译是否有报错。编译完成会生成`/bin`文件夹，里面有很多个可执行文件。带`d`的是debug模式，可以进行debug输出，但是运行速度会慢一点。
encoder是`TAppEncoderStatic`,decoder是`TAppDecoderStatic`
#arm补丁
[HM移植到arm平台]（http://www.findspace.name/res/1206)

[0]: https://hevc.hhi.fraunhofer.de/svn/svn_HEVCSoftware/trunk/doc/software-manual.pdf