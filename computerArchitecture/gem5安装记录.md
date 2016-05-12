# Introduction
>GEM5是一款模块化的离散事件驱动全系统模拟器，它结合了M5和GEMS中最优秀的部分，是一款高度可配置、集成多种ISA和多种CPU模型的体系结构模拟器

本文记录了我安装gem5安装的过程。
我的所有压缩包都放在了`~/gem5/`下，最后安装出来的路径是`~/gem5/gem5-stable`，你可以根据自己的情况执行下面的命令
所有的压缩包都在这里：
[gem5的百度云分享](http://pan.baidu.com/s/1dFe4whV)
里面还有个很好的入门教程gem5_hipeac.pdf
# 安装步骤
```bash
sudo apt-get update
# 安装依赖
sudo apt-get install scons  python-dev libprotobuf-dev libgoogle-perftools-dev texinfo protobuf-compiler libprotoc-dev autoconf automake libtool
# gcc和g++只在4.7或者4.8编译通过，4.9好像有问题。
cd /usr/bin
sudo rm gcc
sudo rm g++
sudo ln -s gcc-4.8 gcc
sudo ln -s g++-4.8 g++
# 解压各个包
tar -xzvf m4-1.4.17.tar.gz
cd m4-1.4.17
./configure
make -j 8
sudo make install

tar -xzvf protobuf-2.5.0.tar.gz
cd protobuf-2.5.0
chmod 777 autogen.sh
./autogen.sh
chmod 777 configure
./configure
make
make check
sudo make install
sudo ldconfig

tar -xzvf swig-2.0.7.tar.gz
cd swig-2.0.7
./configure --without-pcre 
make -j 8
sudo make install

tar -xzvf zlib-1.2.8.tar.gz
cd zlib-1.2.8
./configure 
make -j 8
sudo make install
# 解压gem5
tar -vxjf gem5-stable.tar.bz2
cd gem5-stable
scons build/X86/gem5.opt
mkdir -p fs-image

tar -vxjf x86-system.tar.bz2
cp -r x86-system gem5-stable/fs-image
tar -vxjf m5_system_2.0b3.tar.bz2
# 这一步时间比较长
sudo cp -r m5_system_2.0b3/disks/* gem5-stable/fs-image/x86-system/disks/
# 自己选择性删除吧
sudo rm -rf protobuf-2.5.0 m4-1.4.17 scons-2.3.0 swig-2.0.7 protobuf zlib-1.2.8 x86-system m5_system_2.0b3 x86-system.tar.bz2 m5_system_2.0b3.tar.bz2 scons-2.3.0.tar.gz zlib-1.2.8.tar.gz swig-2.0.7.tar.gz m4-1.4.17.tar.gz protobuf-2.5.0.tar.gz gem5-stable.tar.bz2
# 添加环境变量
echo "export M5_PATH=$M5_PATH:/home/find/gem5/gem5-stable/fs-image/x86-system">>~/.bashrc
source ~/.bashrc
```
