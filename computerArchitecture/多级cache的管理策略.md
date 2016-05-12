# Exclusive vs Inclusive
cache的多级管理策略
[wiki上的说明部分](https://en.wikipedia.org/wiki/CPU_cache#Exclusive_versus_inclusive)
>Multi-level caches introduce new design decisions. For instance, in some processors, all data in the L1 cache must also be somewhere in the L2 cache. These caches are called strictly inclusive. Other processors (like the AMD Athlon) have exclusive caches: data is guaranteed to be in at most one of the L1 and L2 caches, never in both. Still other processors (like the Intel Pentium II, III, and 4), do not require that data in the L1 cache also reside in the L2 cache, although it may often do so. There is no universally accepted name for this intermediate policy.
The advantage of exclusive caches is that they store more data. This advantage is larger when the exclusive L1 cache is comparable to the L2 cache, and diminishes if the L2 cache is many times larger than the L1 cache. When the L1 misses and the L2 hits on an access, the hitting cache line in the L2 is exchanged with a line in the L1. This exchange is quite a bit more work than just copying a line from L2 to L1, which is what an inclusive cache does.[33]
One advantage of strictly inclusive caches is that when external devices or other processors in a multiprocessor system wish to remove a cache line from the processor, they need only have the processor check the L2 cache. In cache hierarchies which do not enforce inclusion, the L1 cache must be checked as well. As a drawback, there is a correlation between the associativities of L1 and L2 caches: if the L2 cache does not have at least as many ways as all L1 caches together, the effective associativity of the L1 caches is restricted. Another disadvantage of inclusive cache is that whenever there is an eviction in L2 cache, the (possibly) corresponding lines in L1 also have to get evicted in order to maintain inclusiveness. This is quite a bit of work, and would result in a higher L1 miss rate.[33]
Another advantage of inclusive caches is that the larger cache can use larger cache lines, which reduces the size of the secondary cache tags. (Exclusive caches require both caches to have the same size cache lines, so that cache lines can be swapped on a L1 miss, L2 hit.) If the secondary cache is an order of magnitude larger than the primary, and the cache data is an order of magnitude larger than the cache tags, this tag area saved can be comparable to the incremental area needed to store the L1 cache data in the L2.[34]

大体意思：
多级cache有三种设计：
+ exclusive：L1 cahce中的内容不能包含在L2中
+ strictly inclusive：L1cache的内容一定严格包含在L2中。
+ Third one（没有正式名字）:不要求L1的一定包含在L2中

# 优缺点
exclusive方式可以存储更多数据。当然如果L2大大超过L1的大小，则这个优势也并不是很大了。exclusive要求如果L1 miss L2 hit，则需要把L2 hit的line和L1中的一条line交换。这就比inclusive直接从L2拷贝hit line到L1中的方式多些工作。

strictly inclusive 方式的一个优点是，当外部设备或者处理器想要从处理器里删掉一条cache line时，处理器只需要检查下L2 cache即可。而第一种和第三种方式中，则L1也需要被检查。而strictly inclusive一个缺点是L2中被替换的line，如果L1中有映射，也需要从L1中替换出去，这可能会导致L1的高miss率。
inclusive 方式的另外一个优点是，越大的cache可以使用越大的cache line，这可能减小二级cache tags的大小。而Exclusive需要L1和L2的cache line大小相同，以便进行替换。如果二级cahce是远远大于一级cache，并且cache data部分远远大于tag，省下的tag部分可以存放数据。
# update April 3, 2016 10:49 AM
尽管一般而言，在存储体系结构中低级存储总是包含高级存储的全部数据，但对于多级缓存则未必。相反地，存在一种多级排他性（Multilevel exclusion）的设计。此种设计意指高级缓存中的内容和低级缓存的内容完全不相交。这样，如果一个高级缓存请求失效，并在次级缓存中命中的话，次级缓存会将命中数据和高级缓存中的一项进行交换，以保证排他性。
多级排他性的好处是在存储预算有限的前提下可以让低级缓存更多地存储数据。否则低级缓存的大量空间将不得不用于覆盖高级缓存中的数据，这无益于提高低级缓存的命中率。
当然，也可以如内存对缓存般，使用多级包容性（Multilevel inclusion）设计。这种设计的优点是比较容易方便查看缓存和内存间的数据一致性，因为仅检查最低一级缓存即可。对于多级排他性缓存这种检查必须在各级上分别进行。这种设计的一个主要缺点是，一旦低级缓存由于失效而被更新，就必须相应更新在高级缓存上所有对应的数据。因此，通常令各级缓存的缓存块大小一致，从而减少低级对高级的不必要更新。
此外，各级缓存的写策略也不相同。对于一个两级缓存系统，一级缓存可能会使用写通来简化实现，而二级缓存使用写回确保数据一致性。
[维基百科 CPU缓存](https://zh.wikipedia.org/wiki/CPU%E7%BC%93%E5%AD%98#.E5.A4.9A.E7.BA.A7.E7.BC.93.E5.AD.98)

