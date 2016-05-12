#Pre
博客用了bcs插件，这个插件有个bug就是你上传的文件或者图片会在`wp-content/uploads/`下面有备份，我估计它是先传到博客，然后再上传到bcs上。但是在引用的时候，是引用的bcs的链接。所以这些都是没用的东西。
不想手动删除，就写了个python，顺便完善了下python中的ftp使用。
##我的完整代码：
```
#!/usr/bin/env python3
#coding:utf8

from ftplib import FTP 
hostaddr=""
userName=""
passWord=""
port=21
#文件夹都是以年份和月份来命名的
rootDirRemote="/wwwroot/wp-content/uploads/2015/"
def ftpLogin():
	global ftp
	ftp=FTP()
	ftp.set_debuglevel(1)
	ftp.connect(hostaddr,port)
	ftp.login(userName,passWord)
	print(ftp.getwelcome())
	ftp.cwd(rootDirRemote)
	deleteDir(rootDirRemote)
def deleteDir(path):
	'''对于文件和文件夹的识别没有做，直接捕捉异常。。。。。'''
	try:
		ftp.cwd(path)
	except Exception:
		return
	dirList=ftp.nlst()
	if(dirList):
		for everyDir in dirList:
			deleteDir(path+"/"+everyDir)
			ftp.cwd(path)
			try:
				ftp.rmd(everyDir)
			except Exception:
				ftp.delete(everyDir)


ftpLogin()
```
#相关资料：
##Python中的ftplib模块
Python中默认安装的ftplib模块定义了FTP类，其中函数有限，可用来实现简单的ftp客户端，用于上传或下载文件
FTP的工作流程及基本操作可参考协议RFC959
ftp登陆连接
```
from ftplib import FTP #加载ftp模块
ftp=FTP() #设置变量
ftp.set_debuglevel(2) #打开调试级别2，显示详细信息
ftp.connect("IP","port") #连接的ftp sever和端口
ftp.login("user","password")#连接的用户名，密码
print ftp.getwelcome() #打印出欢迎信息
ftp.cmd("xxx/xxx") #更改远程目录
bufsize=1024 #设置的缓冲区大小
filename="filename.txt" #需要下载的文件
file_handle=open(filename,"wb").write #以写模式在本地打开文件
ftp.retrbinaly("RETR filename.txt",file_handle,bufsize) #接收服务器上文件并写入本地文件
ftp.set_debuglevel(0) #关闭调试模式
ftp.quit #退出ftp
ftp相关命令操作
ftp.cwd(pathname) #设置FTP当前操作的路径
ftp.dir() #显示目录下文件信息
ftp.nlst() #获取目录下的文件
ftp.mkd(pathname) #新建远程目录
ftp.pwd() #返回当前所在位置
ftp.rmd(dirname) #删除远程目录
ftp.delete(filename) #删除远程文件
ftp.rename(fromname, toname)#将fromname修改名称为toname。
ftp.storbinaly("STOR filename.txt",file_handel,bufsize) #上传目标文件
ftp.retrbinary("RETR filename.txt",file_handel,bufsize)#下载FTP文件
```
##官方文档：
https://docs.python.org/3/library/ftplib.html#ftplib.FTP.retrlines
##注意：
过老的服务器上ftp版本不支持mlsd命令：
http://stackoverflow.com/questions/19897458/ftp-directory-in-python-3
