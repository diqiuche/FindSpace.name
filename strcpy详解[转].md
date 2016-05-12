#题目：
已知strcpy函数的原型是： 
`char * strcpy(char * strDest, const char * strSrc); `
1.不调用库函数，实现strcpy函数
2.解释为什么要返回char *
#一. 实现strcpy
```
//1.strcpy的实现代码     
char * strcpy(char * strDest,const char * strSrc)    
{    
  if ((NULL==strDest)||(NULL==strSrc)) //[1]    
    throw "Invalid argument(s)";       //[2]    
    
  char * strDestCopy=strDest;          //[3]    
    
  while ((*strDest++=*strSrc++)!='\0');//[4]  注意是: \0 而不是 /0   
  return strDestCopy;    
    
}  
```
##错误的做法： 
###[1]
(A)不检查指针的有效性，说明答题者不注重代码的健壮性。 
(B)检查指针的有效性时如果使用((!strDest)||(!strSrc))或(!(strDest&&strSrc))，说明答题者对C语言中类型的隐式转换没有深刻认识。在本例中char *转换为bool即是类型隐式转换，这种功能虽然灵活，但更多的是导致出错概率增大和维护成本升高。所以C++专门增加了bool、true、false三个关键字以提供更安全的条件表达式。 

(C)检查指针的有效性时如果使用((strDest==0)||(strSrc==0))，说明答题者不知道使用常量的好处。直接使用字面常量（如本例中的0）会减少程序的可维护性。0虽然简单，但程序中可能出现很多处对指针的检查，万一出现笔误，编译器不能发现，生成的程序内含逻辑错误，很难排除。而使用NULL代替0，如果出现拼写错误，编译器就会检查出来。 


###[2]
(A)如果 return new string("Invalid argument(s)");，说明答题者根本不知道返回值的用途，并且他对内存泄漏也没有警惕心。从函数中返回函数体内分配的内存是十分危险的做法，他把释放内存的义务抛给不知情的调用者，绝大多数情况下，调用者不会释放内存，这导致内存泄漏。 

(B)如果 return 0;，说明答题者没有掌握异常机制。调用者有可能忘记检查返回值，调用者还可能无法检查返回值（见后面的链式表达式）。妄想让返回值肩负返回正确值和异常值的双重功能，其结果往往是两种功能都失效。应该以抛出异常来代替返回值，这样可以减轻调用者的负担、使错误不会被忽略、增强程序的可维护性。


###[3]
(A)忘记保存原始的strDest值，说明答题者逻辑思维不严密。 也就是保存strDest的起始位置，因为strDest在++中移到最后了。


###[4]
(A)循环写成 while (*strDest++　=　*strSrc++);，同[1](B)。 

(B)循环写成 while (*strSrc!='\0') *strDest++=*strSrc++;，说明答题者对边界条件的检查不力。循环体结束后，strDest字符串的末尾没有正确地加上'/0'。 这里要注意：是检查目标strDest最后一个加上'\0'时才结束。

(C)while ((*strDest++ = *strSrc++)!='\0')是什么意思？

将*strSrc赋值给*strDest，然后判断是不是已经到达\0（即字符串结尾），同时，执行完赋值后strSrc和strDest指针均++一位。
###网上有人这么写：
```
while( *strDest != '\0') // 这是错误的！！    
{    
  *strDest = *strSrc;    
  strDest++;    
  strSrc++;    
}
```   
因为它的顺序是赋值，++，判断，所以在strSrc把最后一个'\0'赋值给strDest后，strDest先++了，所以再次判断时是不一定等于'/0'的，所以这个循环会继续。这是很危险的。
#二. 返回char*

返回 strDest 的原始值，是为了使函数能够支持链式表达式，增加了函数的“附加值”。
链式表达式的形式如：
``` 
int iLength = strlen(strcpy(strA,strB));  
//又如：  
char * strA = strcpy(new char[10],strB);   
```
返回 strSrc 的原始值是错误的。
其一，源字符串肯定是已知的，返回它没有意义。
其二，不能支持形如第二例的表达式。
其三，为了保护源字符串，形参用const限定strSrc所指的内容，把const char *作为char *返回，类型不符，编译报错。

#后记：

有人问下面的代码为什么不对？

```
int main()  
{  
  int *p1 = "abcdef";  
  int p2[] = "abcdef";  
  
  strcpy(p1, "fedcba"); //错误  
  strcpy(p2, "fedcba"); //正确  
  
  return 0;  
}  
```
p1为什么不对，首先p1是个指针，这个指针指向一个字符串常量，这个区是不能修改的，所以当向这个区做'写'操作时，就会出错。

而p2是个数组，数组里存的值可不是常量噢。这个不要混啊！

Reference：
http://blog.csdn.net/lwbeyond/article/details/6181396