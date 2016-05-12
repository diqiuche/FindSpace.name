# Introdution 
[arm官方手册中关于cache lock down的部分][0]
Arm9 Processers的Rev0.ARM940T Technical Reference Manual.Caches and Write Buffer Cache lock down部分。
鉴于我的水平，请和上面官网的文档部分结合查看，本文主要是对上文的翻译。如有纰漏，恳请指正。
绝大多数情况下cache对程序员都是透明的，但是仍然会出现要求cache line不要被替换的需求出现，所以大多数架构都支持对cache的操作。
# 锁cache需要满足的条件
Locking down a region of the I Cache or D Cache is achieved by executing a short software routine, taking note of these requirements:

+ the program should be held in a non-cached area of memory | 程序应该运行在标记为不可缓存的内存区域，（否则加锁代码自己很可能就被锁在cache里）
+ the cache should be enabled and interrupts should be disabled
+ software must ensure that the code or data to be locked down is not already in the cache | 软件应该保证要锁的代码或数据尚未在cache中？？？
+ if the caches have been used since the last reset, the software must ensure that the cache in question is cleaned, if appropriate, and then flushed.| 要使用的部分的cache应该被clean且如果可以，flush掉

# 锁cache(locking down the caches)
I cache和D cache锁具有的相同步骤特点：

+ cache块应该置于lock down 模式
+ 一个line的填充应该是强制性的
+ 相应的数据应该被锁在cache里

>line fill: 在cache当找不到数据的时候，processor 将会产生 cache line fill 动作，将从下级 cache 或 memory 中读取数据 fill

如果超过一个line被locked，只需要重复多次操作即可

##Data Cache lock down

+ Write to CP15 register 9, setting DL=1 and Dindex=0.
+ Initialize the pointer to the first of the 16 words to be locked.
+ Execute an LDR from that location. This forces a linefill from that location, and the resulting four words are captured by the cache.
+ Increment the pointer by 16 to select cache bank 1.
+ Execute an LDR from that location. The resulting linefill is captured in cache bank 2.
+ Repeat steps 1 to 5 for cache banks 3 and 4.
+ Write to CP15 register 9, setting DL=0 and Dindex=1.

1. 写CP15 寄存器9，置DL=1 && Dindex=0
2. 初始化指针，指向lock的cache的前16个word头部(为什么是16个word？？)
3. 在头部执行LDR命令，这将强制性开始linefill？？？
4. 指针+16来选择cache bank1（每次+16=+4Byte=+32位=+一行）
5. 当前位置执行LDR命令
6. 重复1-5命令来填充bank 3,4的数据
7. 写CP15 寄存器9，置DL=0 && Dindex=1

如果这里有更多的数据需要被锁，则在步骤7中，DL仍=1,Dindex则+1,并重复以上步骤。直到所有需要被锁的数据都被读入了缓存，DL才置0

## Instruction cache的锁
For the I Cache, this procedure is as follows:

+ Write to CP15 register 9, setting IL=1 and Iindex=0.
+ Initialize the pointer to the first of the sixteen words to lock down.
+ Force a line fill from that location by writing to CP15 register 7.
+ Increment the pointer by 16 to select cache segment 1.
+ Force a line fill from that location by writing to CP15 register 7. The resulting line fill is captured in segment 1.
+ Repeat for cache segments 3 and 4.
+ Write to CP15 register 9, setting IL=0 and Iindex=1.

如果需要更多数据被锁，同理。

