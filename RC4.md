#信息安全实验
#RC4和MD5
---
#实验要求
RC4和MD5。将用户的密码通过md5加密之后作为RC4加密的输入key进行设置RC4的密钥，并使用此密钥对用户内容进行加密。
#实验
##几个关键词
1. 密钥流：RC4算法的关键是根据明文和密钥生成相应的密钥流，密钥流的长度和明文的长度是对应的，也就是说明文的长度是500字节，那么密钥流也是500字节。当然，加密生成的密文也是500字节，因为密文第i字节=明文第i字节^密钥流第i字节；
2. 状态向量S：长度为256，`S[0],S[1].....S[255]`。每个单元都是一个字节，算法运行的任何时候，S都包括0-255的8比特数的排列组合，只不过值的位置发生了变换；
3. 临时向量T：长度也为256，每个单元也是一个字节。如果密钥的长度是256字节，就直接把密钥的值赋给T，否则，轮转地将密钥的每个字节赋给T；
4. 密钥K：长度为1-256字节，注意密钥的长度keylen与明文长度、密钥流的长度没有必然关系，通常密钥的长度取为16字节（128比特）。

##RC4的原理分为三步
1. 初始化S和T
```
for i=0 to 255 do
   S[i]=i;
   T[i]=K[ i mod keylen ];
```
2. 初始排列S
```
j=0;
for i=0 to 255 do
   j= ( j+S[i]+T[i])mod256;
   swap(S[i],S[j]);
```
3. 产生密钥流
```
i,j=0;
for r=0 to len do  //r为明文长度，r字节
   i=(i+1) mod 256;
   j=(j+S[i])mod 256;
   swap(S[i],S[j]);
   t=(S[i]+S[j])mod 256;
   k[r]=S[t];//K为密钥
```
4. 利用密钥流与明文进行按位异或

则我们需要做的是把K替换为用户输入key的MD5值。
##获取MD5
利用openssl库([文档里对md5相关的部分函数介绍][0])，一般有两种方式：
```
#include<openssl/md5.h>

MD5_CTX ctx;
unsigned char *data="123";
unsigned char md[16];
MD5_Init(&ctx);
MD5_Update(&ctx,data,strlen(data));//data为要加密的串
MD5_Final(md,&ctx);//md为密文
```
或者：
```
unsigned char * userKey;//从用户获取的明文
unsigned char md5Value[32];//md5值
MD5((unsigned char*)userKey,strlen(userKey),md5Value);
```
##解密
解密则只需要将保存的密钥和密文按位异或即可。
#Tips
#char && unsigned char
在C中，默认的基础数据类型均为signed,在内存中，char与unsigned char没有什么不同，都是一个字节，唯一的区别是，char的最高位为符号位，因此char能表示`-128~127`, unsigned char没有符号位，因此能表示`0~255`.
主要是符号位，但是在普通的赋值，读写文件和网络字节流都没什么区别，反正就是一个字节，不管最高位是什么，最终的读取结果都一样，只是你怎么理解最高位而已，在屏幕上面的显示可能不一样。
但是我们却发现在表示byte时，都用unsigned char，这是为什么呢？
首先我们通常意义上理解，byte没有什么符号位之说，更重要的是如果将byte的值赋给int，long等数据类型时，系统会做一些额外的工作。
如果是char，那么系统认为最高位是符号位，而int可能是16或者32位，那么会对最高位进行扩展（注意，赋给unsigned int也会扩展）,是根据当前char的最高位进行扩展的。
而如果是unsigned char，那么不会扩展。
这就是二者的最大区别。
同理可以推导到其它的类型，比如short， unsigned short。等等
#Code
```
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <openssl/md5.h>  

unsigned char * userKey;
unsigned char md5Value[32];
int keylen;

char  *plaintext;
char ciphertext[1024];
//keystream
	unsigned  char *key;

int pLen;
size_t result;

void readUserKey(){
	FILE *fin;
	if((fin=fopen("userKey","r"))==NULL){
		printf("Read UserKey failed\n");
		exit(1);
	};
	fseek(fin,0,SEEK_END);
	int userkeySize=ftell(fin);
	rewind(fin);
	userKey=(unsigned char*)malloc(sizeof(unsigned char)*userkeySize);
	result = fread(userKey,1,userkeySize,fin);
	if(result!=userkeySize){
		printf("Read userKey error when malloc\n");
		exit(1);
	}
	if(userKey==NULL)printf("null\n");
		printf("userkey %s\n",userKey );
	fclose(fin);
};
void readUserContent(){
	long lSize;
	FILE *fin2;
	if((fin2=fopen("userContent","rb"))==NULL){
		printf("Read userContent error\n");
		exit(2);
	}
	//get the file size 
	fseek(fin2,0,SEEK_END);
	lSize=ftell(fin2);
	rewind(fin2);

	plaintext=(char*)malloc(sizeof(char)*lSize);

	result=fread(plaintext,1,lSize,fin2);
	if(result!=lSize){
		printf("Read userContent error when malloc\n");
		exit(3);
	}
	printf("%s\n",plaintext );
	fclose(fin2);
}

void getMD5(){
	//attention !!!! malloc!!!!!!!!!!!!!!!
	MD5((unsigned char*)userKey,strlen(userKey),md5Value);
	keylen=strlen(md5Value);
	int i;
	for(i=0;i<keylen;i++)
		printf("%2.2x",md5Value[i] );
	printf("\n");
};
	
void encrypt(){
	unsigned  char  s[256],t[256];
	int i,j,temp,q,m,n;
	for(i=0;i<256;i++){
		s[i]=i;
		t[i]=md5Value[i%keylen];
	}
	for(i=0;i<256;i++){
		 j=(j+s[i]+t[i])%256;
		 temp=s[i];
		 s[i]=s[j];
		 s[j]=temp;
	}
	pLen=strlen(plaintext);
	key=(unsigned char*)malloc(sizeof(unsigned char)*pLen);
	m=n=0;
	for(i=0;i<pLen;i++){
		m=(m+1)% 256;
		n=(n+s[n])% 256;
		temp=s[m];
		s[m]=s[n];
		s[n]=temp;
		q=(s[m]+s[n])%256;
		key[i]=s[q];
		ciphertext[i]=plaintext[i]^key[i];
		printf("%X",ciphertext[i] );
	}
	ciphertext[i]='/0';
	printf("\n");
}

void decrypt(){
	int i;
	printf("\n");
	for(i=0;i<pLen;i++){
		printf("%c", ciphertext[i]^key[i]);
	}
}

int main(int argc, char const *argv[])
{
	readUserKey();
	getMD5();
	readUserContent();
	encrypt();
	decrypt();
	return 0;
}
```
#Reference
[RC4加密原理及代码][1]
[RC4加密原理及代码][2]
[char 与 unsigned char的本质区别][3]

[3]: http://www.cnblogs.com/qytan36/archive/2010/09/27/1836569.html
[2]: http://blog.csdn.net/lc_910927/article/details/37599161
[1]: http://blog.csdn.net/guhog/article/details/4203021
[0]: https://www.openssl.org/docs/manmaster/crypto/md5.html 