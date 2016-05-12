#命令行的消息发送与接收
先做一个简单的命令行的消息发送和接收。下面是服务器端
#Server Code
```
import socket

srvsock = socket.socket( socket.AF_INET, socket.SOCK_STREAM )
srvsock.bind( ('', 23000) )
srvsock.listen( 5 )

while 1:

  clisock, (remhost, remport) = srvsock.accept()
  str = clisock.recv(100)
  clisock.send( str )
  clisock.close()
```
##socket函数
```
socket.socket(family=AF_INET, type=SOCK_STREAM, proto=0, fileno=None)
Create a new socket using the given address family, socket type and protocol number. The address family should be AF_INET (the default), AF_INET6, AF_UNIX, AF_CAN or AF_RDS. The socket type should be SOCK_STREAM (the default), SOCK_DGRAM, SOCK_RAW or perhaps one of the other SOCK_ constants. The protocol number is usually zero and may be omitted or in the case where the address family is AF_CAN the protocol should be one of CAN_RAW or CAN_BCM. If fileno is specified, the other arguments are ignored, causing the socket with the specified file descriptor to return. Unlike socket.fromfd(), fileno will return the same socket and not a duplicate. This may help close a detached socket using socket.close().
The newly created socket is non-inheritable.

socket(family,type[,protocal]) 
```
使用给定的地址族、套接字类型、协议编号（默认为0）来创建套接字。

|socket类型|描述|
|-|-|
|socket.AF_UNIX|只能够用于单一的Unix系统进程间通信|
|socket.AF_INET|服务器之间网络通信|
|socket.AF_INET6|IPv6|
|socket.SOCK_STREAM|流式socket , for TCP|
|socket.SOCK_DGRAM|数据报式socket , for UDP|
|socket.SOCK_RAW|原始套接字，普通的套接字无法处理ICMP、IGMP等网络报文，而SOCK_RAW可以；其次，SOCK_RAW也可以处理特殊的IPv4报文；此外，利用原始套接字，可以通过IP_HDRINCL套接字选项由用户构造IP头。|
|socket.SOCK_SEQPACKET|可靠的连续数据包服务|
|创建TCP Socket：|s=socket.socket(socket.AF_INET,socket.SOCK_STREAM)|
|创建UDP Socket：|s=socket.socket(socket.AF_INET,socket.SOCK_DGRAM)|
##bind函数
绑定套接字到本地端口
```
socket.bind(address)
Bind the socket to address. The socket must not already be bound. (The format of address depends on the address family — see above.)
```
对于网络类型是`AF_INET`的，address就是一个包含两个元素的元组`(host,port)`
a (address, port, flow info, scope id) 4-tuple for AF_INET6.
注意，是一个**元组**，也就是说，传入的是带括号的，举例：
```
srvsock.bind( ('', 23000) )
```
##listen()
```
socket.listen([backlog])
Enable a server to accept connections. If backlog is specified, it must be at least 0 (if it is lower, it is set to 0); it specifies the number of unaccepted connections that the system will allow before refusing new connections. If not specified, a default reasonable value is chosen.
```
backlog指定最多允许多少个客户连接到服务器。它的值至少为1。收到连接请求后，这些请求需要排队，如果队列满，就拒绝请求。
##socket()
```
socket.accept()
Accept a connection. The socket must be bound to an address and listening for connections. The return value is a pair (conn, address) where conn is a new socket object usable to send and receive data on the connection, and address is the address bound to the socket on the other end of the connection.

The newly created socket is non-inheritable.
```
文档里已经说的很清楚了，返回值是(conn,address)，conn是socket类型，adress的格式就是前面说的，根据你定义的网络类型。如样例代码中的`clisock, (remhost, remport) = srvsock.accept()`
以后就可以调用conn来对这个连接操作。


#Reference
[python socket][0]
[python docs about socket][1]
[简单分析一下socket中的bind][2]
[0]: http://yangrong.blog.51cto.com/6945369/1339593
[1]: https://docs.python.org/3/library/socket.html?highlight=bind#socket.socket.bind
[2]: http://www.cnblogs.com/nightwatcher/archive/2011/07/03/2096717.html