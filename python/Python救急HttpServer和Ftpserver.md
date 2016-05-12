#python救急的HttpServer
python有个`-m`参数可以运行一些现有的内置的模块，当然模块可以自己`pip install module_name`安装。
这里就有个模块可以提供非常简易的HttpServer:
```
python -m SimpleHTTPServer 8000
```
这是python2的，python3中该模块移到了`http.server`里，所以命令变成了：
```
python3 -m http.server 8000
```
当然需要先cd到要建立的服务器目录下，比如`cd ~`，那么我的用户文件夹就作为了server的资源。默认这个server是显示目录列表，如果你的目录下有`index.html`，那么就会显示这个网页。命令最后的端口可以手动指定，默认是8000。
在我的使用中，这个最常用的场景就是局域网之间传文件。linux党和win党之间传文件真是。。。
考虑到U盘的写大文件速度一般在2M/s左右，偶尔还会崩，不足1M，而局域网是比较稳定的1M多（当然看具体的环境）。所以比优盘省时，还省去了插拔u盘的时间。
场景二就是虚拟机和host，有时候vmtoos或者vboxtools，并没有想象中那么成功的安装，那么这个简单的命令就很必要了。
而且一般python都一定内置了。
关于这个SimpleHTTPServer的源代码，请移步：
[Python Simple httpserver][0]
[官方简单文档][1]

##多线程版本
[python多线程启动httpserver](http://www.findspace.name/easycoding/1692)

#python 简易ftpserver

Python没有内置一个直接可以用的FTP服务器，所以需要第三方组件的支持，我找到的这个组件叫pyftpdlib，首先安装：
```
pip install pyftpdlib
```
安装完后，和HTTP服器类似，执行以下命令就可以启动一个FTP服务器了：
```
python -m pyftpdlib -p 21
```
后面的21端口依然是可选的，不填会随机一个，被占用的端口将跳过。
是匿名访问，也就是用户名是**anonymous**，密码为空
它可用的命令可以开终端输入ftp，然后输入help来获取，主要有如下：
```
Commands may be abbreviated.  Commands are:

!	dir	mdelete	qc	site $	disconnect	mdir	sendport	size
account	exit	mget	put	status append	form	mkdir	pwd	struct
ascii	get	mls	quit	system bell	glob	mode	quote	sunique
binary	hash	modtime	recv	tenex bye	help	mput	reget	tick
case	idle	newer	rstatus	trace cd	image	nmap	rhelp	type
cdup	ipany	nlist	rename	user chmod	ipv4	ntrans	reset	umask
close	ipv6	open	restart	verbose cr	lcd	prompt	rmdir	?
delete	ls	passive	runique debug	macdef	proxy	send

```

[0]: https://hg.python.org/cpython/file/2.7/Lib/BaseHTTPServer.py "Python Simple httpserver"
[1]: https://docs.python.org/2/library/simplehttpserver.html "简单文档"