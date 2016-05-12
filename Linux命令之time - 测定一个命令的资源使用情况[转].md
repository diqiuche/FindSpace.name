
#用途说明
time命令常用于测量一个命令的运行时间，注意不是用来显示和修改系统时间的（这是date命令干的事情）。但是今天我通过查看time命令的手册页，发现它能做的不仅仅是测量运行时间，还可以测量内存、I/O等的使用情况，手册页上的说法是time a simple command or give resource usage，其中time一词我认为它应该是测量或测定的意思，并不单指时间。一个程序在运行时使用的系统资源通常包括CPU、Memory和I/O等，其中CPU资源的统计包括实际使用时间（real time）、用户态使用时间（the process spent in user mode）、内核态使用时间（the process spent in kernel mode）。但是简单的使用time命令并不能得到内存和I/O的统计数据，请看后文慢慢道来。
#常用参数
time命令最常用的使用方式就是在其后面直接跟上命令和参数：
`time <command> [<arguments...>]`
在命令执行完成之后就会打印出CPU的使用情况：
```
real    0m5.064s      <== 实际使用时间（real time） 
user    0m0.020s     <== 用户态使用时间（the process spent in user mode） 
sys     0m0.040s      <== 内核态使用时间（the process spent in kernel mode）
```
time命令跟上-p参数可以只打印时间数值（秒数），不打印单位。
#使用示例
##示例一 统计运行时间
```
[root@web186 root]# time find . -name "mysql.sh" 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh

real    0m14.837s
user    0m0.030s
sys     0m0.120s
[root@web186 root]#
```
注：real远大于user加上sys，因为find需要遍历各个目录，需要大量的I/O操作，而磁盘I/O通常是最慢的环节，因此大部分时间find进程都在等待磁盘I/O完成。
```
[root@web186 root]# time find . -name "mysql.sh" 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh

real    0m0.230s
user    0m0.040s
sys     0m0.030s
```
注：再次运行的时候，发现real time变得很小了，应该是操作系统将刚才操作过的一些文件缓存了的缘故，因而大大减少了磁盘I/O。 
```
[root@web186 root]# time -p find . -name "mysql.sh" 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh
real 0.15
user 0.04
sys 0.03
```
注：使用-p参数时，直接打印所需时间的数值，单位为秒，而不是更友好的格式，包括分钟、秒钟的显示方式。 
`[root@web186 root]#`
##示例二 Linux系统中time命令其实不止一个
看过手册页的朋友，会发现有个-f参数可以来指定统计信息的输出格式，我们也来试一下。
```
[root@web186 root]# time -f "real %f\nuser %f\nsys %f\n" find . -name "mysql.sh"  
-bash: -f: command not found

real    0m0.024s
user    0m0.000s
sys     0m0.000s
```
怪哉，不灵啊。使用type -a来看一下。使用这个shell内建命令经常会有意想不到的发现。
```
[root@web186 root]# type -a time 
time is a shell keyword
time is /usr/bin/time
```
注：通过这条命令我们可以发现我们常用的time其实是一个Shell关键字，还有一个外部命令/usr/bin/time，它有何不同呢？ 
```
[root@web186 root]# /usr/bin/time 
Usage: /usr/bin/time [-apvV] [-f format] [-o file] [--append] [--verbose]
       [--portability] [--format=format] [--output=file] [--version]
       [--help] command [arg...]
```
注：外部命令/usr/bin/time功能更强大，下面来尝试一下。
```
[root@web186 root]# /usr/bin/time find . -name "mysql.sh" 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh
0.03user 0.04system 0:00.12elapsed 55%CPU (0avgtext+0avgdata 0maxresident)k
0inputs+0outputs (154major+63minor)pagefaults 0swaps
```
注：注意后面两行，打印了很多信息，但看不太清楚。它有一个参数-v，可以打印得更清楚些。 
```
[root@web186 root]# /usr/bin/time -v find . -name "mysql.sh" 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh
        Command being timed: "find . -name mysql.sh"
        User time (seconds): 0.03
        System time (seconds): 0.05
        Percent of CPU this job got: 47%
        Elapsed (wall clock) time (h:mm:ss or m:ss): 0:00.17
        Average shared text size (kbytes): 0
        Average unshared data size (kbytes): 0
        Average stack size (kbytes): 0
        Average total size (kbytes): 0
        Maximum resident set size (kbytes): 0
        Average resident set size (kbytes): 0
        Major (requiring I/O) page faults: 153
        Minor (reclaiming a frame) page faults: 64
        Voluntary context switches: 0
        Involuntary context switches: 0
        Swaps: 0
        File system inputs: 0
        File system outputs: 0
        Socket messages sent: 0
        Socket messages received: 0
        Signals delivered: 0
        Page size (bytes): 4096
        Exit status: 0
[root@web186 root]#
```
尝试完这个之后，我看了一下Google搜索的结果，发现有位大虾早已发现了这个秘密，见相关资料【1】。
##示例三 解决time命令输出信息的重定向问题
time命令的输出信息是打印在标准错误输出上的， 我们通过一个简单的尝试来验证一下。
```
[root@web186 root]# time find . -name "mysql.sh" >1.txt 

real    0m0.081s
user    0m0.060s
sys     0m0.020s
[root@web186 root]# time find . -name "mysql.sh" 2>2.txt 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh

real    0m0.068s
user    0m0.040s
sys     0m0.030s
```
通过上面的尝试，发现无法将time的输出信息重定向到文件里面，为什么？因为time是shell的关键字，shell做了特殊处理，它会把time命令后面的命令行作为一个整体来进行处理，在重定向时，实际上是针对后面的命令来的，time命令本身的输出并不会被重定向的。那现在怎么办呢？网上提供了两种解决方法【2，3】，我们一一尝试一下。
第一种解决方法，就是将time命令和将要执行的命令行放到一个shell代码块中，也就是一对大括号中，要注意空格和分号的使用。 
`[root@web186 root]# {time find . -name "mysql.sh"} 2>2.txt`
好像成功了。慢，看一下对不对。 
```
[root@web186 root]# cat 2.txt 
-bash: {time: command not found
```
原来bash把 {time 作为一个整体来处理了，前后都加上空格试试。 
```
[root@web186 root]# { time find . -name "mysql.sh" } 2>2.txt 
> Ctrl+C
```
这次Bash认为命令都没有输入完成，少了分号。因为Bash认为后面的 } 是find命令的参数。 
```
[root@web186 root]# { time find . -name "mysql.sh"; } 2>2.txt 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh
[root@web186 root]# cat 2.txt 

real    0m0.068s
user    0m0.030s
sys     0m0.040s
```
第一种方式的尝试成功了，总结起来就是 `{ time command-line; } 2>file`  注意分隔符的使用。
为什么用2呢？
>0是标准输入 
1是标准输出 
2是标准错误

