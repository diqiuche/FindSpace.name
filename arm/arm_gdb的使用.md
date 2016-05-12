#Introduction
本文简单介绍了我带的实验过程中对gdb的使用，以及以实验简单实验内容进行举例的用法。
# GDB概述
GDB是GNU开源组织发布的一个强大的UNIX下的程序调试工具。或许，各位比较喜欢那种图形界面方式的，像VC、BCB等IDE的调试，但如果你是在UNIX平台下做软件，你会发现GDB这个调试工具有比VC、BCB的图形化调试器更强大的功能。所谓“寸有所长，尺有所短”就是这个道理。
一般来说，GDB主要帮忙你完成下面四个方面的功能：

1. 启动你的程序，可以按照你的自定义的要求随心所欲的运行程序。
2. 可让被调试的程序在你所指定的调置的断点处停住。（断点可以是条件表达式）
3. 当程序被停住时，可以检查此时你的程序中所发生的事。
4. 动态的改变你程序的执行环境。

从上面看来，GDB和一般的调试工具没有什么两样，基本上也是完成这些功能，不过在细节上，你会发现GDB这个调试工具的强大，大家可能比较习惯了图形化的调试工具，但有时候，命令行的调试工具却有着图形化工具所不能完成的功能。
# 手动编译
从 ftp://mirrors.ustc.edu.cn/gnu/gdb/ 找个版本下载，
安装依赖
```
sudo apt-get install texinfo 
sudo apt-get install libncurses5-dev m4 flex bison
```
编译安装：
```
sudo make 
sudo make install
```
完成后则可以通过过`arm-linux-gdb`来运行。

# 远程gdb的链接
在target（开发板）上运行
```bash
gdbserver ip:port binary_file
```
显示：
```bash
Process bombg created; pid = 30404
Listening on port 12345
```
ip可以为`localhost`或者`127.0.0.1`或者直接获取到本机ip填入，port一般不要使用保留的1024以内。`binary_file`则是需要执行的文件。
表示进程bombg创建成功了，此时gdbserver正在监听12345端口。
在host（你的主机）上则进行连接：
```bash
arm-linux-gdb binary_file
(gdb)target remote ip:port 
# 如果正常连接上，则target端会显示类似
Remote debugging from host 211.87.235.11
```
注意可能有权限问题，通过`ll bombg`查看文件是否有可执行权限，通过`chmod a+x bombg`来添加可执行权限。

# gdb常用的命令
学会使用help。

+ `r|run [ARGS]` 在命令的后面可以跟随发送给程序的参数，参数可以包含Shell通配符（*，[…]等），和输入输出重定向符（<，>，>>）。如果你使用不带任何参数的run命令，GDB就再次使用你上次执行run命令时给的参数。(有时可能终端会提示远端不支持该命令，并且会给出支持的命令的提示)
+ `b|break *addr|func` addr为逻辑地址，func则为函数名。更多信息通过help或者谷歌查看
+ `n|next [stepN]` 下一行代码，以source line（注意和下面si的区别），step over，而不陷入。
+ `d|delete [N]` 删除断点，默认是删除所有的断点，通过后面添加数字来表示断点号
+ `p|print` p/x 以十六进制显示 p/d 以十进制显示 p/t 以二进制显示 p/s 以字符串格式显示  p   $eax  查看寄存器eax中的内容   `p/x  (char *) p/x  *(int *)`
+ `disass|disassemble [func_name|0x0 0x10] 反汇编一个函数或者一段内存地址，例子种的0x0是起始地址，第二个是终止地址  
+ `si|stepi` 以汇编行执行。
+ `info `则用来查看信息，比如`info break`查看所有断点

# 查看实验汇编代码
[实验所用文件百度网盘分享](http://pan.baidu.com/s/1jIEnvtc)
.s文件为反汇编出来的文件，可能与在执行时，通过disass命令获得的有出入。可以通过`objdump`自己获得反汇编的文件。
先找到main函数入口。使用文本编辑器（geidt,vim等等）搜索main即找到，通过阅读了解大致main的过程。然后确定六关的入口，为phase_1,phase_2等。
# 实验的第一个函数
查阅汇编代码后，首先找到第一个函数的汇编地址：
```asm
000083f0 <phase_1>:
    83f0:	e92d4800 	push	{fp, lr}
    83f4:	e28db004 	add	fp, sp, #4
    83f8:	e24dd008 	sub	sp, sp, #8
    83fc:	e50b0008 	str	r0, [fp, #-8]
    8400:	e51b0008 	ldr	r0, [fp, #-8]
    8404:	e59f1018 	ldr	r1, [pc, #24]	; 8424 <phase_1+0x34>
    8408:	eb00025d 	bl	8d84 <strings_not_equal>
    840c:	e1a03000 	mov	r3, r0
    8410:	e3530000 	cmp	r3, #0
    8414:	0a000000 	beq	841c <phase_1+0x2c>
    8418:	eb000337 	bl	90fc <explode_bomb>
    841c:	e24bd004 	sub	sp, fp, #4
    8420:	e8bd8800 	pop	{fp, pc}
    8424:	0007163c 	.word	0x0007163c
```
可以通过函数名`phase_1`或者直接在地址行添加断点：
```asm
(gdb)b *0x000083f4
```
通过`disass`查看当前函数的反汇编，同时可以看到有个`=>`符号，表示当前要执行该行了。`si`则来运行汇编行
看到有跳转函数的地方

+ `<strings_not_equal>` 字符串不匹配
+ explode_bomb 炸弹爆炸

猜测这个`strings_not_equal`肯定有输入参数来进行对比，因此通过`si step_n`来跳到跳转字符串匹配函数前，ldr是读取指令，猜测r0,r1是传入参数。打印r0 r1
```asm
p/s (char *)$r0
p/s (char *)$r1
```
发现r0即为自己的输入，r1为要比对的字符串。则第一关的输入解出来了。
重新运行程序，输入获得的字符串，程序输出`Phase 1 defused. How about the next one?`，则第一关已通过。


# Reference
[用GDB调试程序](http://blog.csdn.net/haoel/article/details/2879)
[GDB disassemble](http://blog.sina.com.cn/s/blog_54c5dcff0100h33j.html)
[arm B、BL、BX、BLX 和 BXJ](http://infocenter.arm.com/help/topic/com.arm.doc.dui0204ic/Cihfddaf.html)