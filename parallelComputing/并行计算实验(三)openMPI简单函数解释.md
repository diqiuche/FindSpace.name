更多内容查看[并行计算实验系列文章目录][2]
#openMPI简单函数介绍
针对实验用到的几个函数进行说明。
MPI为程序员提供一个并行环境库，程序员通过调用MPI的库程序来达到程序员所要达到的并行目的，可以只使用其中的6个最基本的函数就能编写一个完整的MPI程序去求解很多问题。这6个基本函数，包括启动和结束MPI环境，识别进程以及发送和接收消息：
理论上说，MPI所有的通信功能可以用它的六个基本的调用来实现：

+ `MPI_INIT` 启动MPI环境
+ `MPI_COMM_SIZE` 确定进程数
+ `MPI_COMM_RANK` 确定自己的进程标识符
+ `MPI_SEND` 发送一条消息
+ `MPI_RECV` 接收一条消息
+ `MPI_FINALIZE` 结束MPI环境

#初始化和结束
MPI初始化：通过MPI_Init函数进入MPI环境并完成所有的初始化工作。
```
int MPI_Init( int *argc, char * * * argv )
```
MPI结束：通过MPI_Finalize函数从MPI环境中退出。
```
int MPI_Finalize(void)
```
#获取进程的编号
调用`MPI_Comm_rank`函数获得当前进程在指定通信域中的编号，将自身与其他程序区分。
```
int MPI_Comm_rank(MPI_Comm comm, int *rank)
```
#获取指定通信域的进程数
调用`MPI_Comm_size`函数获取指定通信域的进程个数，确定自身完成任务比例。
```
int MPI_Comm_size(MPI_Comm comm, int *size)
```
#MPI消息
一个消息好比一封信
消息的内容的内容即信的内容，在MPI中成为消息缓冲(Message Buffer)
消息的接收发送者即信的地址，在MPI中成为消息封装(Message Envelop)
MPI中，消息缓冲由三元组<起始地址，数据个数，数据类型>标识
消息信封由三元组<源/目标进程，消息标签，通信域>标识 
##消息发送
MPI_Send函数用于发送一个消息到目标进程。
```
int MPI_Send(void *buf, int count, MPI_Datatype dataytpe, int dest, int tag, MPI_Comm comm)
```
buf是要发送数据的指针，比如一个A数组，可以直接`&A`，count则是数据长度，datatype都要改成MPI的type。dest就是worker的id了。tag则可以通过不同的type来区分消息类型，比如是master发送的还是worker发送的。
##消息接收
`MPI_Recv`函数用于从指定进程接收一个消息
```
  int MPI_Recv(void *buf, int count, MPI_Datatype datatyepe,int source, int tag, MPI_Comm comm, MPI_Status *status)
```

#编译和执行
生成执行文件data
```
mpicc -o programname programname.c
```
一个MPI并行程序由若干个并发进程组成，这些进程可以相同也可以不同。MPI只支持静态进程创建，即：每个进程在执行前必须在MPI环境中登记，且它们必须一起启动。通常启动可执行的MPI程序是通过命令行来实现的。启动方法由具体实现确定。例如在MPICH实现中通过下列命令行可同时在独立的机器上启动相同的可执行程序：  
```
 mpirun –np N programname
```
其中N是同时运行的进程的个数，programname是可执行的MPI程序的程序名。

[2]: http://www.findspace.name/easycoding/1485
