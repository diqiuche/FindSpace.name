#Pre
GPGPU/ Sim 是加拿大 UBC 大学的 Admodt教授所带领的课题组开发的世界上第一款 cycle accurate 的 GPGPU 仿真器。对于从事GPGPU 微架构,编译器以及应用执行特征提取等工作十分有帮助。

#笔记要点
##Top/ level Organization
1.
[OverallGPU Architecture Modeled by GPGPU/ Sim]
GPGPU/ Sim 中的 GPU 模型由一系列 SIMT 核心构成,这些 SIMT 核心通过片上互连网络与 MemoryPartition 连接,实现与 GDDRDRAM 的通讯。
2.
[SIMTCore Clusters]
每个 SIMT 核心是一个高并行流水的 SIMD 处理器,等同于 NVIDIA 的流多处理器 SM 或者 AMD 的计算单元 CU。
一个流处理器(SP)或一个 CUDA 核心对应一个 lane,也就是 SIMT 核心中的一个 ALU 流水线。
[Detailed Microarchitecture Model of SIMT Core]
3.ALU Pipelines
每个 SIMT 核心有一个 SP 单元和一个 SFU 单元。(这里的SP应该和NVIDIA中的SP不是一回事。)


#manual
http://gpgpu-sim.org/manual/index.php/GPGPU-Sim_3.x_Manual#Microarchitecture_Model
#中文翻译
这个博客翻译的还可以，省略了一点点纹理的东西。
http://blog.sciencenet.cn/blog-1067211-724087.html


[0]: http://gpgpu-sim.org/manual/images/3/39/Simt-core.png 