而time的结果是通过标准错误输出的，和一般命令不同

另外一种方式就是使用子Shell的方式，如下所示：
```
[root@web186 root]# (time find . -name "mysql.sh") 2>2.txt 
./work186/sms/bin/mysql.sh
./work186/sms/src/scripts/mysql.sh
./work186/sms/src/scripts1/mysql.sh
./work186/sms1/bin/mysql.sh
./work186/sms1/src/scripts/mysql.sh
./temp/sms/bin/mysql.sh
./temp/sms/src/scripts/mysql.sh
[root@web186 root]# cat 2.txt 

real    0m0.083s
user    0m0.040s
sys     0m0.020s
[root@web186 root]#
```
第二种方式的尝试也成功了，总结起来就是 `(time command-line) 2>file `这里time紧贴着小括号(也可以的，命令行结束也不必带分号。当然最好还是用第一种方式，毕竟启动一个子shell是要多占些资源的。
##问题思考
1. 为什么执行find命令的多次时间统计差别很大，一次实际时间需要12秒，另外几次却不足1秒？
##相关资料
【1】孵梦森林 Linux中的两个timehttp://blog.chinaunix.net/u3/100692/showart_2222690.html
【2】Nine Rivers 重定向 Bash “time” 命令的输出http://9rivers.linkka.com/2009/12/16/%E9%87%8D%E5%AE%9A%E5%90%91-bash-time-%E5%91%BD%E4%BB%A4%E7%9A%84%E8%BE%93%E5%87%BA/
【3】seizeF的专栏 重定向Bash命令——time http://blog.csdn.net/seizeF/archive/2010/01/09/5164405.aspx
http://www.chinaunix.net/old_jh/24/585.html
http://blog.csdn.net/robertsong2004/article/details/38655389


转载链接： http://codingstandards.iteye.com/blog/798788