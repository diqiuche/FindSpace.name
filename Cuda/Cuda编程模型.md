#主机与设备
运行在GPU上的CUDA并行计算函数成为**kernel**（内核函数），不是一个完整的程序，而是整个CUDA程序中一个可以被并行的步骤。一个完整的CUDA程序是有一系列的设备段kernel函数并行步骤和主机端的串行处理步骤共同组成的。这么步骤会按照程序中相应语句的顺序依次执行，满足**顺序一致性**
![][2]
一个kernel函数中有两个层次的并行：Grid中的block间的并行和thread间并行。
在设备端运行的线程之间并行执行，每个线程按照指令顺序串行执行一次kernel函数，每个线程有自己的blockID和threadID，它们是只读的，并且只能在kernel函数中使用。
#线程结构
kernel以block为单位执行，block间无法通信，也没有执行顺序，。CUDA引入gird是为了使这一编程模型可以适用不同线程块数量的GPU。
**在同一个block中的线程可以进行数据通信**：通过共享存储器（share memory）交换数据，通过栅栏同步保证线程间能够正确地共享数据（调用__syncthreads()函数）。
![][3]
#关键特性
线程按照两个层次进行组织，在较低层次通过共享存储器和栅栏同步实现通信。

#硬件映射
没有非常合适的图，暂且用这个。
![][1]

隶属同一SM的8个SP共用一套取指与发射单元，也共用一块共享存储器。
同一block中的线程需要共享数据，一个block必须被分配到一个SM中，但是一个SM中同一时刻可以有多个活动线程块在等待执行。这是为了隐藏延迟，更好的利用执行单元的资源。SM中的活动线程块数量不超过8个。
block会被分割为更小的线程束(warp)，warp中的线程只与threadID有关，而与block的维度和每一维的尺度没有关系。
warp中包含32条线程是因为每发射一条warp指令，SM中的8个SP会将这条指令执行4遍（why？）。
#执行模型
CUDA采用SIMT(Single Instruction，Multiple Thread单指令多线程)执行模型，是对SIMD(Single Instruction,Multiple Data单指令多数据)的一种改进。
在SIMT中，如果需要控制单个线程的行为，必须使用分支，会大大降低效率。应尽量避免分支，并尽量做到warp内不分支，否则将导致性能急剧下降。



#nvcc编译器

nvcc工作的基本流程：
首先通过CUDAfe分离源文件中的主机端和设备端代码，然后再调用不同的编译器分别编译。设备端代码由nvcc编译成ptx代码或者二进制代码；主机端则将以c文件形式输出，由其他编译器编译。

#CUDA存储器模型

GPU的多层存储器空间
![][0]
每一个线程拥有自己私有的存储器寄存器和局部存储器;每个线程块拥有一块共享存储器。grid中所有的线程都可以访问同一块全局存储器。
共享存储器是GPU片内的高速存储器。是一块可以被同一block中所有线程访问的可读写存储器。访问它的速度和访问寄存器的速度几乎一样快，是实现线程间通讯的延迟最小的方法。
每个SM的共享存储器的大小为16KByte，被组织为16个bank。

|存储器|位置|拥有缓存|访问权限|变量生存周期|特点|
|-|-|-|-|-|-|
|Register|GPU片内|N/A|Device 可读/写|与thread相同|极低延迟|
|Local memory|板载显存|无|Device 可读/写|与thread相同|访问速度慢|
|Shared memory|GPU片内|N/A|Device 可读/写|与thread相同|线程间通信最小延迟|
|Constant memory|板载显存|有|Device可读，host可读/写|可在程序中保持|存储需要频繁访问的参数|
|Texture memory|板载显存|有|Device可读，host可读/写|可在程序中保持|
|Global memory|板载显存|无|Device可读/写，host可读/写|可在程序中保持|高带宽，高延迟|
|Host memory|Host内存|无|host可读/写|可在程序中保持|
|Pinned memory|Host内存|无|host可读/写|可在程序中保持|可通过zero-copy功能映射到设备地址空间，从GPU直接访问|

[0]: /home/find/Dropbox/Findspace.name/Cuda/GPUStorage.gif "GPU的多层存储器空间"
[1]: /home/find/Dropbox/Findspace.name/Cuda/cudaHardware.jpg  "GPU计算单元简图"
[2]: /home/find/Dropbox/Findspace.name/Cuda/cudaCodingMode.jpg "CUDA编程模型"
[3]: /home/find/Dropbox/Findspace.name/Cuda/ThreadStructure.jpg "线程结构"
