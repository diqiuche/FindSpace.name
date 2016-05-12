[TOC]
#![Sae新浪云计算平台][4]

对开发者比较友好。最近我用python用的比较多，记录下一些使用的方法。sae上用的web框架是

+ python Tornado

#![][5]Cron服务
## 在config.yaml文件里添加：
```
cron:
- description: checkUpdate
	url: /update
	schedule: "*/30 * * * *"
```
cron文件的详细配置说明查看[sae的文档即可][1]

##设置对应的访问url
在urls.py里添加：
```
urls=[
#其他的省略
(r"/update",code.UpdateHandler),
]
```
##在处理请求的py里对请求处理
从urls.py的配置中可以看出，我们把update的请求给了code.py的UpdateHandler类，
```
class UpdateHandler(tornado.web.RequestHandler):
	"""docstring for UpdateHandler"""
	def get(self):
		googleips=google.mainProcess()
		for i in googleips:
			self.write(i+"<br>I'm Enter")
```
##查看sae的日志
进入项目管理的页面，左边：**安全与运维-->日志中心-->cron**可以看到运行的日志
#![][6]Mail服务
sae的邮件服务仅支持SMTP
服务限制对于个人使用足够了，[Python的API][2]也非常简单：
**注意要提前开启Mail服务，从服务管理-->Mail那里**
##API使用手册
*class* ** sae.mail.EmailMessage**(**kwargs)
EmailMessage类
参数同下面的initialize

**initialize**(***kwargs*)
初始化邮件的内容。

>参数:	
>>+ to – 收件人，收件人邮件地址或者收件人邮件地址的列表。
+ subject – 邮件的标题。
+ body/html – 邮件正文。如果内容为纯文本，使用body，如果是html则使用html。
+ smtp – smtp服务器的信息。是一个包含5个元素的tuple。(smtp主机，smtp端口， 邮件地址或用户名，密码，是否启用TLS）。
+ from_addr – 可选。发件人，邮件的from字段，默认使用smtp的配置信息。
+ attachments – 可选。邮件的附件，必须为一个list，list里每个元素为一个tuple，tuple的第一个元素为文件名，第二个元素为文件的内容。

**send**()
提交邮件发送请求至后端服务器。

**\__setattr__(attr, value)**
>参数: 
>>+ attr – 属性名。
+ value – 属性的值。
+ sae.mail.send_mail(to, subject, body, smtp, **kwargs)
快速发送邮件。

字段的意义同EmailMessage.initialize()。

使用示例
快速发送一份邮件
```
from sae.mail import send_mail

send_mail("katherine@vampire.com", "invite", "to tonight's party",
          ("smtp.vampire.com", 25, "damon@vampire.com", "password", False))
```
发送邮件给多个收件人
```
to = ["katherine@vampire.com", 'rebecca@vampire.com', 'elena@vampire.com']
send_mail(to, "invite", "to tonight's party",
          ("smtp.vampire.com", 25, "damon@vampire.com", "password", False))
```
发送一封html格式的邮件
```
from sae.mail import EmailMessage

m = EmailMessage()
m.to = 'damon@vampire.com'
m.subject = 'Re: inivte'
m.html = '<b>my pleasure!</b>'
m.smtp = ('smtp.vampire.com', 25, 'katherine@vampire.com', 'password', False)
m.send()
```
使用Gmail SMTP
```
import sae.mail

sae.mail.send_mail(to, subject, body,
        ('smtp.gmail.com', 587, from, passwd, True))
```
#![][7]Storage
##
不管是sae还是bae，不管是python还是php，对文件都没有写权限，只有读权限，很明显是为了安全问题，所以它们都提供了storage服务
>Storage是SAE为开发者提供的分布式文件存储服务，用来存放用户的持久化存储的文件。用户需要先在在线管理平台创建Domain（相当于一级子目录），创建完毕后，即可进行文件的管理。
>Python的API文档，非常详细，但是但是没有给出可线上用的例子


其实storage都已经封装的很好了，我单独写了一个py来处理读写的问题：
```
#coding:utf8
from sae.storage import Bucket, Connection

#读取storagete中的basehosts
def readGoogleIPs():
	c=Connection(account='googleips')
	bucket=c.get_bucket('google')
	baseHosts=bucket.get_object_contents("ips")
	return baseHosts.split('\n')
	# for i in range(len(baseHosts)):
	# 	hosts.append(urls[0]+'  '+baseHosts[i]+"\n")
	# bucket.put_object('hosts',''.join(hosts))
	# return ''.join(hosts)
#把可行的ip都写文件：ips
def writeGoogleIPs(urls):
	c=Connection(account='googleips')
	bucket=c.get_bucket('google')
	ips=[]
	for i in range(len(urls)):
		ips.append(urls[i]+'\n')
	bucket.put_object('ips',''.join(ips))
	return 1
```
关键部分：
```
	c=Connection(account='googleips')
	bucket=c.get_bucket('google')
	baseHosts=bucket.get_object_contents("ips")
```
第一个`account='googleips'`是appname，第二个`get_bucket('google')`是建立的storage表的名字，注意使用这项服务之前要现在应用的后台开启storage服务。
至于文件的读取方法和写入方法，都可以在[API文档][3]里找到类说明


##

[1]: http://sae.sina.com.cn/doc/python/cron.html "Cron"
[2]: http://sae.sina.com.cn/doc/python/mail.html "SAE Mail"
[3]: http://sae.sina.com.cn/doc/python/storage.html "Python Storage API"
[4]: http://sae.sina.com.cn/static/image/home/logo.png "sae logo"
[5]: http://www.haotu.net/up/2836/64/timer.png "cron服务图标"
[6]: http://img.informer.com/icons/png/48/3308/3308475.png "Mail"
[7]: http://www.sinaimg.cn/download/down_contents/1224345600/U74P176T43D41280F1087DT20080923095013.gif "Storage"