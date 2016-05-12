#java开发环境的搭建
[java开发环境搭建及eclipse安装][0]
#下载解压Android SDK Tools 
[官网SDK][1]
在这里推荐一个国内的镜像吧
[AndroidDevTools][2]
里面还有很多关于安卓开发的东西。
本文都以linux为例，下载的是tgz文件，则tar zxvf 解压之即可。
移动到`/opt`下，
```
mv 文件夹名字 /opt/adt-sdk
```
#环境变量设置
修改用户目录下的.bashrc文件
```
geidt ~/.bashrc
```
在最后添加这样两句
```
export ANDROID_HOME=/opt/adt-sdk
export PATH=$PATH:$ANDROID_HOME/platform-tools
```
具体目录依照自己的设置
[win的环境变量设置][3]
#安装android adt插件
这里只介绍离线安装方法，因为现在google被墙的很厉害，而且就算可以连上，速度也是渣。
[还是从那个网站下载][4]
[安装方法参看此文][5]
如果出现了Failed to fetch URL https://dl-ssl.google.com/android/repository/等等，
#platform-tools
[下载platform-tools,包括adb等命令][6]
这是 adb, fastboot 等工具包。把解压出来的 platform-tools 文件夹放在 android sdk 根目录下，并把 adb所在的目录添加到系统 PATH 路径里，即可在命令行里直接访问了 adb, fastboot 等工具。
path已经添加了，现在注销系统重新登录，在命令行里试试adb命令能不能用了～
如果提示adb no such file or directory，那么应该是64位的系统没有安装32库。
解决方法：

+ ubuntu系统
```
sudo apt-get install libc6:i386 libgcc1:i386 gcc-4.6-base:i386 libstdc++5:i386 libstdc++6:i386
sudo apt-get install libqt4-opengl
sudo apt-get install libglu1-mesa
sudo apt-get ia32-libs-multiarch
sudo apt-get install ia32-libs
```
+ Fedora系统
```
sudo yum install glibc-devel.i686
sudo yum install libgcc.i686
sudo yum install libstdc++-devel.i686
```
#下载各种安卓版本和工具
直接从/opt/adt-sdk里的tools打开android或者从eclipse里window–>android sdk manager都可以。然后就是勾选下载了。


[0]: http://www.findspace.name/easycoding/155 "java开发环境搭建及eclipse安装"
[1]: http://developer.android.com/sdk/index.html 
[2]: http://www.androiddevtools.cn/#sdk-tools "安卓镜像"
[3]: http://www.findspace.name/res/360
[4]: http://www.androiddevtools.cn/#adt-plugin
[5]: http://www.findspace.name/res/687
[6]: http://www.androiddevtools.cn/#sdk-platform-tools