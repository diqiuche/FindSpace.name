
现在的机器上都是有多个CPU和多个内存块的。以前我们都是将内存块看成是一大块内存，所有CPU到这个共享内存的访问消息是一样的。这就是之前普遍使用的SMP模型。但是随着处理器的增加，共享内存可能会导致内存访问冲突越来越厉害，且如果内存访问达到瓶颈的时候，性能就不能随之增加。NUMA（Non-Uniform Memory Access）就是这样的环境下引入的一个模型。比如一台机器是有2个处理器，有4个内存块。我们将1个处理器和两个内存块合起来，称为一个NUMA node，这样这个机器就会有两个NUMA node。在物理分布上，NUMA node的处理器和内存块的物理距离更小，因此访问也更快。比如这台机器会分左右两个处理器（cpu1, cpu2），在每个处理器两边放两个内存块(memory1.1, memory1.2, memory2.1,memory2.2)，这样NUMA node1的cpu1访问memory1.1和memory1.2就比访问memory2.1和memory2.2更快。所以使用NUMA的模式如果能尽量保证本node内的CPU只访问本node内的内存块，那这样的效率就是最高的。

 

在运行程序的时候使用numactl -m和-physcpubind就能制定将这个程序运行在哪个cpu和哪个memory中。玩转cpu-topology 给了一个表格，当程序只使用一个node资源和使用多个node资源的比较表（差不多是38s与28s的差距）。所以限定程序在numa node中运行是有实际意义的。

 

但是呢，话又说回来了，制定numa就一定好吗？--numa的陷阱。SWAP的罪与罚文章就说到了一个numa的陷阱的问题。现象是当你的服务器还有内存的时候，发现它已经在开始使用swap了，甚至已经导致机器出现停滞的现象。这个就有可能是由于numa的限制，如果一个进程限制它只能使用自己的numa节点的内存，那么当自身numa node内存使用光之后，就不会去使用其他numa node的内存了，会开始使用swap，甚至更糟的情况，机器没有设置swap的时候，可能会直接死机！所以你可以使用numactl --interleave=all来取消numa node的限制。

 

综上所述得出的结论就是，根据具体业务决定NUMA的使用。

 

如果你的程序是会占用大规模内存的，你大多应该选择关闭numa node的限制。因为这个时候你的程序很有几率会碰到numa陷阱。

另外，如果你的程序并不占用大内存，而是要求更快的程序运行时间。你大多应该选择限制只访问本numa node的方法来进行处理。
#Reference
http://www.cnblogs.com/shanyou/archive/2009/12/26/1633052.html
http://www.enet.com.cn/article/2010/0511/A20100511651737_4.shtml
http://www.ibm.com/developerworks/cn/linux/l-numa/index.html
http://baike.baidu.com/view/380118.htm
#原文
http://www.cnblogs.com/yjf512/archive/2012/12/10/2811823.html