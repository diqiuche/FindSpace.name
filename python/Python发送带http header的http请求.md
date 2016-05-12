[TOC]
# python 爬虫入门系列
##[Python爬虫学习目录](http://www.findspace.name/easycoding/1625)
#在 HTTP Request 中加入特定的 Header
要加入 header，需要使用 Request 对象：
```
#code1
import urllib2
request = urllib2.Request('http://www.baidu.com/')
request.add_header('User-Agent', 'fake-client')
response = urllib2.urlopen(request)
print response.read()
```
对有些 header 要特别留意，服务器会针对这些 header 做检查
User-Agent : 有些服务器或 Proxy 会通过该值来判断是否是浏览器发出的请求
Content-Type : 在使用 REST 接口时，服务器会检查该值，用来确定 HTTP Body 中的内容该怎样解析。常见的取值有：
application/xml ： 在 XML RPC，如 RESTful/SOAP 调用时使用
application/json ： 在 JSON RPC 调用时使用
application/x-www-form-urlencoded ： 浏览器提交 Web 表单时使用
在使用服务器提供的 RESTful 或 SOAP 服务时， Content-Type 设置错误会导致服务器拒绝服务
再给一个详细的：
```
#Code2
import urllib,urllib2

url = 'http://www.super-ping.com/ping.php?node='+node+'&ping=www.google.com'
		headers = { 'Host':'www.super-ping.com',
					'Connection':'keep-alive',
					'Cache-Control':'max-age=0',
					'Accept': 'text/html, */*; q=0.01',
					'X-Requested-With': 'XMLHttpRequest',
					'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
					'DNT':'1',
					'Referer': 'http://www.super-ping.com/?ping=www.google.com&locale=sc',
					'Accept-Encoding': 'gzip, deflate, sdch',
					'Accept-Language': 'zh-CN,zh;q=0.8,ja;q=0.6'
					}
		data = None
		req = urllib2.Request(url, data, headers)
		response = urllib2.urlopen(req)
		compressedData = response.read()
```
某些网站反感爬虫的到访，于是对爬虫一律拒绝请求
这时候我们需要伪装成浏览器，这可以通过修改http包中的header来实现。
上面的例子里面user-agent就是伪装过的。
```
'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
```
#对付"反盗链"
某些站点有所谓的反盗链设置，其实说穿了很简单，
就是检查你发送请求的header里面，referer站点是不是他自己，
所以我们只需要像把headers的referer改成该网站即可，以cnbeta为例：
```
#Code3
headers = {
    'Referer':'http://www.cnbeta.com/articles'
}
```
headers是一个dict数据结构，你可以放入任何想要的header，来做一些伪装。
例如，有些网站喜欢读取header中的X-Forwarded-For来看看人家的真实IP，可以直接把X-Forwarde-For改了。
#解压缩
在Code2中最后获取的数据是gzip压缩过的（在这个样例中返回的数据是服务器决定的），可以写进文件查看，对其进行解压缩：
```
#Code4
import gzip,StringIO

compressedData = response.read()
		compressedStream=StringIO.StringIO(compressedData)
		gzipper=gzip.GzipFile(fileobj=compressedStream)
		data=gzipper.read()
```

#Reference:
http://blog.csdn.net/pleasecallmewhy/article/details/8925978 