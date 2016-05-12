# Introducation 
本文原来介绍的是偶Hosts1Plus这个美国便宜的vps，使用半年以后，发现该款vps实际上是非常差的，而且算起来性价比并不高。先改为有名的搬瓦工（bandwagon）vps的介绍说明。

# 优势说明
现在搬瓦工最便宜的也是19.99$一年了。合租的话性价比会非常高。
```
Self-managed service
SSD: 10 GB
RAM: 512 MB
CPU: 1x Intel Xeon
BW: 1000 GB/mo
Link speed: 1 Gigabit

VPS technology: OpenVZ/KiwiVM
Linux OS: 32-bit and 64-bit Centos, Debian, Ubuntu, Fedora
Instant OS reload
1 Dedicated IPv4 address
Full root access
PPP and VPN support (tun/tap)
Instant RDNS update from control panel
No contract, anytime cancellation
Strictly self-managed, no support
```
通常有优惠码，可以在付款的时候找优惠码，现在最大的优惠额度就是4.8%了，聊胜于无。
通常2-4人合租最好，流量每月1000GB足够了。而且支持IPV6,如果教育网的话，可以搭ss翻墙，支持ipv6,速度非常快，看youtube 1080p的视频也不卡。
# 注册链接
[搬瓦工](https://bandwagonhost.com/aff.php?aff=4005)
#支持支付宝付款
# 技术支持
它的后台控制也很强大，而且如果设置系统是centos6,还可以一键安装vpn或者shadowsocks。

# 购买
![bandwagon][13]
通过上面的注册链接注册登录以后，点击`client area-->services -->order new services` 一般最上面那个就是默认推荐的配置，看下配置意见右边的价格，$19.99 USD Annually，年付19.99刀。
购买过程很简单，不再赘述，


#事实证明Host1plus这是个坑货
现在ip显示的是智利的。而且速度200k真的太慢了，延迟还是比较高的。挂挂vpn之类的吧。。

下面的不要看了。。

---

推荐一款非常便宜的VPS，可以支付宝支付。这里给出比较详细的购买和使用步骤,力求让小白也能操作。
**由于当时写博客的时候，host1plus正在更新新的后台管理UI，当时还没有支持中文，现在已经全面支持中文了，所以购买更加方便～**
**我会在评论里不定期更新优惠码～**

#购买
##选择配置

点击上面的图片进入（不然不能保证优惠码有效哦），进入选择配置的界面：
![][0]
默认都是最小的，是Bronze型号。每月2.5美元,**强烈建议一次买两个月以上**，网速是50Mbps,实测很快，但是由于在美国，必不可免地被卡，ping190ms。体验一般般，看youtube网速在100kb/s左右，高清的就比较卡，如果你想下载youtube的视频可以看看这个[youtube视频下载工具][1]。
关于和搬瓦工（另外一个更便宜的vps）相比，个人的对比：
搬瓦工国内用的人很多，经常出现ip被封，尽管可以到后台更换，但是让人感觉不稳定。还一个搬瓦工也非常便宜，但是它4刀或9.99刀每年的已经没有再卖了，它最便宜的也成了19.99,和这个差不了几块人民币了。而且这个还经常有优惠码，我会不断在评论里更新。
**毕竟一分钱一分货**

如果觉得贵，建议几个人合租，每月有500G流量，我一般就用十几G，因为上面没有搭站，只用来翻，和小伙伴合租的。


选择账单周期，默认一个月，你也可以按三个月或者半年付一次，会省一点。**强烈建议一次买两个月以上**。
操作系统建议下拉选择Ubuntu14.04-x86。

选择完成后点击继续。
##用户信息
这里会进入英文页面
要填写的内容也很简单，我在图片里都已经填了中文注释。不再赘述。
![][2]
##确认订单
单击have a PROMO Code，优惠码，输入该文章下面评论里的优惠码，但是只能用一个。优惠码会不定期更新的。

看下订单没有问题之后，点击Next step:Payment
![][3]
##支付
看到第三个了没有，哈哈，alipay支付宝。点击Place Your Order，会自动跳转到支付宝支付界面。支付宝支付就不再说了，你要是不会用，还是放弃这个吧。
第一次注册需要验证电话号码，会收到来自美国的语音电话，就是中文说的几个数字，记得记下来，然后输入就行。
![][4]
##登录
支付成功以后，就可以登录了。
打开[Host1Plus][5],默认英文界面，点击右上角Log In来登录，在弹出的窗口中，输入用户名和密码然后点击login &Continue按钮登录。
##后台
![][6]
后台就是这个样子的，这里我们只关注我们用到的。主界面的Services就是你买的vps，我的名字叫findspace1。点击它进入详情页面。
##vps详情
![][7]
图片有注释，不再赘述。说明一点，默认的用户名是root，密码点击show按钮会显示，默认是一个随机密码，在后面可以修改。
#登录
windows下载[Putty(点击即可下载)][8]
然后解压出来，打开
![][9]
在输入框填VPS的IP地址（如图），别的不用动，然后点击Open
弹出一个对话框，如果在自己的电脑上，选择yes，如果选择no，每次都会弹出这个提示。
![][10]
然后提示你log in:输入root，然后回车，输入密码（这里可以直接在网页里复制，单击鼠标右键自动粘贴，这里输入密码是不显示的，\*nix的风格）
登录后是这样的：
![][11]
输入
```
passwd
```
输入两次新的密码来修改用户密码，建议不要修改，因为默认的密码强度很高，而且一般不需要登录后台管理。

#配置Shadowsocks
见博客：
##[SHADOWSOCKS科学上网][12]

[0]: http://www.findspace.name/wp-content/uploads/2015/07/choose.png
[1]: http://youtube.findspace.name
[2]: http://www.findspace.name/wp-content/uploads/2015/07/billingInfor.png
[3]: http://www.findspace.name/wp-content/uploads/2015/07/confirmOrder.png
[4]: http://www.findspace.name/wp-content/uploads/2015/07/pay.png
[5]: http://host1plus.com
[6]: http://www.findspace.name/wp-content/uploads/2015/07/portoal.png
[7]: http://www.findspace.name/wp-content/uploads/2015/07/ControlPanel.png
[8]: http://www.findspace.name/wp-content/uploads/2015/07/putty.zip
[9]: http://www.findspace.name/wp-content/uploads/2015/07/putty.png
[10]: http://www.findspace.name/wp-content/uploads/2015/07/puttynotify.png
[11]: http://www.findspace.name/wp-content/uploads/2015/07/puttylog.jpg
[12]: http://www.findspace.name/res/956 
[13]: http://www.findspace.name/wp-content/uploads/2016/03/bandwagon.jpg