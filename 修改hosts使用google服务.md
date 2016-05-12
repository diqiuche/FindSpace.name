# 安卓用[CoolHosts][2]（点击移步介绍和下载）
# 1.修改hosts方法的原理
>hosts说明：来自百度百科     
让我们来看看Hosts在Windows中是怎么工作的。
我们知道在网络上访问网站，要首先通过DNS服务器把要访问的网络域名解析成XXX.XXX.XXX.XXX的IP地址后，计算机才能对这个网络域名作访问。      
要是对于每个域名请求我们都要等待域名服务器解析后返回IP信息，这样访问网络的效率就会降低，因为DNS做域名解析和返回IP都需要时间。      
为了提高对经常访问的网络域名的解析效率，可以通过利用Hosts文件中建立域名和IP的映射关系来达到目的。根据Windows系统规定，在进行DNS请求以前，Windows系统会先检查自己的Hosts文件中是否有这个网络域名映射关系。如果有，则调用这个IP地址映射，如果没有，再向已知的DNS服务器提出域名解析。也就是说Hosts的请求级别比DNS高。

# 2.修改方法：
如果之前你有自定义的hosts条目，可以在原hosts文件后面粘贴，如果没有，直接覆盖即可

+ win用户覆盖   **C:\windows\system32\drivers\etc** 下面的hosts
+ Mac&&linux用户覆盖    **/etc/hosts**

win下用户：
直接修改 可能没法保存的，两个方法：
1 直接把下载的hosts文件复制粘贴覆盖掉原来的文件。
2 参考这个百度经验修改hosts文件的权限
http://jingyan.baidu.com/article/b24f6c8223feb486bee5da48.html
# 3.新增问题
近来ip很多都不能用了，但实际上，需要访问的不是`http://www.google.com`，而是`https://www.google.com.hk`，http的几乎没法用了，访问https开头的，主域名也经常不行，所以访问地域性的域名。
强制浏览器的一些域名使用https访问的插件：
##[https everywhere](https://www.eff.org/https-everywhere)
支持chrome，firefox，opera
##注意
这个插件会导致国内一些主要网站无法跳转，比如淘宝或者微博在登陆的时候，跳不到主页，但是可以跳不过去的时候，点击这个插件，关闭`enable taobao.com`类似这样的设置，取消打钩即可。

# [Hosts单击下载][0]
请注意，地址修改了，原来的文件不再维护

[adblockingdetector id="56c46fdd3e5fe"]
# 4.PS:
>* 如果linux出现了不能解析主机XXX的问题，比如不能解析Find-Ubuntu，那么在hosts中手动加上
```127.0.0.1 Find-Ubuntu```
>* win下如果不行的话，记得看下下面的回复。刷新dns，清除浏览器缓存。

# 5.请不要翻墙发表不当言论，这里提供的主要是学术功能
+ 我希望我的祖国更加：富强民主文明和谐。
+ 如果遇到不良信息，请到12377举报，会有奖励。我中过。

# 6.其他方法1
对于没有root的手机或者越狱的pad，可以参考**[刷openwrt系列的文章][6]**把自己的路由器刷成openwrt之类的rom，然后修改路由器上的hosts，这样只要连上路由器，所有的终端默认可以翻墙。
# 7.其他方法2
动手能力强的人可以自己买vps搭一个Shadowsocks来用，国外低价的vps很多.
教程：
##[SHADOWSOCKS科学上网][5]

#捐助
请到**[捐助列表][15]**查看详细信息

[0]: http://googlehosts-hostsfiles.stor.sinaapp.com/hosts "单击下载"
[1]: http://www.findspace.name/res/528 "戳我"
[2]: http://www.findspace.name/easycoding/503 "一键hosts（CoolHosts）-安卓应用"
[3]: http://www.findspace.name/res/956 "Shadowsocks"
[4]: http://bcs.duapp.com/findspace//blog/201505//cute.jpg
[5]: http://www.findspace.name/res/956 "shadowsocks"
[15]: http://www.findspace.name/donate
[16]: http://findspace.name/adds/google.php 
[7]: http://googleips-google.stor.sinaapp.com/hosts.ipv6
[6]: http://www.findspace.name/tag/openwrt