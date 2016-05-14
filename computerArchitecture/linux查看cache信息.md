# Introduction
本文介绍了如何在linux下查看cache的信息，并进行简单的分析。
# unix设计哲学

>一切皆文件

Linux也很好的继承了这个理念。
# cpu cache
我的cpu：
>Intel(R) Core(TM) i5-3470 CPU @ 3.20GHz

## cpu文件
文件夹`/sys/devices/system/cpu`就是对cpu的文件映射。进入以后，随便进一个cpu核，可以看到cache文件夹，`tree`以后：
```linux
.
├── index0
│   ├── coherency_line_size
│   ├── level
│   ├── number_of_sets
│   ├── physical_line_partition
│   ├── shared_cpu_list
│   ├── shared_cpu_map
│   ├── size
│   ├── type
│   └── ways_of_associativity
├── index1
│   ├── coherency_line_size 
│   ├── level
│   ├── number_of_sets
│   ├── physical_line_partition
│   ├── shared_cpu_list
│   ├── shared_cpu_map
│   ├── size
│   ├── type
│   └── ways_of_associativity
├── index2
│   ├── coherency_line_size
...同上一个文件夹
│   └── ways_of_associativity
└── index3
    ├── coherency_line_size
...同上一个文件夹
    └── ways_of_associativity

```
## 文件解释
|文件|表示内容|表示内容（中）|
|---|------|------------|
|coherency_line_size|size of each cache line usually representing the minimum amount of data that gets transferred from memory|cache line大小（有的地方叫cache block）|
|level|represents the hierarchy in the multi-level cache|cache属于第几层|
|number_of_sets|total number of sets, a set is a collection of cache lines sharing the same index|cache set的数量|
|physical_line_partition|number of physical cache lines sharing the same cachetag|一个tag对应几个cache line（竟然还可以对应多个？？？看来了解的还不够全面）|
|size|Total size of the cache|总大小|
|type|type of the cache - data, inst or unified|cache的类型：数据、指令、统一，一般商用cpu只有L1划分了指令cache和数据cache|
|ways_of_associativity|number of ways in which a particular memory block can be placed in the cache|几路组相连|
|shared_cpu_list|||
|shared_cpu_map||
### shared_cpu_list && shared_cpu_map
解释一下L3 的shared_cpu_map内容的格式：
```
00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,00000000,0000000f
```
表面上看是2进制，其实是16进制表示，每个bit表示一个cpu，1个数字可以表示4个cpu
截取0000000f的后4位，转换为2进制表示

|CPUid|15-4忽略|3|2|1|0|
|-|-|-|-|-|
|0x000f的二进制表示|...|1|1|1|1|

这就表示L3是四个cpu共享的，`cat shared_cpu_list`:
```
0-3
```
是0-3序号的cpu core。
# 组相连分析
针对L3的数据：
ways_of_associativity： 12
size： 6144K  (Bytes)
coherency_line_size: 64   (Bytes)
number_of_sets: 8192

验证：
```
64*12*8192/1024 = 6144 KByte
```

# Reference

[玩转CPU Topology ](http://blog.itpub.net/645199/viewspace-1421876/)
[如何查看CPU的cache大小](http://www.lenky.info/archives/2012/07/1805)
[Linux/include/linux/cacheinfo.h](http://lxr.free-electrons.com/source/include/linux/cacheinfo.h)

[CPU体系架构-Cache](https://nieyong.github.io/wiki_cpu/CPU%E4%BD%93%E7%B3%BB%E6%9E%B6%E6%9E%84-Cache.html)
[理解cache](http://www.mouseos.com/arch/cache.html)