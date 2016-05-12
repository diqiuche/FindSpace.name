#计算平台介绍
Flynn's taxonomy (multiprogramming context)

||Single instruction|Multiple instruction|Single program|Multiple program|
|-|-|-|-|-|
|Single data|SISD|MISD|||		
|Multiple data|SIMD|MIMD|SPMD|MPMD|

**注意配图，配图才是重点，图片来源于wiki**
Flynn于1972年提出了计算平台的Flynn分类法，主要根据指令流和数据流来分类，共分为四种类型的计算平台，
##单指令流单数据流机器（SISD）
![][2]
SISD机器是一种传统的串行计算机，它的硬件不支持任何形式的并行计算，所有的指令都是串行执行。并且在某个时钟周期内，CPU只能处理一个数据流。因此这种机器被称作单指令流单数据流机器。早期的计算机都是SISD机器，如冯诺.依曼架构，如IBM PC机，早期的巨型机和许多8位的家用机等。
##单指令流多数据流机器（SIMD）
![][0]
SIMD是采用一个指令流处理多个数据流。这类机器在数字信号处理、图像处理、以及多媒体信息处理等领域非常有效。
Intel处理器实现的MMXTM、SSE（Streaming SIMD Extensions）、SSE2及SSE3扩展指令集，都能在单个时钟周期内处理多个数据单元。也就是说我们现在用的单核计算机基本上都属于SIMD机器。
##多指令流单数据流机器（MISD）
![][1]
MISD是采用多个指令流来处理单个数据流。由于实际情况中，采用多指令流处理多数据流才是更有效的方法，因此MISD只是作为理论模型出现，没有投入到实际应用之中。
##多指令流多数据流机器（MIMD）
![][3]
MIMD机器可以同时执行多个指令流，这些指令流分别对不同数据流进行操作。最新的多核计算平台就属于MIMD的范畴，例如Intel和AMD的双核处理器等都属于MIMD。


[0]: http://www.findspace.name/wp-content/uploads/2015/06/SIMD2.svg_.png
[1]: http://www.findspace.name/wp-content/uploads/2015/06/MISD.svg_.png
[2]: http://www.findspace.name/wp-content/uploads/2015/06/SISD.svg_.png
[3]: http://www.findspace.name/wp-content/uploads/2015/06/MIMD.svg_.png