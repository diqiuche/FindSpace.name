#Pre
写一个简易的聊天室，Python写，界面的。因为比较要的急，所以选用了内置的最简单的Tkinter。接下来的一系列文章会一步一步走出来，跟着这个教程，大概十几个小时，最后你也会写出了一个简易的聊天室
#需求
根据描述，这个不应该写成聊天室，应该写成通知工具。。。
需要具有 登录、注册功能，只允许管理员说话，其他用户只需要接收数据，在登录以后，可以接收到离线的时候，管理员发的信息。（当然聊天室在此处就是把判断管理员的部分去掉即可）
客户端要求在win上运行。
图片功能迫于时间没有做上去。不过最后也会写一篇博客来说明如何传输图片。
我是基于纯纯的socket做的，没有用框架。。
#代码
欢迎fork和star
##[github地址:ChatGroup][0]
##[gitOSC地址：ChatGroup][1]
#面向对象
假定读者了解简单的python语法（list，字典，文件读取等等），已经做过简单的socket编程（非python语言），写过简单的界面（java语言等），了解简单的多线程编程
在开发过程使用python3，可能并不能直接在python2上运行
#目录
##[Python3 Tkinter简易聊天室(一)初步认识socket](http://www.findspace.name/easycoding/1509)
##[Python3 Tkinter简易聊天室(二)版本0.0.1简陋版server](http://www.findspace.name/easycoding/1522)

[0]: https://github.com/FindHao/ChatGroup
[1]: http://git.oschina.net/findspace/ChatGroup