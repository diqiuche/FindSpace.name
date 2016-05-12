#声明：
**由于飞信更改了网页飞信的登录方式，必须使用短信验证码登录。导致该工具已经失效。**
#1.飞信开放API
这是一个民非官方的飞信开放API
我的个人主页 http://www.findspace.name
我的github地址 https://github.com/Findxiaoxun
该项目在开源中国git的地址
https://git.oschina.net/findspace/FetionAPI

#2.教程
##数据格式约定

```
user:  你的手机号
key：密码
number：目的手机号
text：要发送的信息
```

##Get方式
直接get地址
```
http://openfetionapi.sinaapp.com/fetion.php?&user=10086&key=10086&number=10010&text=hello,China Unicom
```
其中，user等参数替换为自己的信息
##Post
目的地址：
```
http://openfetionapi.sinaapp.com/fetion.php
```
##样例
包含java，php，python的示例：
https://git.oschina.net/findspace/FetionAPI
#3.说明
原作者博客：http://blog.quanhz.com/
PHPFetion.php是原作者提供的主要核心接口
实现原理利用socket模拟登录wap版飞信，并模拟发送飞信，好处是不会有验证码，也比较稳定。