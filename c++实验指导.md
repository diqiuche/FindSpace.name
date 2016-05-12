#算术表达式求值
##实验要求
基础要求：

+ 四则运算
+ 带括号

进阶要求：

+ 次幂
+ 负数

##实验报告格式

+ 个人信息
+ 实验要求
+ 实验内容
+ 实验结果及分析
+ 遇到的问题及解决方式
+ 总结
#样例代码说明
课件中的要求是先将表达式转换成后缀表达式，然后再计算这个后缀表达式。
我把代码改成了纯C的，queue用链表模拟，stack用数组模拟。其中两次用到了stack，由于数据类型不同，所以写了两个结构体，但实际上，可以合成一个，通过添加一个标记变量来识别。为了代码的简单，我没有添加各种边界检查，这并不是一个好习惯，很容易出现溢出等等。
##基本过程
###中缀表达式和后缀表达式
中缀表达式就是通常所说的算术表达式，比如`(1+2)*3-4`。
后缀表达式是指通过解析后，运算符在运算数之后的表达式，比如上式解析成后缀表达式就是`12+3*4-`。这种表达式可以直接利用栈来求解。
##运算符的优先级

|优先级|运算符|
|-|-|
|1|括号()|
|2|负号-|
|3|乘方**|
|4|乘*，除/，求余%|
|5|加+，减-|
|6|小于<，小于等于<=，大于>，大于等于>=|
|7|等于==，不等于!=|
|8|逻辑与&&|
|9|逻辑或|

大致的规律是，一元运算符 > 二元运算符 > 多元运算符。

###利用堆栈解析算术表达式的过程

中缀表达式翻译成后缀表达式的方法如下：
（1）从右向左依次取得数据ch。
（2）如果ch是操作数，直接输出。
（3）如果ch是运算符（含左右括号），则：

+ a：如果ch = '('，放入堆栈。
+ b：如果ch = ')'，依次输出堆栈中的运算符，直到遇到'('为止。
+ c：如果ch不是')'或者'('，那么就和堆栈顶点位置的运算符top做优先级比较。
+ + 1：如果ch优先级比top高，那么将ch放入堆栈。
+ + 2：如果ch优先级低于或者等于top，那么输出top，然后将ch放入堆栈。

（4）如果表达式已经读取完成，而堆栈中还有运算符时，依次由顶端输出。

如果我们有表达式(A-B)*C+D-E/F，要翻译成后缀表达式，并且把后缀表达式存储在一个名叫output的字符串中，可以用下面的步骤。
```
（1）读取'('，压入堆栈，output为空
（2）读取A，是运算数，直接输出到output字符串，output = A
（3）读取'-'，此时栈里面只有一个'('，因此将'-'压入栈，output = A
（4）读取B，是运算数，直接输出到output字符串，output = AB
（5）读取')'，这时候依次输出栈里面的运算符'-'，然后就是'('，直接弹出，output = AB-
（6）读取'*'，是运算符，由于此时栈为空，因此直接压入栈，output = AB-
（7）读取C，是运算数，直接输出到output字符串，output = AB-C
（8）读取'+'，是运算符，它的优先级比'*'低，那么弹出'*'，压入'+"，output = AB-C*
（9）读取D，是运算数，直接输出到output字符串，output = AB-C*D
（10）读取'-'，是运算符，和'+'的优先级一样，因此弹出'+'，然后压入'-'，output = AB-C*D+
（11）读取E，是运算数，直接输出到output字符串，output = AB-C*D+E
（12）读取'/'，是运算符，比'-'的优先级高，因此压入栈，output = AB-C*D+E
（13）读取F，是运算数，直接输出到output字符串，output = AB-C*D+EF
（14）原始字符串已经读取完毕，将栈里面剩余的运算符依次弹出，output = AB-C*D+EF/-
```
###计算算术表达式

当有了后缀表达式以后，运算表达式的值就非常容易了。可以按照下面的流程来计算。

