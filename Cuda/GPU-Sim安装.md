[TOC]
#Pre
GPGPU-sim 是一个模拟NVIDIA GPU的开发工具。
[官方主页][0]
#我的安装环境
Ubuntu 14.04 x64
无NVIDIA显卡
Lenovo Y471A
#CUDA Toolkit
##安装
由于gpgpu-sim仅最高支持cudatoolkit4.0，所以不能下载太高版本的cuda，否则安装gpgpu时会提示”not tested”。

[cuda4.0toolkit下载地址][1]

	经测试4.2不可以。

	注意要下载两个文件，toolkit和sdk
		GPU Computing SDK - complete package including all code samples
		CUDA Toolkit for Ubuntu Linux 10.10

`chmod a+x downloadfile`给下载的文件加权限，然后`sudo ./downloadfile`执行安装，（如果不使用sudo权限系统会给出提示无法创建/usr/local/cuda的目录）
注意安装过程是否有错误出现，有的话自行google。
##设置环境变量
在安装完成后，terminal最后会显示设置环境变量的文字。
这里给出我的设置
我修改的是`~/.bashrc`，64位和32位略有不同，注意看之前终端的提示文字：
```
export CUDAHOME=/usr/local/cuda
export CUDA_INSTALL_PATH=/usr/local/cuda
export PATH=$PATH:$CUDA_INSTALL_PATH/bin
export LD_LIBRARY_PATH=$CUDA_INSTALL_PATH/lib64:$LD_LIBRARY_PATH:$CUDA_INSTALL_PATH/lib
```
#GPU Computing SDK
##安装
需要使用gcc-4.4和g++-4.4版本，太高的不行。所以先
`sudo apt-get install gcc-4.4 g++-4.4`
然后还要把默认的gcc和g++的链接改掉。
```
#备份原来的链接
sudo cp /usr/bin/gcc /usr/bin/gcc_back
sudo cp /usr/bin/g++ /usr/bin/g++_back
#使用新的链接
sudo ln -s gcc-4.4 gcc
sudo ln -s g++-4.4 g++
```
##设置环境变量
```
export NVIDIA_COMPUTE_SDK_LOCATION=/home/find/NVIDIA_GPU_Computing_SDK
```
#处理GPGPU-Sim的依赖
GPGPU-Sim是挂在github上的,依赖文件可以在github的项目README里看到。
```
//GPGPU-Sim dependencies
sudo apt-get install build-essential xutils-dev bison zlib1g-dev flex libglu1-mesa-dev
sudo apt-get install doxygen graphviz //GPGPU-Sim documentation dependencies
//AerialVision dependencies
sudo apt-get install python-pmw python-ply python-numpy libpng12-dev python-matplotlib 
//CUDA SDK dependencies （这条指令在实际输入的过程中会有提示无法找到libglut3-dev( unable to locate package libglut3-dev，应该将libglut3-dev改为freeglut3-dev)
sudo apt-get install libxi-dev libxmu-dev freeglut3-dev(libglut3-dev) 
```
#设置环境
进入到下载gpgpu-sim的文件夹下，
```
//对于cuda/OpenCL的程序，这个指令更改LD_LIBRARY_PATH，使用gpgpu-sim
source setup_environment
make
```
若一切正常则可以成功的编译gpgpu-sim
#benchmark测试
##编译
benchmark也在github上，是一个单独的项目。
下载后进入文件夹
`make -f Makefile.ispass-2009`
如果有一些benchmark无法make成功，则将其**注释**掉，在Makefile.ispass-2009文件里注释。
确认在`ispass2009-benchmarks/bin/release`文件夹下有生成的可执行文件。
我的注释了AES、DG、WP三个，但是剩下的能编译成功，能运行的却只有CP。
##配置文件
通过`./setup_config.sh`来查看有哪些配置文件可以使用，然后选择一个你要用的。
`./setup_config.sh TeslaC2050`
如果以后你想更换一下配置文件，首先应该输入
`./setup_config.sh --cleanup`
然后再次输入
`./setup_config.sh <config_name>`
##运行
cd到你想要运行的benchmark下，
`sh README.GPGPU-Sim`
运行该工程。
注意查看终端中有没有提示error，一般一个项目会运行几分钟，不会立即停止。
##Debug
如果你想要debugging simulator，应该使用debug模式
```
cd $GPGPUSIM_ROOT
source setup_environment debug
make clean
make
cd MUM
gdb –args 'cat README.GPGPU-Sim'
```
![][2]
#Reference
http://linux-article-collections.blogspot.com/2015/01/gpgpu-sim-installation.html
http://cs922.blogspot.com/2014/01/gpgpu-sim-is-tool-developed-for.html
注意查看benchmark下的README.ISPASS-2009,里面包括了很多东西。

[0]: http://www.gpgpu-sim.org/ 
[1]:  https://developer.nvidia.com/cuda-toolkit-40
[2]: http://www.findspace.name/wp-content/uploads/2015/06/gpgpu-sim_benchmark.png
