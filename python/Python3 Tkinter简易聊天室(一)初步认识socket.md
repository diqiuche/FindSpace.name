[TOC]
#规划
首先根据需求，在正式了解python相关内容之前，根据自己的开发经验（我Java，C居多），大致我们做以下规划,

Server:

+ socketServer：多线程监听端口，并保存连接的客户端，在有客户端掉线时，及时剔除
+ 可以读取和保存用户信息，用户单独出一个类
+ 可以保存和读取聊天记录

Client：

+ 发送登录和注册信息
+ 接收server发过来的聊天记录
+ 管理员登录的时候还可以发送消息过去
+ 由于需求规定，需要在win下运行

#初步了解PythonSocket编程
这里跳过大量使用搜索引擎搜索的过程，直接说明结果吧。
socket server多线程编程根据以往的经验并不是一个可以快速开发的东西。现在搜索到了select这个函数，[这里是官网手册][1]，
```
This module provides access to the select() and poll() functions available in most operating systems, devpoll() available on Solaris and derivatives, epoll() available on Linux 2.5+ and kqueue() available on most BSD. Note that on Windows, it only works for sockets; on other operating systems, it also works for other file types (in particular, on Unix, it works on pipes). It cannot be used on regular files to determine whether a file has grown since it was last read.
```
在win下，这个函数只能用于socket。
[阅读IBM这篇教程][0]会发现socket编程都很类似，下面通过跟随这篇教程用简单的例子来说明用法
#Python Sockets模块
##基础的sockets模块
python提供了两个基础的sockets模块：Socket，提供了标准的BSD Sockets接口;SocketServer提供了服务器类来简化服务器上的开发，异步，你可以自己添加很多模块在上面。
##Socekt模块
这个模块有你用来搭建服务器和客户端需要的所有的东西。实际上，在本次开发中，只用它来写server和client。
Socket 模块提供了 UNIX 程序员所熟悉的基本网络服务（也称为 BSD API）。这个 API 与标准的 C API 之间的区别在于它是面向对象的。在C中，socket描述符是从socket调用中获得的，然后会作为一个参数传递给BSD API函数。在Python中，socket 方法会向应用socket方法的对象返回一个socket对象。
以下函数均可以在[官方socket文档][2]里找到更详细的说明

|类方法名|描述|
|-|-|
|socekt.socekt(family,type)|创建并返回一个新的socekt对象|
|socekt.getfqdn(name)|将使用点号分隔的 IP 地址字符串转换成一个完整的域名|
|socket.gethostbyname(hostname)|将主机名解析为一个使用点号分隔的 IP 地址字符串|
|socket.fromfd(fd, family, type)|从现有的文件描述符创建一个 socket 对象|

|实例方法|说明|
|sock.bind( (adrs, port) )|将 socket 绑定到一个地址和端口上，注意有两层括号，bind传入的是一个元组|
|sock.accept()|返回一个客户机 socket（带有客户机端的地址信息）|
|sock.listen(backlog)|将 socket 设置成监听模式，能够监听 backlog 外来的连接请求|
|sock.connect( (adrs, port) )|将 socket 连接到定义的主机和端口上|
|sock.recv( buflen[, flags] )|从 socket 中接收数据，最多 buflen 个字符|
|sock.recvfrom( buflen[, flags] )|从 socket 中接收数据，最多 buflen 个字符，同时返回数据来源的远程主机和端口号|
|sock.send( data[, flags] )|通过 socket 发送数据|
|sock.sendto( data[, flags], addr )|通过 socket 发送数据|
|sock.close()|关闭 socket|
|sock.getsockopt( lvl, optname )|获得指定 socket 选项的值|
|sock.setsockopt( lvl, optname, val )|设置指定 socket 选项的值|

类方法和实例方法之间的区别在于，实例方法需要有一个socket实例（从socket返回）才能执行，而类方法 则不需要。
##SocketServer模块
SockerServer模块是python封装好了的socket服务器。下面演示了一个简单的helloworld服务器，凡是连接上的，都会自动回复一个helloworld.
首先，类hwRequestHandler继承自`SocketServer.StreamRequestHandler`类，并定义了handle函数来处理客户端的连接请求。接下来就是通过`SocketServer.TCPServer`建立服务器的监听，当然需要传入服务器的ip地址和要监听的端口，调用`serve_forever`函数来启动这个服务器。
但是在本系列文章中，我们不采用这个方法，还是利用前面的socket模块来做。
```
import SocketServer

class hwRequestHandler( SocketServer.StreamRequestHandler ):
  def handle( self ):
    self.wfile.write("Hello World!\n")


server = SocketServer.TCPServer( ("", 2525), hwRequestHandler )
server.serve_forever()
```

#Reference
[IBM Sockets programming in Python][0]
#更多请查看

[0]: http://www.ibm.com/developerworks/linux/tutorials/l-pysocks/
[1]: https://docs.python.org/3/library/select.html
[2]: https://docs.python.org/3/library/socket.html