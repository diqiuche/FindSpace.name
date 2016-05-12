#Pre
去年买了树莓派之后，又买了个免驱的无线网卡，查看芯片是RTL8188cus，看[wiki无线网卡的列表][0]它是支持monitor模式的，但是实际使用的时候，并不能开启监听模式。
在[贴吧的贴子][1]讨论，时隔一年，断断续续的回复下，终于有人在[stackoverflow上发现了有用的讨论][2]。
>Raspbian官方不能开是因为rtlwifi这驱动在arm上不稳定，所以被屏蔽了，换成realtek官方的驱动了。这个官方的驱动代码是2013年释出的，所以很多功能并不支持。只要重新编译rtlwifi驱动到内核里面就可以了。


下面整理一下整个步骤。
#步骤
##STEP 0: Update existing modules and kernel to latest更新现有的kernel和模块到最新版本
```
sudo apt-get update
sudo rpi-update
uname -a
Linux raspberrypi 4.1.7+ #815 PREEMPT Thu Sep 17 17:59:24 BST 2015 armv6l GNU/Linux
```
##STEP 1: Get the raspbian kernel source and add missing dependencies获取最新的raspbian 内核源码并安装依赖
```
git clone --depth=1 https://github.com/raspberrypi/linux
sudo apt-get install bc lshw
```
##STEP 2: Enable the rtlwifi (kernel) drivers for RTL8188CUS (RTL8192)用rtlwifi的驱动代替原来的rtl8188cus
```
leafpad linux/drivers/net/wireless/Kconfig
找到下面一行，去掉前面的#
#source "drivers/net/wireless/rtlwifi/Kconfig"
找到下面一行，在前面加上#
source "drivers/net/wireless/rtl8192cu/Kconfig"
编辑makefile
leafpad linux/drivers/net/wireless/Makefile
找到下面一行，去掉前面#
#obj-$(CONFIG_RTLWIFI)         += rtlwifi/
```
##STEP 3: Compile and install kernel (took many hours)编译和安装内核
```
cd linux
KERNEL=kernel
make bcmrpi_defconfig
#下面这行，我第一天中午开始跑，第二天早上看到跑完了。。。
make zImage modules dtbs
sudo make modules_install
```
下面的可以直接复制到脚本里运行脚本：
```
sudo cp arch/arm/boot/dts/*.dtb /boot/
sudo cp arch/arm/boot/dts/overlays/*.dtb* /boot/overlays/
sudo cp arch/arm/boot/dts/overlays/README /boot/overlays/
sudo scripts/mkknlimg arch/arm/boot/zImage /boot/$KERNEL.img
```
##STEP 4: Reboot
`sudo reboot`
##STEP 5: Check that the rtlwifi/rtl8192cu module is loaded检查新驱动是否成功加上了
```
lsmod | fgrep rtl8192cu

rtl8192cu             100806  0 
rtl_usb                14781  1 rtl8192cu
rtl8192c_common        72091  1 rtl8192cu
rtlwifi               101122  3 rtl_usb,rtl8192c_common,rtl8192cu
mac80211              623281  3 rtl_usb,rtlwifi,rtl8192cu

lshw
  *-network:0
       description: Ethernet interface
       physical id: 1
       bus info: usb@1:1.3
       logical name: wlan0
       serial: 00:0b:81:94:e9:a3
       capabilities: ethernet physical
       configuration: broadcast=yes driver=rtl8192cu driverversion=4.1.7+ firmware=N/A link=no multicast=yes
```
##STEP 6: 安装iw工具
默认的`iwconfig`工具还是没法开启监听模式，安装`iw`这个工具，
```
sudo apt-get install iw -y
```
查看可用的物理网卡
```
iw dev

phy#0
	Interface wlan0
		ifindex 3
		wdev 0x1
		addr e8:4e:06:20:22:fc
		type managed
		channel 6 (2437 MHz), width: 40 MHz, center1: 2447 MHz

```
##STEP 7查看是否支持监听模式
```
iw phy phy0 info
... lots of stuff 忽略...
Supported interface modes:
     * IBSS
     * managed
     * AP
     * AP/VLAN
     * monitor（看到这个说明支持）
     * mesh point
     * P2P-client
     * P2P-GO
... lots more stuff 忽略...
```
##STEP 8开启监听模式
从这里就可以参考[树莓派(RASPBERRYPI)安装AIRCRACK-NG,REAVER及WIFI破解教程[整理]][3]
注意在执行到`sudo airmon-ng start wlan0`的时候，应该还是提示错误，但是可以通过运行
```
sudo airmon-ng check kill
```
结束所有阻碍它开启的进程，然后`start`就可以了
#后续
开启监听模式后，会关闭它的wifi连接功能，所以必须要外接显示器和键盘操作。
但是我印象中，使用另外一个型号无线网卡的时候，开启了monitor模式之后，仍可以连接wifi。而且在当前这个小网卡上，开启的interface是`wlan0mon`而不是`mon0`，所以在上面的破解教程里，所有`mon0`都要改成`wlan0mon`
另外我在实验中，开始运行破解命令之后，一直都提示`failed to associate with ...`问题，这个应该是说无线关闭了wps功能，但是连接任何wifi都是这样的，就不禁让我怀疑哪里出了问题，难道周围人都这么有警惕性？？我用平板开了个热点，终于连上了，但是破解仍有问题。一直都是持续的` receive timeout (0x03), or WPS transaction fail (0x02) `。最终我放弃了，没有继续让它跑下去，希望各位如有结果，请回来告知。
这个问题可以参考[Constant receive timeout (0x03), or WPS transaction fail (0x02) with rtl8187][4]

可以先通过`wash -i wlan0mon -C`这个命令扫描开启了wps的。

[0]: https://wikidevi.com/wiki/Wireless_adapters/Chipset_table
[1]: http://tieba.baidu.com/p/3489839634 "贴吧讨论"
[2]: http://stackoverflow.com/questions/32703715/enable-monitoring-mode-for-rtl8188cus-via-usb-on-raspbian
[3]: http://www.findspace.name/res/1184 
[4]: https://code.google.com/p/reaver-wps/issues/detail?id=183