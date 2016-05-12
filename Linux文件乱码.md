#Introduction
本文主要介绍linux下zip解压出来乱码以及普通文本类文件的乱码问题。
主要是系统之间编码方式不同造成的，国内windows文件名编码方式一般是gbk，而Linux默认是utf-8，这样就会导致在windows的文件在Linux下面显示乱码。
#1. zip文件解压出来乱码
##1.1 unzip支持-O选项
如果系统自带的unzip支持`-O`选项，则直接使用以下命令即可，这样最方便简单，然而debian stable版本自带的就不支持。
```linux
unzip -O GBK you_zip_file.zip
```
##1.2 python脚本
我仅对网上流传的代码修改了一点点。
```
#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import sys
import zipfile

docs = """usage:
    unzippy zip_file.zip [dest_path]
    It needs at least one arguments.
    zip_file.zip:
        The file needs to be unzipped.
    dest_path:
        The path you want to extract files.It's default value is current path.
    """
if len(sys.argv) == 3:
    zip_file = sys.argv[1]
    dest_path = os.path.join(os.path.abspath('.'), sys.argv[2])
elif len(sys.argv) == 2:
    zip_file = sys.argv[1]
    dest_path = os.path.abspath('.')
else:
    print(docs)
    sys.exit()

print "Processing File " + zip_file
file=zipfile.ZipFile(zip_file,"r");
for name in file.namelist():
    utf8name=name.decode('gbk')
    print "Extracting " + utf8name
    pathname = os.path.join(dest_path,os.path.dirname(utf8name))
    if not os.path.exists(pathname) and pathname!= "":
        os.makedirs(pathname)
    data = file.read(name)
    if not os.path.exists(utf8name):
        fo = open(utf8name, "w")
        fo.write(data)
        fo.close
file.close()
print("unzip to %s successfully." % dest_path)
```
保存为unzippy ，然后移动到`/usr/bin`，并添加可执行权限即可在任意地方使用
```
sudo mv ./unzippy /usr/bin/
cd /usr/bin/
sudo chmod a+x ./unzippy
```
以后就可以直接用`unzippy 你的zip文件 要解压出来的路径`，其中解压目标路径可选，默认是当前文件夹下。
###UPDATE April 14, 2016 2:32 PM
这个方法并不完美，经常遇到问题。

##1.3 7z解压
先设置bash的lang，用7z解压出文件，然后再用convmv转换文件格式是这个方法的主要内容。
```
sudo apt-get install p7zip-full convmv
LANG=C
7z x zip_file.zip
convmv -f gbk -t utf8 --notest -r your_unzipped_file_floder/
或者先cd到解压好的地方
convmv -f gbk -t utf8 --notest  ./*
```
我用这个方法并没有成功
##1.4 给unzip打补丁
根据 https://github.com/ikohara/dpkg-unzip-iconv 上的安装步骤，给unzip打补丁，然后就可以用`-O`参数了
##1.5 unar方法
这个最简单省力，默认debian已经安装了额unar，这个工具会自动检测文件的编码，也可以通过`-e`来指定：
```
unar file.zip
```
即可解压出中文文件。

# 2. 文件内容乱码
##2.1 iconv工具
```
iconv -f gbk -t utf-8 file1 -o file2  # gbk编码转换为utf-8  
```
命令很简单，可以man出手册或者`--help`看一下。
##2.2 enca工具
```
# -L指明文件语言，一般可以省略
enca -L zh_CN file &nbsp;# 检查文件的编码
enca -L zh_CN -x UTF-8 file &nbsp;# 将文件编码转换为"UTF-8"编码
enca -L zh_CN -x UTF-8 file1 file2&nbsp; # 如果不想覆盖原文件可以这样
```
#Reference
http://marshal-r.iteye.com/blog/2161903
https://linuxtoy.org/archives/wrong-handling-of-chinese-coded-filename-in-fileroller-unzip.html
