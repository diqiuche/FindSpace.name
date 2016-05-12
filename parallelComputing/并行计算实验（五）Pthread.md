#Pre
Pthreads 是 IEEE（电子和电气工程师协会）委员会开发的一组线程接口，负责指定便携式操作系统接口（POSIX）。Pthreads 中的 P 表示 POSIX，实际上，Pthreads 有时候也代表 POSIX 线程。基本上，POSIX 委员会定义了一系列基本功能和数据结构，它希望能够被大量厂商采用，因此线程代码能够轻松地在操作系统上移植。委员会的梦想由 UNIX 厂商实现了，他们都大规模实施 Pthreads。（最著名的例外就是 Sun，它继续采用 Solaris* 线程作为其主要线程 API。）由于 Linux 的采用和移植到 Windows 平台，Pthreads 的可能性一直被进一步扩展。

Pthreads 指定 API 来处理线程要求的大部分行为。这些行为包括创建和终止线程、等待线程完成、以及管理线程之间的交互。后面的目录中存在各种锁定机制，能够阻止两个线程同时尝试修改相同的数据值：互斥体、条件变量和信号量。（从技术上讲，信号量不是 Pthreads 的一部分，但是它们从概念上更接近于与线程合作，而且可用于 Pthreads 能够运行的所有系统上。）

为了使用 Pthreads，开发人员必须为这一 API 专门编写代码。这就意味着它们必须包括标头文件、宣布 Pthreads 数据结构、并调用 Pthreads 指定的函数。基本上，此流程与使用其它库没有不同。和 UNIX 以及 Linux 上的其它库一样，Pthreads 库只是简单地链接到应用代码（通过 -lpthread 参数）。

虽然 Pthreads 库相当复杂（尽管不像一些其它固有的 API 设置那样广泛）而且显然具有便携性，但是全部固有线程 API 常用的严格限制条件也使它非常艰难：它需要大量线程专用代码。换言之，为 Pthreads 进行编码就要在线程模型中建立代码库，这是不可回避的。此外，一些决策（如需要使用的线程数据）也将成为程序中的硬编码。作为这些限制的交换条件，Pthreads 能提供对于线程操作的广泛控制--这是一个固有的低级 API，通常要求多个步骤来执行简单的线程任务。例如，使用线程循环来通过大型数据块需要宣布线程结构、单独创建线程、计算通向每个线程的循环并分配到线程、最终处理线程终止--所有这些必须由开发人员进行编码。如果循环不仅仅是简单的叠代，则线程指定代码的数量将显著增加。为了公平起见，对于如此多代码的需求存在于所有本地线程 API 中，而不仅仅是 Pthreads。

更多内容查看[并行计算实验系列文章目录][2]
#函数
头文件`#include<pthread.h>`
##多线程创建`pthread_create`
```
int pthread_create(pthread_t *thread, pthread_attr_t *attr, void *(*start_routine)(void *), void *arg);
int pthread_create(/*指向线程标识符的指针*/,/*线程属性参数，通常为NULL*/,/*返回值是void类型指针的函数 */,/*运行函数的参数*/);
5
//成功返回0，失败返回错误编号。


```
pthread_t表示了这个线程的对象；pthread_attr_t表示了应用在这个线程上的属性，一般设为NULL；接下来的那个参数就是要多线程执行的函数，这个函数一般定义为void* func_name(void* para)；最后一个是传入前面这个函数的参数，
注意：被创建的线程可能在pthread_create执行完毕之前就开始执行。
编译注意：编译时注意加上-lpthread参数，以调用静态链接库。因为pthread并非Linux系统的默认库。

##等待线程结束pthread_join
```
 int pthread_join(pthread_t thread, void **retval);
pthread_join(/*等待的线程标识符*/,/*传入指针，用于存储被等待线程的返回值，通常为NULL*/);

```
让主线程阻塞在这个地方等待子线程结束。
##for循环创建线程
在这个实验中，最可能出现的错误就是for循环创建线程，却直接利用了循环变量作为传入参数，最后测试在线程里打印传入的参数，并不是变量的0,1,2,3,，，，循环值。
原因

>使用for循环启动多线程，而传入的参数在for循环内使用的是同一个参数，而同一个参数会存在同一处内存中，我们无法确定各个线程启动的快慢，这样在线程启动时，也许参数还没更新，或者已经更新过了几次才启动线程，这就导致这个问题。而解决方法就是使用malloc分配参数，这样for中的每次循环都会生成一个新的参数，即内存地址不相同，就可以解决这个问题了。

```
int *m=(int*)malloc(sizeof(int));
*m=i;
if(pthread_create(&threadIds[i],NULL,work,m)){
```
##传入函数
子线程要执行的函数记得要这样写：
```
void *functionName(void *arg){
	int k=*(int*) arg;
...
}
```
如果我要传入int数，比如传入之前的m，则在create的时候，输入m，但在函数里要写成如上的形式，`void *arg`这是pthread的规定，不信可以改掉，编译看看编译warning。然后通过强制类型转换及取值获得int值即可。
#Reference

[高性能计算的线程模型：Pthreads 还是 OpenMP？][0]
[初探linux pthread多线程编程][1]
[pthread遇到的问题][3]
[0]: https://software.intel.com/zh-cn/articles/threading-models-for-high-performance-computing-pthreads-or-openmp 
[2]: http://www.findspace.name/easycoding/1485
[1]: http://www.kryptosx.info/archives/737.html
[3]: http://blog.hackcv.com/index.php/archives/78/