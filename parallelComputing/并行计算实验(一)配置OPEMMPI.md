更多参考[并行计算实验目录][2]
#安装openMPI
由于是实验，也不进行多机的配置了，只在虚拟机里安装吧。[多个机器的配置可以参考此文][1]
最简单的方法，apt安装
```
sudo apt-get install libcr-dev mpich2 mpich2-doc
```
#测试
hello.c
```
/* C Example */
#include <mpi.h>
#include <stdio.h>
 
int main (int argc, char* argv[])
{
  int rank, size;
 
  MPI_Init (&argc, &argv);      /* starts MPI */
  MPI_Comm_rank (MPI_COMM_WORLD, &rank);        /* get current process id */
  MPI_Comm_size (MPI_COMM_WORLD, &size);        /* get number of processes */
  printf( "Hello world from process %d of %d\n", rank, size );
  MPI_Finalize();
  return 0;
}
```
编译运行及显示结果
```
mpicc mpi_hello.c -o hello
mpirun -np 2 ./hello
Hello world from process 0 of 2
Hello world from process 1 of 2
```
正常出现结果表明没有问题，
看下openmpi的版本
```
mpirun --version

mpirun (Open MPI) 1.6.5
Report bugs to http://www.open-mpi.org/community/help/
```
#Reference
[how to install mpi in ubuntu][0]

[0]: https://jetcracker.wordpress.com/2012/03/01/how-to-install-mpi-in-ubuntu/ 
[1]: http://blog.csdn.net/bendanban/article/details/9136755
[2]: http://www.findspace.name/easycoding/1485