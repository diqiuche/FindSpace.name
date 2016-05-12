#OPENMP
OpenMP是专门针对共享地址空间的平行计算机提供的**并行计算库**，支持OpenMp的编译器包括Sun Compiler，GNU Compiler和Intel Compiler等，现在只需要在编译的时候添加`-fopenmp`，就启用了对openmp的支持。
对于实验而言，本文的内容已经足够多了，如果想了解更多，请看最后的Reference里的内容。
更多内容查看[并行计算实验系列文章目录][4]
#Simple HelloWorld
```
#include <stdio.h>
int main() {
    #pragma omp parallel
    {
        int i;
        printf("Hello World\n");
        for(i=0;i<6;i++)
            printf("Iter:%d\n",i);
    }
    printf("GoodBye World\n");
}
```
编译运行
```
gcc  -fopenmp -o test test.c
./test
#虚拟机中双核的结果:
Hello World
Iter:0
Iter:1
Iter:2
Iter:3
Iter:4
Iter:5
Hello World
Iter:0
Iter:1
Iter:2
Iter:3
Iter:4
Iter:5
GoodBye World

```
当然可以指定线程数：
```
OMP_NUM_THREADS=10
export OMP_NUM_THREADS
./test
```
结果自然就是打印10遍了。
#基础语法
##创建并行区
下图是一个典型的OpenMP程序的示意图，我们可以看到它是由串行代码和并行代码交错组成的，并行代码的区域我们把它叫做“并行区”。主线程一旦进入并行区，就自动产生出多个线程，来并行的执行。
![][0]
怎样在我们的代码中使用OpenMP呢？很简单，拿我们常用的C/C++代码来说，只需要插入如下pragma，然后我们选择不同的construct就可以完成不同的功能。
```
#pragma omp construct [clause [clause]...]
```
创建一个并行区：增加一行代码#pragma omp parallel，然后用花括号把你需要放在并行区内的语句括起来，并行区就创建好了。并行区里每个线程都会去执行并行区中的代码。
```
pragma omp parallel
{
	block;
}
```
##for语句
一个新的openmp语句
```
#pragma omp for
```
使用这个语句，我们就可以把一个for循环的工作量（例如：1...N）分配给不同线程。这个语句后面必须紧跟一个for循环，他只能对循环的工作量进行划分、分配。
```
#pragma omp parallel
#pragma omp for
	for ( i=0;i<N;i++){
		work(i);
}
```
或者合并成一行
```
#pragma omp parallel for
	for ...
```
##数据环境（Data Environment）

OpenMP属于共享内存的编程模型。在我们的多线程代码中，大部分数据都是可以共享的。共享内存给我们程序中数据的共享带来了极大的便利。因此在默认情况下，OpenMP将全局变量、静态变量设置为共享属性。

但是，还是有些变量需要是每个线程私有的，也就是每个线程有这些变量的独立拷贝，这样每个线程在使用这些变量时不会相互影响。
我们可以通过如下方法来改变OpenMP的变量默认属性，你可以把它设置为共享（shared）或无。也可以单独改变某几个变量的属性，把他们设置为shared或private。
![][1]

#Reference:
[【系列讲座】OpenMP 入门][2]
[简单的OpenMP编程指南][3]
[0]: http://p.blog.csdn.net/images/p_blog_csdn_net/intel_jeff/390473/o_7.JPG "并行区域"
[1]: http://p.blog.csdn.net/images/p_blog_csdn_net/intel_jeff/390473/o_17.JPG "数据环境"
[2]: http://bbs.csdn.net/topics/230002131
[3]: http://blog.csdn.net/drzhouweiming/article/details/4093624
[4]:  http://www.findspace.name/easycoding/1485