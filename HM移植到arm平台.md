[TOC]
#Pre
这是邮件与官方人员交流后他们做的一个临时的补丁，仅针对4445版本，可以在hm目录下，使用
```
svn up -r 4445
```
回退到4445版本，当前，前提是你是通过svn下载的hm。
#Environment
平台：Ubuntu arm
>开发板的官网链接
https://developer.nvidia.com/jetson-tk1

#Patch File
##[补丁文件下载地址][1]
#打补丁
把下载的文件重命名，删掉最后的`.txt`后缀成为：`HMForArmPatch.patch`
然后在HM的根目录下，例如HM-dev/或者HM-版本号/(`ubuntu@tegra-ubuntu:~/HM-16.5$`)：
```
patch -p0 <HMForArmPatch.patch
```


---
#Pre
I'm learning something about HM,and our project requires arm platform.However,change makefile or sourcefiles(we can't change all files)
I'm particularly grateful to Karl for his's patching,and Karsten Suehring who gives a lot help.
It needs the HM's version is 4445,and you can 
```
svn up -r 4445
```
to retreat the HM to verison 4445.Good Luck.
#Environment
Platform:Ubuntu arm
>hardware
https://developer.nvidia.com/jetson-tk1

#PatchFile
##[Download patch][1]
#Patch
```
mv download/HMForArmPatch.patch.txt HM-dev/HMForArmPatch.patch
cd HM-dev
patch -p0 <HMForArmPatch.patch
```
[1]: http://www.findspace.name/wp-content/uploads/2015/06/HMForArmPatch.patch_.txt "Patch File"