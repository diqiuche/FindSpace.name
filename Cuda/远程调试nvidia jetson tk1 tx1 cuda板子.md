# Introduction
Nvidia针对异构本身有一套开发平台，叫[NVIDIA® Nsight™][0]，有很强的debug和程序分析工具。
有[Nsight Visual Studio](https://developer.nvidia.com/nvidia-nsight-visual-studio-edition)和[Nsight Eclipse](https://developer.nvidia.com/nsight-eclipse-edition)两个版本。
在这里的平台：
Host：

+ x86_64 
+ Ubuntu 14.04

Tegra tx 1板子：

```bash
Ubuntu 14.04.4 LTS
Linux tegra-ubuntu 3.10.67-gcdddc52 #1 SMP PREEMPT Mon Nov 9 13:16:26 PST 2015 aarch64 aarch64 aarch64 GNU/Linux
```

# 依赖
安装cuda driver toolkit等的步骤都忽略
还需要安装git 然后简单设置下username
```
git --config global user.name "yourname"
git --config global user.email "email"
```
# 新建项目
## 导入cuda sample
以新建cuda samples项目为例
![导入cuda sample](http://www.findspace.name/wp-content/uploads/2016/04/nsight_open_project.png)
接下来会选择cuda sample，随便选一个，然后下一步，如果有nvidia显卡，应该会自动检测出来，如果没有也不用管，直接next即可。注意最好勾选5.0,去掉勾选的1.0,不然编译时可能出现`nvcc fatal : Unsupported gpu architecture 'compute_10'`的错误
![](http://www.findspace.name/wp-content/uploads/2016/04/nsight_setfloder.png)
## 设置目标
单击manage则可以添加remote target
![remote target](http://www.findspace.name/wp-content/uploads/2016/04/nsight_ssh.png)
设置toolkit
![](http://www.findspace.name/wp-content/uploads/2016/04/nsight_set_remote_toolkit.png)
按照如图设置
设置好后记得要选择。会变成类似
![](https://www.ecofinancialtechnology.com/wp-content/uploads/2015/02/CreateProject5.png)
如果本机没有cuda 显卡，可以直接叉掉`local System`
# Build
如果按照以上步骤进行，在Project-->Build-->BuildConfigutrations-->set active 里可以看到远程target的debug和release两个build模式。（只有一个也没关系。）
## 如果项目已有makefile
且makefile不在根目录下，则项目可以通过File-->New-->Makefile Project with Exsiting Code 导入项目，然后在项目上右键-->properties-->Build 点击Manage Configurations 添加自己的config，接着在新建的config里，设置Build Location的Builde directory 为makefile所在的路径。build的时候记得用这个配置文件。
简单的编译结果
```bash
18:30:50 **** Incremental Build of configuration Debug for project test2 ****
# 可以从路径上看出是否是板子
make all -C /home/ubuntu/cuda_work/test2/Debug 
make: Entering directory `/home/ubuntu/cuda_work/test2/Debug'
Building file: ../src/test2.cu
Invoking: NVCC Compiler
/usr/local/cuda-7.0/bin/nvcc -G -g -O0 -gencode arch=compute_50,code=sm_50  -odir "src" -M -o "src/test2.d" "../src/test2.cu"
/usr/local/cuda-7.0/bin/nvcc --compile -G -O0 -g -gencode arch=compute_50,code=compute_50 -gencode arch=compute_50,code=sm_50  -x cu -o  "src/test2.o" "../src/test2.cu"
Finished building: ../src/test2.cu
 
Building target: test2
Invoking: NVCC Linker
/usr/local/cuda-7.0/bin/nvcc --cudart static -link -o  "test2"  ./src/test2.o   
Finished building target: test2
 
make: Leaving directory `/home/ubuntu/cuda_work/test2/Debug'
> Shell Completed (exit code = 0)

18:30:57 Build Finished (took 7s.412ms)
```
编译完成后，项目的Binaries部分里即出现编译好的目标文件
# Debug
编译完成后，在板子上之前设置的路径下，会出现编译好的文件。
在run之前，可能需要通过Run-->Debug Remore Application来设置一些参数。
然后就可以按照正常eclipse debug的方式进行debug了

# Reference

[How to use the Jetson TK1 as a remote development environment for CUDA](https://www.ecofinancialtechnology.com/2015/02/how-to-use-the-jetson-tk1-as-a-remote-development-environment-for-cuda/)

[0]: http://www.nvidia.com/object/nsight.html