## 替换策略
在[arm官方文档的另一部分中](http://infocenter.arm.com/help/index.jsp?topic=/com.arm.doc.ddi0329l/Beieiiab.html)说明的替换策略原文：

>Replacement strategy
The cache controller uses a pseudo-random replacement strategy. A deterministic replacement strategy can be achieved, when you use them in combination with the lockdown registers.
The pseudo-random replacement strategy fills empty, unlocked ways first. If a line is completely full, the victim is chosen as the next unlocked way.
If you require a deterministic replacement strategy, the lockdown registers are used to prevent ways from being allocated. For example, if the L2 size is 256KB, and each way is 32KB, and a piece of code is required to reside in two ways of 64KB, with a deterministic replacement strategy, then ways 1-7 must be locked before the code is filled into the L2 cache. If the first 32KB of code is allocated into way 0 only, then way 0 must be locked and way 1 unlocked so that the second half of the code can be allocated in way 1.
There are two lockdown registers, one for data and one for instructions, if so required, you can separate data and instructions into separate ways of the L2 cache.

cache控制器使用伪随机的替换策略。你也可以结合它利用lockdown的寄存器来实现一个确定的替换策略。
伪随机替换策略首先填充空的、未锁定的cache line。如果line满了，~~下一个未锁定的line将被用来替换。~~（这里不确定）
如果你需要一个严格确定的替换策略，lockdown寄存器被用来阻止cache line被分配内容。举个例子，256KB的L2，每个line是32KB，一段程序要求填充64KB的数据（两个line），对于一个严格确定的替换策略，在内容填充到L2cache 之前，line 1-7必须被锁定。如果前32KB的内容只被填充到了line0，则line 0必须被锁，而且line1必须解锁，以便剩下的32KB填充到line1里。
这里有两个lockdown寄存器，一个是data的一个是instructions的，你可以分别使用他们。

# Dcache锁和Icache锁的区别
两者类似。
显著的不同是，在指令存入I cache用的是MCR指令，而不是LDR（load 到寄存器），这是由于哈佛结构决定的。在MCR的过程中，指针寄存器里的值输出指针地址总线，一个读内存的动作将执行。而cache 由于之前的flush操作，将miss这次访问，则这个line将被填充。
其他的操作与Dcache锁没有区别。
MCR的操作举例：
```asm
The MCR to perform the I Cache lookup is a CP15 register 7 operation:
MCR p15, 0, Rd, c7, c13, 1
```
# No bb, Show me the code
指令cache锁宏样例
```asm
;	Subroutine lock_i_cache
;	R1 contains start address of code to be locked down 
;	R1包含了需要被锁代码的起始地址
;	The subroutine performs a lock-down of instructions in the 
;	I Cache 该子程序是用来锁I cache里的指令的
;	It first reads the current lock_down index and then locks 	
;	down the number of lines requested.
;	它首先会读取当前lock_down index的值，然后锁住请求的line
;	Note that this subroutine must be located in a non-cacheable
;	region of memory in order to work, or these instructions
;	themselves will be locked into the cache. Interrupts should also 
;	be disabled.
;	The subroutine should be called via the ‘BL’ instruction.
;
;	This subroutine returns the next free cache line number in R0, or 
;	0 in R0.
;	if an error occurs.

lock_i_cache
	STMFD 	R13!, {R1-R3}					; save corrupted registers 保存中断寄存器
	BIC	R1, R1, #0x3f					; align address to cache line
	MRC	p15, 0, R3, c9, c0, 1 	; get current instruction cache index 这里并不是写错了，此处将协处理器p15的寄存器中的数据传送到ARM处理器的寄存器r3中
	AND	R2, R2, #0x3f					; mask off unwanted bits
	ADD	R3, R2, R0					; Check to see if current index
	CMP 	R3, #0x3f					; plus line count is greater than 63
    // 如果此时被锁的line超过了限制就跳到出错？？？
							; If so, branch to error as
							; more lines are being locked down
							; than permitted
	//r2里存放的是要锁的line数
	ORR	2, R2, #0x80000000					; set lock bit, r2 contains the cache
							; line number to lock down
lock_loop
	MCR 	p15, 0, R2, c9, c0, 1 	; write lock down register
	MCR 	p15, 0, R1, c7, c13, 1	; force line fetch from external memory 
	ADD 	R1, R1, #16					; add 4 words to address
	MCR	p15, 0, R1, c7, c13, 1					; force line fetch from external memory
	ADD	R1, R1, #16					; add 4 words to address
	MCR	p15, 0, R1, c7, c13, 1					; force line fetch from external memory
	ADD	R1, R1, #16					; add 4 words to address
	MCR	p15, 0, R1, c7, c13, 1					; force line fetch from external memory
	ADD	R1, R1, #16					; add 4 words to address

	ADD	R2, R2, #0x1					; increment cache line in lock down 
							; register
	SUBS	R0, R0, #0x1					; decrement line count and set flags
	BNE	lock_loop					; if r0! = 0 then branch round

	BIC	R0, R2, #0x80000000					; clear lock bit in lockdown register


	MCR	p15, 0, R0, c9, c0, 1					; restrict victim counter to lines 
							; r0 to 63

	LDMFD	 R13!, {R1-R3}					; restore corrupted registers and return
	MOV	PC, LR					; R0 contains the first free cache line
							; number
error
	LDR	R0, =0					; make r0 = 0 to indicate error
	LDMFD	 R13!, {R1-R3}					; restore corrupted registers and return
	MOV	PC, LR
```

# Reference

[why-would-a-region-of-memory-be-marked-non-cached](http://stackoverflow.com/questions/90204/why-would-a-region-of-memory-be-marked-non-cached)
[ARM中LDR伪指令与LDR加载指令](http://www.cnblogs.com/hnrainll/archive/2011/06/14/2080241.html)
[ARM汇编中的LDR指令总结](http://blog.sina.com.cn/s/blog_5f9b3de40100qvnr.html)
[arm指令中mov和ldr有什么区别?](http://zhidao.baidu.com/question/39403018.html)
[ARM官方doc的LDR命令介绍](http://infocenter.arm.com/help/topic/com.arm.doc.dui0204ic/Chdhbfcd.html)
[ARM汇编中ldr伪指令和ldr指令](http://blog.csdn.net/ce123/article/details/7182756)
[谁能解释下mrc p15,0,r0,c1,c0,0？把我搞死了 ](http://bbs.csdn.net/topics/200014187)
[关于ARM9协处理器CP15及MCR和MRC指令  ](http://6xudonghai.blog.163.com/blog/static/336406292008724103317304/)

[0]: http://infocenter.arm.com/help/index.jsp?topic=/com.arm.doc.ddi0092b/ch04s06s01.html "arm cache lock的说明"