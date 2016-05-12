目前主要提供了对谷歌服务（不包括play商店）和wiki的服务。其他的根据源的不同进行筛选。

![][3] 
 
# 1.下载地址 
## [点击立即下载][17]
CoolHosts的更新会在微信公众号发消息:
**FindBlog**
# 2.使用方法 

+ 一键更新
	直接点击app中的**一键更新**按钮，等待提示更新成功。遇到问题请及时反馈给我。
+ 初始化hosts
	还原hosts为`127.0.0.1 localhost`，这是系统默认hosts文件的内容。
+ 检查Coolhosts版本
	该功能已加入自动提示,点击版本更新里面的提示就可以直接下载更新。
+ 自定义hosts地址
	可手动设置自己的纯文本hosts源地址，注意一定要是纯文本格式，而且目前设置了之后，只会在App重启之前有效。
+ 从文件读
	选择自己从别的地方下载的hosts文件(可以是任何后缀)，然后点击一键更新，从本地下载的hosts更新。在App重启之前有效。
+ 编辑Hosts（TODO）


[adblockingdetector id="56c46fdd3e5fe"]

# 3.反馈方式
发送建议或者出现的问题：

+ 在该篇文章下面留言
+ 发邮件给<find@findspace.name>
+ 微博私信或者@FindSpace博客
+ 加入侧边栏的qq群反馈

# 4.说明

+ 软件需要**root**权限 
+ 修改hosts不是万能的，只是针对性的。所以并不能完美的浏览所有的网站。
+ 用coolhosts改过之后，coolhosts就可以关闭了，除非你觉得使用出现了问题，google等不能登陆了，否则不必打开coolhosts进行更新。
+ **Play商店基本不能使用**，所以不要再问关于play的问题了。
+ 代码一直开源，地址在下面。可以清楚看到整个代码流程。并没有上传用户信息的行为，（竟然看到有些人通过这个关键词搜索进我的博客。。。）

# 5.PC下修改hosts[参考这篇博客][2]
# 6.我用了还是上不去google啊

+ 访问https://www.google.com.hk 而不是 http://www.google.com
+ 重启再试
+ 开启飞行模式然后关闭试试
+ 放弃使用hosts上谷歌

可能会有地区限制，有些ip可能在某个地区被限制。请点击初始化hosts，等待下次hosts更新。或者放弃使用hosts上google。
因为修改hosts并不能让你下载play商店的应用，下载应用仍需要挂vpn，但是此时hosts**可能**会导致下载失败，可以点击初始化hosts来还原hosts，再用vpn下载。

# 7.如果app不能正常运行

请先尝试手动修改hosts，参考文章
[安卓手动修改hosts](http://www.findspace.name/easycoding/1642)

# 8.更新日志

+ v2.0.6 2015.5.26 添加了清空hosts功能，如果出现问题，可以使用这个清空hosts
+ v2.0.7 2015.5.28 增加了查看hosts功能，同时修复了不能更新的重大bug。
+ v2.0.8 2015.5.28 增加了提示CoolHosts版本更新的功能
+ v2.0.9 2015.6.25 修改hosts文件地址到sae，增加了进度条
+ v2.1.0 2015.8.19 增加了自定义hosts功能，后续会继续优化;现在打开只获取hosts版本，节省流量。
+ v2.1.1 2015.10.18 大幅修改UI，美化界面。下一版会添加一些新功能，同时对现有功能进行优化完善。
+ v2.1.2 2015.11.16 增加从本地文件更新hosts功能
+ v2.1.3 2015.12.3 恢复了查看本地hosts内容,增加了下载更新功能,将显示hosts版本单独出来
+ v2.1.4 2016.2.17 转移源地址到更稳定自动抓取的服务器上，原服务器将在两个月后下线。

[老版本的下载地址][16]



# 9.声明
由于开放地址导致恶意抓取，耗费流量巨大，而开启防火墙又会导致coolhosts工具不可使用，所以在2.0.9开始修改hosts地址到sae，并设置sae的防火墙规则如下：

>单IP访问频率：
20次/5分钟之内
200次/24小时之内

正常用户不会在5分钟之内访问10次hosts的地址，也不会一天访问100次，所以不会影响正常用户的使用。而我之前已经说过了，建议以30min每次抓取，但是仍发现了大量每分钟抓取一次的记录，所以只好将hosts搬到sae。


# 10.代码开源
该用具最一开始是参考的autohosts的代码，后来我重写了一遍，代码遵守**GPL**协议，对源代码的任何修改同样需要开源！
##[github地址][0]

服务器端的代码目前不考虑开源。代码托管在了SAE上，SAE注册链接：
>「新浪云福利」1000云豆免费领！低成本、免运维、灵活、安全稳定，轻松应对业务爆发式增长，一起来用吧！
注册地址：http://t.cn/R4jC9Bz

对于没有root的用户，可以考虑**[刷路由器成openwrt系列的包，来修改hosts翻墙][1]**，这样只要连上路由器的设备默认可以翻墙。

#捐助
扫码（捐助是软件更新的动力～～～请在捐助的同时发送想要显示的名称消息，以便加入[捐赠列表][15]，否则隐去名，只显示姓）

[0]: https://github.com/FindHao/CoolHosts
[2]: http://www.findspace.name/res/72 "修改hosts使用google服务"
[3]: http://www.findspace.name/wp-content/uploads/2015/11/2.jpg
[13]: http://pan.baidu.com/s/1dDI2eMP "Coolhosts百度网盘"
[14]: http://www.findspace.name/wp-content/uploads/2015/06/alipayDonate.jpg 
[15]: http://www.findspace.name/donate
[16]: http://pan.baidu.com/s/1eQjoonS "老版本"
[17]: http://openbox.mobilem.360.cn/index/d/sid/2483628
[18]: http://a.app.qq.com/o/simple.jsp?pkgname=com.find.coolhosts
[1]: http://www.findspace.name/tag/openwrt
