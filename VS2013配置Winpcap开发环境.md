#[WinPcap 4.0.1在线中文手册][0]
#Start
本文以Visual Studio 2013和WinPcap 4.1.3作为示例，对32位和64位Win7通用
##1、下载并安装WinPcap运行库

http://www.winpcap.org/install/default.htm
一些捕包软件会捆绑安装WinPcap，MentoHust也会附带WinPcap，这种情况下一般可以跳过此步。
##2、下载WinPcap开发包
http://www.winpcap.org/devel.htm
解压到纯英文路径，得到如图1所示目录结构：
![][1]
##3、新建项目
以管理员权限打开Visual Studio，新建一个Visual C++的Win32控制台应用程序，设置为空项目，如图2所示：
![][2]
##4、设置项目
打开项目属性，如图3所示添加`WPCAP`和`HAVE_REMOTE`这两个宏定义：
![][3]
##5、添加`wpcap.lib`和`ws2_32.lib`两个库。
![][4]
##6、添加包含路径（即图1的`Include`目录）和库路径（即图1的`Lib`目录）：
![][5]
##7、设置UAC
此步不是必须的，但是推荐设置。让生成的程序能够自动触发管理员权限对话框。无论如何，最终生成的程序都需要以管理员权限运行。
![][6]
##8、完成
完成以上步骤并点击确定保存设置以后，向项目添加需要的源文件即可。例如新建一个C++源文件（也可以添加现有的）
![][7]




#Tips：
##问题1
对于提示的用scanf不安全的问题：可以把`scanf`改成`scanf_s`，或者直接在`#include<stdio.h>`前面加上`#define  _CRT_SECURE_NO_WARNINGS`就可以不报警告
##问题2
在选择网卡之后出现错误，程序中断，推测：需要选择活动的网卡才可以，根据网卡的型号来确定本地连接，可以参考“打开网络和共享中心”中适配器的顺序。

#代码样例

##[计算机网络课程设计][8]

#Reference：

http://blog.csdn.net/kxcfzyk/article/details/20129867


[0]: http://www.ferrisxu.com/WinPcap/html/index.html "WinPcap 4.0.1在线中文手册"
[1]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap1.png
[2]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap2.png
[3]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap3.png
[4]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap4.png
[5]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap5.png
[6]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap6.png
[7]: http://www.findspace.name/wp-content/uploads/2015/06/winpcap7.png
[8]: http://www.findspace.name/easycoding/881 