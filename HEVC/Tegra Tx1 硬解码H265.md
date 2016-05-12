# Introducation
NVIDIA® TEGRA® X1 全新的移动超级芯片
全新的 Tegra X1 是我们有史以来的移动处理器。 它拥有 256 个 NVIDIA Maxwell™ GPU 核心和一颗 64 位 CPU、具备无与伦比的 4K 视频功能和超越上一代产品的节能性与性能，所有这些使其能够完美适配挑战性的移动应用。

|||
|-|-|
|GPU|NVIDIA Maxwell 256 核 GPU DX-12、OpenGL 4.5、NVIDIA CUDA®、OpenGL ES 3.1、AEP、以及 Vulkan|
|CPU|8 核 CPU 、64 位 ARM® CPU 4 颗 A57 2MB 二级缓存颗粒; 4 颗 A53 512KB 二级缓存颗粒|
|VIDEO|H.265、VP9 4K 60 fps 视频 4k H.265、4k VP9、4k H.264|
|POWER|20 纳米片上系统 - 台积电分离式电源轨、第四代集群交换|
|显示屏|4K x 2K @60Hz、1080p @120Hz HDMI 2.0 60 fps、HDCP 2.|

# 硬解码
在其文档中已经明确说明支持硬编码、编码。通过gstreamer。
```bash
H.265 Encode (NVIDIA accelerated encode)
gst-launch-1.0 videotestsrc ! 'video/x-raw, format=(string)I420, width=(int)640, height=(int)480' ! omxh265enc ! filesink location=test.h265 -e
```
```
H.265 Decode (NVIDIA accelerated decode)
gst-launch-1.0 filesrc location=<filename.mp4> ! qtdemux name=demux demux.video_0 ! queue ! h265parse ! omxh265dec ! nvoverlaysink -e
```
经测试的确很强大。
# 测试
测试文件是`Kimono_1920x1080_24.yuv`,

```
gst-launch-1.0 filesrc location=Kimono_1920x1080_24.yuv ! videoparse format=i420 width=1920 height=1080 framerate=24 ! omxh265enc ! filesink location=Kimono_1920x1080_24.bin -e
```
终端输出：
```
Setting pipeline to PAUSED ...
Inside NvxLiteH264DecoderLowLatencyInitNvxLiteH264DecoderLowLatencyInit set DPB and MjstreamingInside NvxLiteH265DecoderLowLatencyInitNvxLiteH265DecoderLowLatencyInit set DPB and MjstreamingPipeline is PREROLLING ...
Framerate set to : 24 at NvxVideoEncoderSetParameterNvMMLiteOpen : Block : BlockType = 8 
===== MSENC =====
NvMMLiteBlockCreate : Block : BlockType = 8 
===== NVENC blits (mode: 1) into block linear surfaces =====
Pipeline is PREROLLED ...
Setting pipeline to PLAYING ...
New clock: GstSystemClock
Got EOS from element "pipeline0".
Execution ended after 0:00:03.341615615
Setting pipeline to PAUSED ...
Setting pipeline to READY ...
Setting pipeline to NULL ...
Freeing pipeline ...

```
压缩10秒的1080p视频只用了3秒，已经实时。
压缩前后文件大小对比
```
ubuntu@tegra-ubuntu:~$ ll -h Kimono_1920x1080_24.yuv
-rw--w---- 1 ubuntu ubuntu 712M Mar  2 08:36 Kimono_1920x1080_24.yuv
ubuntu@tegra-ubuntu:~$ ll -h Kimono_1920x1080_24.bin
-rw-rw-r-- 1 ubuntu ubuntu 4.8M Mar  2 11:37 Kimono_1920x1080_24.bin
```
解码命令：
```
gst-launch-1.0 filesrc location=./Kimono_1920x1080_24.bin ! h265parse ! omxh265dec ! nvoverlaysink -e
```
会自动播放视频。

gstreamer的相关内容不再贴，我也只是了解了一天。知道并测试tx1能硬编码解码h265即止。
#Reference

http://stackoverflow.com/questions/30583133/gstreamer-unalble-to-encode-uyvy-as-h264