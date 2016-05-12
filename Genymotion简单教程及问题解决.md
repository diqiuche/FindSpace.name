[TOC]

#介绍
Genymotion是一款安卓虚拟机，极速”的虚拟机。
>速度的酸爽简直不能忍

+ http://www.genymotion.com 官方网站
+ http://www.genymotion.net/    国人自己的国内网

建议注册在官网注册。从国内网下载。
#安装
下载安装很简单，都有说明，这里不再赘述。
>注意安装完成后，在设置ADB那里设置自己的adb路径

#google play等功能修复
genymotion2.0升级后就没有google app商店了，应用安装一般也会出错。提示INSTALL_FAILED_CPU_ABI_INCOMPATIBLE” errors 这个貌似只要把apk的中文去掉就可以了。但是一些应用装上还是不行。
##解决方法的原链接
[来自stackoverflow][1]
##翻译一下
###1.下载ARM Translation Installer
[下载地址][2]
拖到虚拟机的home界面，会弹出两次对话框，点两次ok，
###2.重启虚拟机
建议用adb命令重启，[adb的使用参看链接][3]。直接叉掉程序可能会不管用。
>有的可能在这里已经可以正常安装程序了，你可以试一下
###3.下载gapps
[下载源（国外）][4]
[从这个网站参考你的操作系统对应的包][5]
如果你是4.2的安卓系统，可以直接从[这里下载][6]

这是[资源整合的文件夹][7]，文件我会不断完善

下载完成之后，还是拖进去，点两次ok，然后手动重启。

[4.4的gapps下载地址（xda论坛的）][9]


我的到这里就结束了，至于原文中说会出现google paly等已停止的提示，4.2没有出现。
另外，4.4的包bug太多，完全不能正常使用，会不断弹出google XX已停止。一直没办法。
#安装x86架构的软件
你也可以不做上面的步骤，直接安装有x86支持的软件，比如uc浏览器。从虚拟机里面的浏览器搜索就可以。[这里有个uc的x86版本][7]。
应用汇市场和安卓市场都有x86版本。
#输入法
在设置里面选择谷歌输入法，并设置为默认即可。或者下载支持x86的输入法。[这里面有个搜狗的x86版本][7]，不要更新，更新之后就不能用了。
#Eclipse插件
eclipse开发时需要安装插件才能和genymotion连接起来。

[插件安装方法][8]
安装的参数：
>Name: Genymobile
>Location: http://plugins.genymotion.com/eclipse

注意，不知什么时候开始这个地址出现了`There are no Categorized items`，需要在下面的列表里**取消勾选`Group items by category.`**，然后重新add一遍，就会有了。
然后后面的步骤就是一路next和accept了

附上 adt 下载包（包含eclipse）[百度网盘地址][7]

[1]: http://stackoverflow.com/questions/17831990/how-do-you-install-google-frameworks-play-accounts-etc-on-a-genymotion-virtu/20013322#20013322 
[2]: http://pan.baidu.com/s/1qWNl5WK
[3]: http://www.findspace.name/res/360
[4]: http://wiki.cyanogenmod.org/w/Google_Apps#gappsCM11
[5]: http://wiki.rootzwiki.com/Google_Apps#Jelly_Bean_.28Android_4.1.x-4.3.x.29
[6]: http://pan.baidu.com/s/1hqpBPy8 "4.2 gapps"
[7]: http://pan.baidu.com/s/1pJsjZCZ  "安卓分享文件夹"
[8]: http://www.findspace.name/res/687 "Eclipse插件"
[9]: http://forum.xda-developers.com/showthread.php?t=2397942 "xda论坛