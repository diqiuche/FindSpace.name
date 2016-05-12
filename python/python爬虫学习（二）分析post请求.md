#Introducation
##[Python爬虫学习目录](http://www.findspace.name/easycoding/1625)
本文将接上文详细分析post的请求和响应数据
#General
```
General

Request URL:https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml
Request Method:POST
Status Code:200 OK
Remote Address:[2400:cb00:2048:1::6810:414]:443
```
先看general一节，请求的链接，请求的方式，返回的状态，远程主机的地址。其中此处是IPV6的地址，与普通的ipv4地址不同。443端口已经表明是个https的响应。
#Response Headers
```http
cache-control:no-cache
cf-ray:27c2de2513c6192c-HKG
content-encoding:gzip
content-type:text/xml; charset=UTF-8
date:Mon, 29 Feb 2016 08:06:05 GMT
server:cloudflare-nginx
status:200 OK
version:HTTP/1.1
x-frame-options:SAMEORIGIN
```
再看Response Headers响应头，关注content-encoding，是gzip压缩的，如果使用python urllib等基础库，则需要添加解压的代码，使用requests等库则会自动处理好。
注意status是[http状态码](https://zh.wikipedia.org/wiki/HTTP%E7%8A%B6%E6%80%81%E7%A0%81)，2xx表示成功，3xx这类状态码代表需要客户端采取进一步的操作才能完成请求。通常，这些状态码用来重定向，后续的请求地址（重定向目标）在本次响应的Location域中指明。
```python
...

req = urllib2.Request(url, data, headers)
response = urllib2.urlopen(req)
compressedData = response.read()
compressedStream = StringIO.StringIO(compressedData)
gzipper = gzip.GzipFile(fileobj=compressedStream)
data = gzipper.read()
...
```
# Request Headers
```html
:host:www.gebiz.gov.sg
:method:POST
:path:/ptn/opportunity/BOListing.xhtml
:scheme:https
:version:HTTP/1.1
accept:*/*
accept-encoding:gzip, deflate
accept-language:en-US,en;q=0.8,zh-CN;q=0.6,zh;q=0.4
content-length:487
content-type:application/x-www-form-urlencoded;charset=UTF-8
cookie:__cfduid=d3945804a6f00d7cb1bb79047fd1f1e101456553632; BIGipServerPTN2_PRD_Pool=18964640.47873.0000; wlsessionid=anYr9cztb9uglt1TPdwwNV5Awq3w-DTYbU0c_KFQOn8YSG_xbkpY!1656571856
dnt:1
faces-request:partial/ajax
origin:https://www.gebiz.gov.sg
referer:https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml
user-agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36
```
在这个头中其实是有些奇怪的，一些字段前面有‘：’，而且如果在代码中，相应字段也加上冒号会报错。所以代码中自定义headers的时候需要按照标准格式写。
注意有这样一个字段：
```html
faces-request:partial/ajax
```
表明发起的是ajax请求。通常在伪造头文件的时候需要伪造的信息请参看[Python发送带http header的http请求](http://www.findspace.name/easycoding/1137)。
注意有些网站并不需要cookies字段，也没有查阅cookies字段，然而，我们的样例网站则需要。
# Form Data
```html
contentForm:contentForm
contentForm:j_idt54_listButton2_HIDDEN-INPUT:
contentForm:j_idt61_searchBar_INPUT-SEARCH:
contentForm:j_idt203_select:0
javax.faces.ViewState:-2381311688370570247:-4405473631519789197
javax.faces.source:contentForm:j_idt260_2_2
javax.faces.partial.event:click
javax.faces.partial.execute:contentForm:j_idt260_2_2 contentForm:j_idt260
javax.faces.partial.render:contentForm:j_idt209
javax.faces.behavior.event:action
javax.faces.partial.ajax:true
```
这个formdata则是post中需要带上的数据，如果是登录网站，一般都是用户名和密码，可能外加一些验证数据。
在网页上审查元素，可以看到，这里的`j_idt260_2_2`就是第二页数据，`3_3`是第三页数据。而切换到Elements标签页，然后点击网页里查看第二页，会发现改变的是
```
<div id="contentForm:j_idt209">
```
主要是这个div。也就是下面的列表数据。分析这个网站的div命名格式，都是`contentForm:j_idt***`这样的规律，因此从这里的form data请求里数据就比较明显了。这里有个关键的字段：
```html
javax.faces.ViewState:-2381311688370570247:-4405473631519789197
```
找了半天没高明白是个什么东西，理解认为应该是随着session生成的一个类似验证码。而且后面这个也有很重要的作用，在post的时候，如果这个数据不对，那么就抓不到正确的数据。