（1）从左向右扫描表达式，一个取出一个数据data
（2）如果data是操作数，就压入堆栈
（3）如果data是操作符，就从堆栈中弹出此操作符需要用到的数据的个数，进行运算，然后把结果压入堆栈
（4）如果数据处理完毕，堆栈中最后剩余的数据就是最终结果。

比如我们要处理一个后缀表达式1234+*+65/-，那么具体的步骤如下。

（1）首先1，2，3，4都是操作数，将它们都压入堆栈
（2）取得'+'，为运算符，弹出数据3，4，得到结果7，然后将7压入堆栈
（3）取得'*'，为运算符，弹出数据7，2，得到数据14，然后将14压入堆栈
（4）取得'+'，为运算符，弹出数据14，1，得到结果15，然后将15压入堆栈
（5）6，5都是数据，都压入堆栈
（6）取得'/'，为运算符，弹出数据6，5，得到结果1.2，然后将1.2压入堆栈
（7）取得'-'，为运算符，弹出数据15，1.2，得到数据13.8，这就是最后的运算结果
#样例代码
```
//
// Created by find on 15-12-28.
//

#include <stdio.h>
#include <stdlib.h>
#include <string.h>


struct stack{
    char * myoperator;
    int index;
};
struct stackForOperand{
    int operand[100];
    int index;
};

struct node {
    int number;
    //用来记录是不是操作符
    int isOperator;
    struct node *next;
};
//这是用来存放生成的后缀表达式的用链表模拟的队列
struct node *outputQueue = NULL;
//队列的尾巴
struct node *tail = NULL;
//符号栈
struct  stack operatorStack;
//在计算后缀表达式的时候用到的数据栈
struct  stackForOperand operandStack;
//初始化栈
void initStack(){
    //模拟栈顶指针
    operatorStack.index=0;
    //预设符号栈大小为100,可自定义。
    operatorStack.myoperator = (char*) malloc(sizeof(char)*100);

    operandStack.index=0;

}
//入栈操作
void push(char aOperator){
    operatorStack.myoperator[operatorStack.index]=aOperator;
    operatorStack.index++;
}
//弹出，但是没有返回值
void pop(){
    operatorStack.index--;
}
//查看栈顶的数据，但是不弹出。
//pop和top都是cpp里的写法，不同语言有不同的写法，比如java只有一个pop函数，弹出且返回栈顶的数据。
char top(){
    return operatorStack.myoperator[operatorStack.index-1];
}
//判断栈是否为空,返回1表示是空的。
int empty(){
    if (operatorStack.index == 0)return 1;
    else return 0;
}

//同理，下面是数据栈的。但其实这两个栈可以合在一起写成一种数据结构。

//入栈操作
void push2(int aOperand){
    operandStack.operand[operandStack.index]= aOperand;
    operandStack.index++;
}
//弹出，但是没有返回值
void pop2(){
    operandStack.index--;
}
//查看栈顶的数据，但是不弹出。
//pop和top都是cpp里的写法，不同语言有不同的写法，比如java只有一个pop函数，弹出且返回栈顶的数据。
int top2(){
    return operandStack.operand[operandStack.index-1];
}
//判断栈是否为空,返回1表示是空的。
int empty2(){
    if (operandStack.index == 0)return 1;
    else return 0;
}

//进队列操作，参考ppt
void enQueue(int aNumber, int isOperator) {
    struct node *tempNode = (struct node *) malloc(sizeof(struct node));
    tempNode->number = aNumber;
    tempNode->isOperator = isOperator;
    tempNode->next = NULL;
    if (tail) {
        tail->next = tempNode;
    } else {
        outputQueue = tempNode;
    }
    tail = tempNode;
}

//出队列，注意由于需要判断是不是操作符，所以不是直接返回int值，返回的是node数据。在下面的判断也是。
struct node *deQueue(void) {
    if (outputQueue) {
        struct node *returnNode = (struct node *) malloc(sizeof(struct node));
        struct node *tempNode = outputQueue;
        returnNode->number = outputQueue->number;
        returnNode->isOperator = outputQueue->isOperator;
        returnNode->next = NULL;
        outputQueue = outputQueue->next;
        if (tempNode == tail) {
            tempNode = NULL;
        }
        free(tempNode);
        return returnNode;
    } else {
        return NULL;
    }
}

//返回运算符的优先级
int getPriority(char ch) {
    int priority;
    switch (ch) {
        case '+' :
            priority = 1;
            break;
        case '-' :
            priority = 1;
            break;
        case '*' :
            priority = 2;
            break;
        case '/' :
            priority = 2;
            break;
        default :
            priority = 0;
            break;
    }

    return priority;
}
//判断是不是数字
int isOperand(char x){
    return x>='0'&& x<='9';
}
//转换成后缀表达式，并存入queue中。
void parseToPostfix(char * str){
    int len = strlen(str);
    int tempNumber;
    char tempChar;
    int i;
    for (i=0;i<len;i++){
        if (isOperand(str[i])){
            //提取出操作数
            tempNumber = 0;
            while (str[i]!='\0' && isOperand(str[i])){
                tempNumber*=10;
                tempNumber+=str[i]-'0';
                i++;
            }
            i--;
            enQueue(tempNumber,0);
        } else{
            switch (str[i]){
                case '(':
                    //左括号直接入队列
                    push('(');
                    break;
                case ')':
                    //右括号则弹出符号栈里所有的东西直到遇到左括号
                    while(!empty()){
                        tempChar = top();
                        pop();
                        if(tempChar == '(')break;
                        else{
                            enQueue(tempChar,1);
                        }
                    }
                    break;
                case '+':
                case '-':
                case '*':
                case '/':
                    if(!empty()){
                        while(!empty()){
                            tempChar = top();
                            //读到的操作符优先级高
                            if(getPriority(str[i])>getPriority(tempChar)){
                                push(str[i]);
                                break;
                            }
                            //栈顶的优先级更高
                            else{
                                pop();
                                enQueue(tempChar,1);
                                if(empty()){
                                    push(str[i]);
                                    break;
                                }
                            }
                        }
                    }else{
                        push(str[i]);
                    }

            }
        }

    }
    while(!empty()){
        tempChar = top();
        pop();
        enQueue(tempChar,1);
    }
}
//测试打印出后缀表达式看看是否正确
void testPrint(){
    struct node * tempPonter = outputQueue;
    while(tempPonter != NULL){
        if(tempPonter->isOperator){
            printf("%c ",tempPonter->number);
        }else{
            printf("%d ",tempPonter->number);
        }
        tempPonter = tempPonter->next;
    }

}
//两个操作数操作
int getValue(char op, int ch1,int ch2) {
    switch( op )    {
        case '+':
            return ch2 + ch1;
        case '-':
            return ch2 - ch1;
        case '*':
            return ch2 * ch1;
        case '/':
            return ch2 / ch1;
        default:
            return 0;
    }
}
//计算后缀表达式
int calcuate(){

    struct  node * pointer = outputQueue;
    int num1,num2;
    while(pointer != NULL){
        if(!pointer->isOperator)
            push2(pointer->number);
        else{
            num1 = top2();
            pop2();
            num2 = top2();
            pop2();
            push2(getValue((char)pointer->number,num1,num2));
        }
        pointer = pointer->next;
    }
    return top2();
}

int main() {
    char expression[]="3+5*(1-22)/2";
    initStack();
    parseToPostfix(expression);
    testPrint();
    printf(" =  %d ",calcuate());

}
```

#Reference
[利用堆栈解析算术表达式一：基本过程][0]

[0]: http://www.cnblogs.com/flyingbread/archive/2007/02/03/638932.html