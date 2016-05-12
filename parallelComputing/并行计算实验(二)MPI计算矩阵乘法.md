#Pre
更多参考[并行计算实验目录][2]
通过opemMPI加速矩阵乘法运算。采用主从模式，0号是master，其他是child（或者叫worker，as you wish）。
#基本思路
两个矩阵A，B进行乘法运算，则A的行 i 乘以B的列 j 得出的数是新矩阵(i,j)坐标的数值。A(M*N) B(N*K)最后矩阵是M*K的，实验中M=N=K=1000，我也就没有明确区分MNK，全部用MATRIX_SIZE定义的。
最简单的思路就是每个worker分配(MATRIX_SIZE/(numprocess-1))个，然后如果有余下的，就分给余数对应的worker。比如MATRIX_SIZE=10，numprocess=4 则实际的worker有3个，每个人分3行，最后的一行给id是1的。可以很简单的利用循环类分配。最后Master收集所有的结果，并按照顺序组装起来就行。
每个worker的工作就是接收来自master的一行，和B矩阵运算，得出新一行的结果，然后发送回master
#代码
多加了很多注释来解释，函数的说明下一节解释下。
```
#include <mpi.h>
#include <stdio.h>
#define MATRIX_SIZE 10
#define FROM_MASTER 1 //这里的类型可以区分消息的种类，以便区分worker发送来的结果
#define FROM_CHILD 2 
#define MASTER 0
MPI_Status status;
int myid,numprocess;
//最终保存的结果
int ans [MATRIX_SIZE*MATRIX_SIZE];
int A[MATRIX_SIZE*MATRIX_SIZE],B[MATRIX_SIZE*MATRIX_SIZE];
//读取文件，注意读取文件要放在master里，不然会读两遍，出现错误
void readFile(){
	FILE* fina,*finb;
	fina=fopen("a.txt","r");
	int i;
	for (i = 0; i < MATRIX_SIZE*MATRIX_SIZE ; ++i)
	{
		fscanf(fina,"%d ",&A[i]);
	}
	fclose(fina);
	finb=fopen("b.txt","r");
	for(i=0;i<MATRIX_SIZE*MATRIX_SIZE;i++)
		fscanf(finb,"%d ",&B[i]);
	fclose(finb);
	printf("read file ok\n");
}
int master(){
	int workid,dest,i,j;
	printf("numprocess %d\n",numprocess );
	//给每个worker发送B矩阵过去
	for(i=0;i<numprocess-1;i++){
		//send B matrix
		MPI_Send(&B,MATRIX_SIZE*MATRIX_SIZE,MPI_INT,i+1,FROM_MASTER,MPI_COMM_WORLD);
	}
	//开始给每个worker分配任务，取模即可
	for (i = 0; i < MATRIX_SIZE; i++)
	{
		//attention:  num of workers is numprocess-1
		workid=i%(numprocess-1)+1;
		//send single line in A
		MPI_Send(&A[i*MATRIX_SIZE],MATRIX_SIZE,MPI_INT,workid,FROM_MASTER,MPI_COMM_WORLD);
	}

	//等待从worker发送来的数据
	int tempLine[MATRIX_SIZE];
	for (i = 0; i < MATRIX_SIZE*MATRIX_SIZE; i++)
	{
		ans[i]=0;
	}
	for (i = 0; i < MATRIX_SIZE; ++i)
	{
		int myprocess=i%(numprocess-1)+1;
		printf("Master is waiting %d\n",myprocess);
		//receive every line from every process
		MPI_Recv(&tempLine,MATRIX_SIZE,MPI_INT,myprocess,FROM_CHILD,MPI_COMM_WORLD,&status);
		//发送过来的都是计算好了的一行的数据，直接组装到ans里就行
		for(j=0;j<MATRIX_SIZE;j++){
			ans[MATRIX_SIZE*i+j]=tempLine[j];
		}
		printf("Master gets %d\n",i);
	}

	for(i=0;i<MATRIX_SIZE*MATRIX_SIZE;i++){
		printf("%d ",ans[i] );
		if(i%MATRIX_SIZE==(MATRIX_SIZE-1))printf("\n");
	}
	printf("The Master is out\n");

}
int worker(){
	int mA[MATRIX_SIZE],mB[MATRIX_SIZE*MATRIX_SIZE],mC[MATRIX_SIZE];
	int i,j,bi;
	MPI_Recv(&mB,MATRIX_SIZE*MATRIX_SIZE,MPI_INT,MASTER,FROM_MASTER,MPI_COMM_WORLD,&status);
	//接收来自master的A的行
	for(i=0;i<MATRIX_SIZE/(numprocess-1);i++){
		MPI_Recv(&mA,MATRIX_SIZE,MPI_INT,MASTER,FROM_MASTER,MPI_COMM_WORLD,&status);
		//矩阵乘法，A 的一行和B矩阵相乘
		for(bi=0;bi<MATRIX_SIZE;bi++){
			mC[bi]=0;
			for(j=0;j<MATRIX_SIZE;j++){
				mC[bi]+=mA[j]*mB[bi*MATRIX_SIZE+j];
			}
		}
		MPI_Send(&mC,MATRIX_SIZE,MPI_INT,MASTER,FROM_CHILD,MPI_COMM_WORLD);
	}
	//如果处于余数范围内，则需要多计算一行
	if(MATRIX_SIZE%(numprocess-1)!=0){
		if (myid<=(MATRIX_SIZE%(numprocess-1)))
		{
			MPI_Recv(&mA,MATRIX_SIZE,MPI_INT,MASTER,FROM_MASTER,MPI_COMM_WORLD,&status);
			for(bi=0;bi<MATRIX_SIZE;bi++){
				mC[bi]=0;
				for(j=0;j<MATRIX_SIZE;j++){
					mC[bi]+=mA[j]*mB[bi*MATRIX_SIZE+j];
				}
			}
			MPI_Send(&mC,MATRIX_SIZE,MPI_INT,MASTER,FROM_CHILD,MPI_COMM_WORLD);
		}
	}
	printf("The worker %d is out\n",myid);
}
int main(int argc, char **argv)
{
	MPI_Init (&argc, &argv); 
	MPI_Comm_rank(MPI_COMM_WORLD,&myid);
	MPI_Comm_size(MPI_COMM_WORLD,&numprocess);

	if(myid==MASTER){
		readFile();
		master();
	}
	if(myid>MASTER){
		worker();
	}

	MPI_Finalize();
	return 0;
}
```
#UPDATE
2015.11.2
代码的写法有些错误，B矩阵忘记了转置，不过在计算的时候，我是按转置后的矩阵进行运算的。转置是为了利用程序局部性，优化访存。现在这个写法用MPI是没有问题的，但是用后面的OpenMP和Pthread就会影响性能。影响多少没测。后面再发的OpenMP和Pthread也不贴代码了。后面俩比这个简单很多。

[2]: http://www.findspace.name/easycoding/1485