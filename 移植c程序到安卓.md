#Pre
安卓是基于linux做的，如果是普通的c程序经过简单的修改也可以在安卓上跑。当然是纯命令行式的跑。
我的开发环境：
Ubuntu14.04 x86_64 
手机：小米4c Android 5.1.1
#准备工作
参考[ubuntu下Eclipse搭建android开发环境][0]配置adb，手机连接上电脑，从开发者模式里开启usb调试，然后输入
```
adb devices
```
正确的结果应该是列出了可用的设备的series_number和名称，如果多个设备同时插入，可以用`-s series_number`来使用adb shell

为了防止后面编译出现问题，64位的系统也要安装32位的一些库：
```
sudo apt-get install ia32-libs
As of Ubuntu 13.10, one has to run this now: 
sudo apt-get install lib32z1, you may need to get the C++ stdlibs too, with this: sudo apt-get install lib32ncurses5 lib32stdc++6

For adb you just need:
sudo apt-get install libc6:i386 libstdc++6:i386
For aapt you need to add:
sudo apt-get install zlib1g:i386

```
参考这两个问答得到的
[No such file or directory for existing executable][1]
[“No such file or directory” trying to execute linux binary on Android device][2]
#从最简单的helloworld开始
##编译
写helloworld程序：
```
#include "stdio.h"
int main(int argc, char const *argv[])
{
	printf("hello\n");
	return 0;
}
```
既然是要跑在arm上，自然是要用arm gcc编译，先安装：
```
sudo apt-get install gcc-arm-linux-androideabi
#如果没有上面那个，也可以安装，当然下面的编译也是要改成这个
sudo apt-get install gcc-arm-linux-gnueabi
```
编译：
```
arm-linux-androideabi-gcc -o hello hello.c -fPIE -pie --target=mandroid -static 
```
##编译说明
前面是正常的编译，后面两个参数`-fPIE -pie`,PIE这个安全机制从4.1引入，但是Android L之前的系统版本并不会去检验可执行文件是否基于PIE编译出的。因此不会报错。但是Android L已经开启验证，如果调用的可执行文件不是基于PIE方式编译的，则无法运行。加入这两个参数可能会有warning:
```
cc1: warning: command line option ‘-ftarget=mandroid’ is valid for Java but not for C [enabled by default]
```
**这两个参数会对静态编译产生影响，在某些情况下不可用，如果出现了问题，请尝试去掉这两个参数**
##32位
目标板是32位，而编译出来的如果是64位，则在运行的时候会出现no such file or directory的情况。可以用`file hello`命令查看编译出来的文件的信息：
```
hello: ELF 32-bit LSB  executable, ARM, EABI5 version 1 (SYSV), statically linked, for GNU/Linux 2.6.32, BuildID[sha1]=fa8252e049e0ce715f8718c72105c3aedb22c38e, not stripped
```
32-bit程序，statically linked,ARM.
我在小米4c android5.1.1上测试，64位的程序可以跑，在开发板armv7l上，则无法运行64位。
#运行
通过`adb shell`（如果只有一个设备连接到了pc上），进入设备的命令行之后，新开一个终端，
```
adb push hello /data/local/
```
或者在设备的命令行里通过`adb pull`从pc拉文件过去。
注意，不要放在`/sdcard/`里，默认sd卡是没法有执行权限的，文件所有的`x`属性都不开，就算是su也没用。而放在/data/local是最简便的选择，push过去就有x权限。
然后`./hello`执行就可以了。可以发现正常打印出了hello，可能会提示一个warning，下次再贴上那个warning吧。
#运行简易server
在[简易HTTPSERVER(500行左右代码)][5]一文中，我详述了TinyHttpd的一些东西，并且代码也提供了地址，这次直接拿它做实验

+ 修改makefile文件里的编译器，并加上`-fPIE -pie`标记（可选，根据实际情况，如果出现了下面Reference的第一个问题错误提示则加上）
+ htdoc文件夹的路径也要修改就是在`httpd.c`源文件里，关于`index.html`的部分。然后adb push htdoc和编译好的httpd
+ 运行的时候要加端口`./httpd 8888`，（端口自己指定）

可以看到程序正常执行，从手机浏览器打开`http://localhost:8888`看看吧。

#后续
现在只是移植了很简单的两个程序，并没有涉及更多的库或者别的东西，所以很容易就实现了，如果是一个比较完善的完整的稍大的程序，可能就会有更多问题，不过这个简单的实验也证明，这样的移植是完全可行的。
等下次再玩的时候，试试nohup 还有`&`能不能让server在后台，等等。

#Reference
[Android 5.0 "error: only position independent executables (PIE) are supported."][3]
[NDK编译可执行文件在Android L中运行显示error: only position independent executables (PIE) are supported.失败问题解决办法。][4]


[0]: http://www.findspace.name/easycoding/280
[1]: http://askubuntu.com/questions/73491/no-such-file-or-directory-for-existing-executable "No such file or directory for existing executable"
[2]: http://stackoverflow.com/questions/13581927/no-such-file-or-directory-trying-to-execute-linux-binary-on-android-device 
[3]: https://github.com/tatsuhiro-t/aria2/issues/321
[4]: http://blog.csdn.net/hxdanya/article/details/39371759 
[5]: http://www.findspace.name/easycoding/1209 "简易HTTPSERVER(500行左右代码)"