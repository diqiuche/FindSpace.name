# Introducation
安卓基于linux，所以很多地方都可以视为linux的操作。
# 安装必需软件
hosts的修改需要root权限。
而普通自带的文件管理器只能显示sd卡上的内容，而实际上，sd卡只是linux系统挂载在`/storage/emulated/sdcard1`,类似这种格式，而内置ROM的路径则是`/storage/emulated/0`。
所以推荐用RE文件管理器，或者ES文件管理器，在手机应用市场搜一搜就可以，这两个都可以。
# hosts文件
hosts文件的路径是`/etc/hosts`，实际上`/etc`文件夹只是个链接，真正的文件是`/system/etc/hosts`，不过是软链接，修改是同步的。
## 修改权限
用文件管理器进到hosts文件的地址，找到hosts文件

![](http://www.findspace.name/wp-content/uploads/2016/03/es_file_manager.jpg)

注意查看相关的属性信息

+ 修改时间
+ 大小
+ 权限

这里对权限进行详细说明，linux下的标准权限图解是这样的
![linux文件权限](http://vbird.dic.ksu.edu.tw/linux_basic/0210filepermission_files/0210filepermission_3.gif)

（图片来自鸟哥的linux私房菜）
所以注意要把hosts文件的权限至少添加可读写权限，在RE文件管理器中，长按hosts文件，提示会进入系统的可写状态，确认后弹出如下的权限设置，至少勾上所有的读写权限。然后确定就可以了。

![文件权限](http://www.findspace.name/wp-content/uploads/2016/03/re_file_manager_hosts2.jpg)

# ES文件管理器的说明
注意要在ES文件管理主页的侧边栏开启`Root工具箱`，然后长按hosts文件，弹出的框才有修改权限按钮。
![](http://www.findspace.name/wp-content/uploads/2016/03/es_root_tool.jpg)
![](http://www.findspace.name/wp-content/uploads/2016/03/es_root.jpg)
##修改hosts
从[修改hosts使用谷歌服务](http://www.findspace.name/res/72)下载好hosts以后，把下载的hosts的内容全选，复制，在re文件管理器里以文本模式打开`/etc/hosts`,粘贴进去，保存
修改完成后，打开浏览器，多刷新几次，看看谷歌是不是能上去了。
如果仍不能上谷歌，开关一下飞行模式，或者重启一下试试

# 说明
[CoolHosts一键修改hosts](http://www.findspace.name/easycoding/503)的主要原理就是这个。可以从[coolhosts的源码](https://github.com/FindHao/CoolHosts)里分析出。
PC下参考[修改hosts使用谷歌服务](http://www.findspace.name/res/72)