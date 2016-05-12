[TOC]

#Pre 
之前的博客[CUDA锁页内存和零复制][0]只是简单介绍了下零复制内存，并没有详细说明，这里参考《CUDA并行程序设计-GPU编程指南》这本书再详细说明下。
#零复制(Zero Copy)(零拷贝内存)
零复制是一种特殊形式的内存映射，它允许你将主机内存直接映射到GPU内存空间上。因此，当你对GPU上的内存解引用时，如果它是基于GPU的，那么你就获得了全局内存的高速带宽（180GB/s）。如果GPU代码读取一个主机映射变量，它会提交一个PCI-E读取事务，很长时间之后，主机会通过PCI-E总线返回数据。
如果程序是计算密集型，那么零复制可能是一项非常有用的技术。它节省了设备显示传输的时间。事实上，是将计算和数据传输操作重叠了，而且无需执行显式的内存管理。
实际上，使用零复制内存，将传输和内核操作分成更小的块，然后以流水线的方式执行它们。
然而，采用零复制内存的传输实际上是相当小的。PCI-E总线在两个方向上的带宽的总是相同的。由于基于PCI-E的内存读取存在高延迟，因此实际上大多数读操作应该在所有写操作之前被放入读队列。我们可能获得比显式内存复制版本节省大量执行时间的效果。

在论坛上的问答：
Q:我在程序中使用了零拷贝内存，程序反而变得更慢了，看书上说
“当输入内存和输出内存都只能使用一次时，那么在独立GPU上使用零拷贝内存将带来性能提升”。
请问 “当输入内存和输出内存都只能使用一次时” 具体是什么意思？我下面的做法有什么问题？
程序情况是这样的：
在主机申请了3个 零拷贝内存 buf1,buf2,buf3，然后通过cudaHostGetDevicePointer() 获得这块内存在GPU上的有效指针dev1,dev2,dev3。其中 buf1,buf2,buf3 是从影像中读取的数据，然后在核函数中对dev1,dev2,dev3进行处理，值依然存放在dev1,dev2,dev3中，然后调用GDAL将buf1,buf2,buf3 写出。
A:
LZ您好：
zero copy和普通的cudaMemcpy一样也是要走pci-e总线的，只不过cudaMemcpy是一次性全部copy过去，而zero copy是用的时候自动在后台通过pci-e总线传输。
zero copy这样的机制多少可以利用计算来掩盖一些copy的时间，而如果使用cudaMemcpy要实现类似的计算和传输互相掩盖的话，需要使用异步版本的cudaMemcpy函数，并使用页锁定内存以及多个stream。
zero copy的读入信息是不在device端缓冲的，也就是说device端使用几次就需要从host端走较慢的pci-e 总线读入几次。所以，一般建议只使用一次的数据以及少量的返回数据可以使用zero copy，其他情况建议copy到显存使用，显存DRAM的带宽要比pci-e的带宽高出一个量级。
以上是对zero copy的简要介绍。
至于您的程序，您并未提供进一步的详细信息，就先不做出建议了。
您可以根据上述zero copy的叙述，自行考虑下；或者提供您的代码，让各位网友/斑竹/NV原厂支持一同为您提出建议。
大致如此，祝您编码顺利~


#三件事

+ 启用零复制
+ 使用它分配内存
+ 将常规的主机指针转换成指向设备内存空间的指针

