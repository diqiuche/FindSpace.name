# Introduction
本文将简单介绍GPU中的寄存器。
#寄存器
寄存器是GPU片上高速缓存， 执行单元可以以极低的延迟访问寄存器。寄存器的基本单元式寄存器文件，每个寄存器文件大小为32bit。局部存储器对于每个线程，局部存储器也是私有的。如果寄存器被消耗完。数据将被存储在局部存储器中。如果每个线程使用了过多的寄存器，或声明了大型结构体或数据，或者编译器无法确定数据的大小，线程的私有数据就有可能被分配到local memory中，一个线程的输入和中间变量将被保存在寄存器或者是局部存储器中。局部存储器中的数据被保存在显存中，而不是片上的寄存器或者缓存中，因此对local memory的访问速度很慢。
#Registers
寄存器是GPU最快的memory，kernel中没有什么特殊声明的自动变量都是放在寄存器中的。当数组的索引是constant类型且在编译期能被确定的话，就是内置类型，数组也是放在寄存器中。

寄存器变量是每个线程私有的，一旦thread执行结束，寄存器变量就会失效。寄存器是稀有资源。在Fermi上，每个thread限制最多拥有63个register，Kepler则是255个。让自己的kernel使用较少的register就能够允许更多的block驻留在SM中，也就增加了Occupancy，提升了性能。

使用nvcc的`-Xptxas -v,-abi=no`（这里Xptxas表示这个是要传给ptx的参数，不是nvcc的，v是verbose，abi忘了，好像是application by interface）选项可以查看每个thread使用的寄存器数量，shared memory和constant memory的大小。如果kernel使用的register超过硬件限制，这部分会使用local memory来代替register，即所谓的register spilling，我们应该尽量避免这种情况。编译器有相应策略来最小化register的使用并且避免register spilling。我们也可以在代码中显式的加上额外的信息来帮助编译器做优化：
# launch bounds
[官方手册中关于launch bounds的部分 B.20部分 CUDA 7.5](http://docs.nvidia.com/cuda/cuda-c-programming-guide/index.html#launch-bounds)

kernel用的寄存器数量越少，一个核上驻留的线程和线程块越多，则性能就自然提升了。
因此，编译器通过启发式算法来减少寄存器的使用。而应用程序当然也可以通过添加辅助信息来帮助编译器优化这个启发式的算法。
```
__global__ void
__launch_bounds__(maxThreadsPerBlock, minBlocksPerMultiprocessor)
kernel(...) {
    // your kernel body
}
```
maxThreadsPerBlock指明每个block可以包含的最大thread数目。minBlocksPerMultiprocessor是可选的参数，指明必要的最少的block数目。
通过添加`__launch_bounds__`修饰符来修饰global函数，可以达到限定寄存器数量的效果。
我们也可以使用-maxrregcount=32来指定kernel使用的register最大数目。如果使用了__launch_bounds__，则这里指定的32将失效。
如果指定了launch bounds，编译器首先会得出一个寄存器数量上限值L，内核将用L个寄存器来保证maxThreadsPerBlock个线程的minBlocksPerMultiprocessor 个块可以运行在处理器上。

+ 如果初始的寄存器设置比L大，则编译器会将它减少至小于等于L，通常采取的方法是使用更多的local memory和（或者）更多的指令。
+ 如果初始设置比L小，
  + 如果maxThreadsPerBlock指定了，而minBlocksPerMultiprocessor没有指定，编译器将使用maxThreadsPerBlock来决定寄存器的使用阀值，当后面块没有指定launch bounds时，利用同样的启发算法 来使用寄存器。
  + 如果两个值都没有指定，编译器将尽可能的使用寄存器到L，以便减少指令数量，优化单线程指令延时。

如果每个block的线程数超过了maxThreadsPerBlock，将运行出错。

# Reference
[CUDA内存简介](http://blog.csdn.net/mysniper11/article/details/8270149)
[CUDA ---- Memory Model](http://www.cnblogs.com/1024incn/p/4564726.html)
[cuda SM register limit](http://stackoverflow.com/questions/3874839/cuda-sm-register-limit)