[TOC]
#Pre
犹豫了一段时间，最终下定决心从Ubuntu切换到debian。至于X，搜索了一段时间，看了一些评论，最终决定用gnome3。
入手了之后，发现还不错。记录下配置的一些过程。
强烈建议也查看此文

[记一次Ubuntu重装
](http://www.findspace.name/easycoding/1474)

#安装系统
因为这次是在原机器装了win10的情况下，而且由于机器里有别人的资料，不能格盘，所以只好划出了200G的可用空间，装在逻辑分区。
适应我的最好的空间分配计划：
```
/	100G
/home	100G
/opt	100G
```
很多软件我都装在`/opt`，重装的时候，如果懒得导出来，那就得重新下载。
安装的步骤就很简单了。
#添加普通用户为sudoer
debian下默认新建的用户都是普通用户，不是sudoer，这个和ubuntu有很大区别。这意味着你无法使用sudo来暂时提升权限。
打开terminal,切到root
```
su
chmod u+w /etc/sudoers
gedit /etc/sudoers
```
在下面的数据中
```
# User privilege specification
root    ALL=(ALL:ALL) ALL
```
按照root用户的形式，添加你自己的用户名
记得最后要
```
chmod u-w /etc/sudoers
```
此时如果你用sudo进行一些安装操作，应该会提示有lock，重启一下就好了。
#编辑源
修改源为你自己常用的源，教育网我一般用ustc的ipv6的源，速度超爽，如果你是有线的话。
ustc本身也提供了一个[配置生成器][0]，只需要点几下就可以生成你想要的源
然后
```
sudo apt-get update && sudo apt-get upgrade
```
吧
#安装软件
##下载常用软件
先修改hosts，然后下载下列清单的软件

|application|information|downloadlink|
|-|-|-|
|chrome|浏览器|https://www.google.com/chrome/browser/desktop/index.html|
|sublime|编辑器|http://www.sublimetext.com/3|
|wps office|办公软件，速度感觉比libreoffice快一点|http://community.wps.cn/download/|
|wine qq |这个是2012国际版的，是我用过的最稳定的，而且直接安装就行，也不用单独装wine，下面再写配置步骤|http://pan.baidu.com/s/1pJPT1Gn|
|master pdf editor|pdf阅读和**编辑**工具，linux版本全部功能免费|https://code-industry.net/free-pdf-editor/|
|jetbrains系列的ide|我一般用pycharm,clion,intellij idea,破解在下面说|https://www.jetbrains.com/products.html|
|remarkable|markdown写作工具|https://remarkableapp.github.io/linux/download.html|
|haroopad|markdown写作工具|比remarkable更强大，新版本的remarkable在debian下有bug http://pad.haroopress.com/user.html|
|android studio|安卓IDE|所有关于安卓的工具都可以从这里下载 http://androiddevtools.cn 下载一个镜像，下载链接都是百度网盘的。记得下载完成去官网对比下md5或者sha 教育网修改hosts后下载android sdk之类的速度非常快，默认使用ipv6|

以上的常用软件，除了wineqq，都可以直接下载安装，如果是deb就`dpkg -i xx.deb`安装，如果是`tar.gz tar.xz`之类的压缩包，`tar zxvf `之类的参数进行解压，放到`/opt`下就可以
##jetbrains系列IDE
解压缩之后的运行一般都是从`yourIDE/bin/yourIDE.sh`，比如pycharm，安装在了`/opt`那么在命令行就可以通过
```
nohup /opt/pycharm/bin/pycharm.sh &
```
运行，其中nohup是为了让进程在terminal关闭之后也照样运行，`&`是为了让进程后台运行，不占用terminal的输入
第一次运行pycharm的时候，pycharm会有一系列的配置，其中会有添加可执行文件到`/usr/bin`之类的，输入root的密码，创建完成之后，以后就可以通过按下super键（即微软徽标键），输入pycharm来运行pycharm了。
其他的ide同理。
至于破解，国内有人做了假的验证服务器来通过验证，在要求输入license的时候，选择server license，输入
```
http://15.idea.lanyus.com
```
点击确定就完成了破解，但是这是持续的在线验证，不能长时间断网，如果超过两天断网，就自动转成试用版。离线的方法也可以访问http://idea.lanyus.com来获取更多信息
建议工作的人直接入正吧。一年也不是特别贵对于程序员来说。学生党有edu邮箱的直接下教育版，和正式版一样，只不过需要edu邮箱验证。
##wine qq 的安装
[百度云分享wineqq](http://pan.baidu.com/s/1bpgAt7D)
###wine qq 国际版
由于安装包是32位，所以需要先开启debian 32位架构的支持。
```bash
sudo dpkg --add-architecture i386 
sudo apt-get update
sudo apt-get install lib32z1 lib32ncurses5
```
解压出wineqqintl文件夹来，直接`sudo dpkg -i wine-qqintl_0.1.3-2_i386.deb`，另外两个字体包会自动安装上.
这个qq基本功能都可以用，尽管没有设备管理之类的，而且手机qq上要关闭设备保护登陆功能。
###wineqq longene社区版
2015年11月社区新发布了一版基于qq7.8的。功能很多很强大，直接dpkg安装即可。我遇到的一些bug是安装完成后可能需要从命令行和dash多次启动才能打开qq。
###debian gnome可能遇到的bug
可能会导致alt+tab键失效，关闭qq主界面就好了。
解决办法是在“优化工具”(gnome-tweak-tool)里开启了扩展里的第一个Alternatetab插件功能就好了。


##配置shadowsocks
详见 http://www.findspace.name/res/956 
##安装Dropbox
首先到 https://www.dropbox.com/install 下载debian的64位包，dpkg安装上，这只是个安装器，所以先不要运行。先到你的vps上通过前面那个页面上下面的命令下载真正的运行包，下载完成后是在~目录下的`.dropbox-dist`文件夹，scp回自己的机器，然后再运行dropbox。

#配置系统
##fcitx输入法
###无法输入中文，光标不跟随的applicaiton

jetbrains系列的产品
Sublime
wps for linux

###解决办法
这个不是fcitx的原因，在fcitx的wiki里也有说明
```
sudo apt-get install qt4-qtconfig 
```
安装这个（qt5已经没有这个config了）
然后运行`qtconfig`
在 界面->XIM输入风格，选择 **光标跟随风格（Over the spot）**
下面的默认输入法选择fcitx。（ibus同理）
保存退出。
这个方法可以解决jetbrains输入中文问题，wps的所有问题。sublime的解决办法比较特殊，参考：
www.findspace.name/res/291

而至于光标不跟随，wps可以解决，但是其他的都不可以。这个需要给这些产品提及bug反馈。。。

#语言问题
如果一开始使用的是英语或者汉语，现在想切换，那就运行
```
sudo dpkg-reconfigure locales
```
找到想要的语言，空格键选中，tab键切换到ok，然后移动光标到想要使用的主语言，tab键切到ok。
然后就可以在系统设置里添加汉语了。
#输入法问题
汉语输入必须先切到中文语言环境才能添加汉语输入法，建议使用的fcitx输入法有`fcitx-googlepinyin`,`fcitx-sunpinyin`这俩都差不多。可以添加`fcitx-module-cloudpinyin`设置第二个是从百度或者谷歌匹配的结果。
# 安装完成没有无线
ustc的源默认开启了no-free库，只需要：
```bash
#安装无线网卡驱动
sudo apt-get install firmware-iwlwifi
#安装完后启动wifi模块(注意这里必须进su)
sudo su
modprobe -r iwlwifi ; modprobe iwlwifi
```
然后右上角就可以选择无线了
# 默认没有ll命令
让debian支持ll命令 http://weibo.com/p/1001603933096335560026

# 安装32位库
```bash
#debian开启32位的支持
sudo dpkg --add-architecture i386 
sudo apt-get update
# 安装32位库
sudo apt-get install lib32z1 lib32ncurses5
```
# 添加ctrl alt t 开启终端快捷键
设置-->键盘-->快捷键 添加
```
名称 terminal
命令 gnome-terminal
```
点击禁用那两个字，然后按下想要的键
#gnome3
看到网上有人说gnome2和3的主要区别就是gnome3很多操作都键盘化了，有大量的快捷键。的确是这样的。可以在系统设置的键盘里看到各种快捷键，同时也可以添加自己的快捷键，比如ubuntu中默认的ctrl alt t开启terminal。而且gnome的通知栏是通过windows+m键呼出的，这样实际上是可以避免很多干扰。
gnome3很符合我的审美观，感觉非常整洁。
gnome的设计哲学就是为了让你专注在一个窗口工作。



[0]: https://lug.ustc.edu.cn/repogen/