#启用零复制
需要在任何CUDA上下文创建之前进行下面的调用：
```
//Enable host mapping to device memory
CUDA_CALL(cudaSetDeviceFlags(cudaDeviceMapHost));
```
当CUDA上下文被创建时，驱动程序会知道它需要支持主机内存映射，没有驱动程序的支持，零复制将无法工作。如果该支持在CUDA上下文创建之后完成，内存也无法工作。请注意对`cudaHostAlloc`这样的函数调用，尽管在主机内存上执行，也仍然创建一个GPU上下文。
虽然大多数设备支持零复制内存，但是一些早期的设备却不支持。显式检查：
```
struct cudaDeviceProp device_prop
CUDA_CALL(cudaGetDeviceProperties(&device_prop,device_num));
zero_copy_supported=device_prop.canMapHostMemory;
```
#分配主机内存
分配了主机内存，这样它就可以被映射到设备内存。我们对`cudaHostAlloc`函数使用额外的标志`cudaHostAllocMapped`就可以实现。
```
//Allocate  zero copy pinned memory
CUDA_CALL(cudaHostAlloc((void**)&host_data_to_device,size_in_bytes,cudaHostAllocWriteCombined|cudaHostAllocMapped));
```
#将常规的主机指针转换成指向设备内存空间的指针
通过`cudaHostGetDevicePointer`函数：
```
//Conver to  a GPU host pointer
CUDA_CALL(cudaHostGetDevicePointer(&dev_host_data_to_device,host_data_to_device,0);
```
在这个调用中，我们将之前在主机内存空间分配的`host_data_to_device`转换成GPU内存空间的指针。在GPU内核中，只使用转换后的指针，原始的指针只出现在主机执行的代码中。因此，为了之后释放内存，需要在主机上执行一个操作，其他的调用保持不变：
```
//Free pinned memory
CUDA_CALL(cudaFreeHost(host_data_to_device));
```
#代码参考
简单的代码，进行两个数组之间数据的拷贝，这里只是为了说明零复制的使用，并无实际意义。
```
#include <stdio.h> 
#include <stdlib.h>
#include <cuda_runtime.h>  
#include <assert.h>
//这个宏是用来检查返回值的，如果返回值不是0,则说明发生了错误，会中断程序然后打印错误说明。网络上也有别的CUDA_CALL函数。
#define CUDA_CALL(x){const cudaError_t a=(x);if(a!=cudaSuccess){printf("\n Cuda Error: %s (err_num=%d) \n",cudaGetErrorString(a),a);cudaDeviceReset();assert(0);}}

__global__ void sumNum(int *res,int *data){
	res[threadIdx.x]=data[threadIdx.x];
	//打印测试，CUDA3.2以后的版本才支持在kernel函数里打印。此处的打印测试可以验证，零拷贝的确是分块进行的，输出结果并非是顺序的数据
	//printf("%d ",res[threadIdx.x]);
}

int main(){
	size_t size=128*sizeof(int);
	//启用零复制
	CUDA_CALL(cudaSetDeviceFlags(cudaDeviceMapHost));
	int * data;
	//分配主机内存
	CUDA_CALL(cudaHostAlloc((void**)&data,size,cudaHostAllocWriteCombined|cudaHostAllocMapped));
	for(int i=0;i<128;i++){
		data[i]=i;
	}
	int *gpudata;
	//将常规的主机指针转换成指向设备内存空间的指针
	CUDA_CALL(cudaHostGetDevicePointer(&gpudata,data,0));

	int *res; 
	CUDA_CALL(cudaHostAlloc((void**)&res,size,cudaHostAllocWriteCombined|cudaHostAllocMapped));
	res[0]=0;
	int *ans;
	CUDA_CALL(cudaHostGetDevicePointer(&ans,res,0));

	sumNum<<<1,128>>>(ans,gpudata);
	//注意！！因为下面要打印出来测试，所以要先同步数据，这个函数可以保证cpu等待gpu的kernel函数结束才往下运行。如果数据暂时用不到，可以在整体结束以后再加这句话。明显等待kernel函数结束会占用程序进行的时间。
	cudaDeviceSynchronize();
	for(int i=0;i<128;i++){
		printf("%d ",ans[i]);
	}
	//记得零拷贝的free是这个函数
	cudaFreeHost(data);
	cudaFreeHost(res);


}
```
#Reference
《CUDA并行程序设计-GPU编程指南（译本）》ISBN 9787111448617
https://cudazone.nvidia.cn/forum/forum.php?mod=viewthread&tid=7059

[0]: http://www.findspace.name/easycoding/1349 "CUDA锁页内存和零复制"