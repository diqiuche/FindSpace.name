#Introducation
##[Python爬虫学习目录](http://www.findspace.name/easycoding/1625)
本文简单说明了网站的反爬虫机制cookie，以及session。
# cookie
关于cookie的介绍，参看[wiki-cookie](https://zh.wikipedia.org/wiki/Cookie),（上不去维基？别说你没看到我博客左侧和置顶的修改hosts使用谷歌服务的链接，其实那个hosts里还经常有twitter的映射）
在上一节中，就是利用浏览器上已经登录的有效的cookie和特殊的`javax.faces.ViewState`值进行的实验环境下的抓取页面。而实际上，这个网站用了一些手段来防止简单的爬虫抓取，浏览器长时间停在页面无操作会弹出这样的提示框，
![session expire](http://www.findspace.name/wp-content/uploads/2016/02/gebiz_session_expire.png)
提示你session已过期。如果网站没有检测session的过期，那么可以利用一个cookie的有效期保持长时间的抓取可用状态。
通过resource可以看到cookie相关的部分，有个超时的时间字段，这就是它的有效期。
![cookie有效期](http://www.findspace.name/wp-content/uploads/2016/02/gebiz_session_resources.png)

# 从响应中获取cookie
>requests已经封装好了很多操作，自动管理cookie, session保持连接,抓取数据后结束

那么我们就可以先访问该站的某个页，建立了session连接之后，获取cookie，再伪造头进行访问。
```python
>>> from bs4Test import *
>>> s = requests.session()
>>> s.get("https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml?origin=menu")
>>> print(s.cookies)
# 下面是打印结果
<RequestsCookieJar[<Cookie __cfduid=d18b8067e8b19399aeb04f93f8f7fd5f81456743568 for .gebiz.gov.sg/>, <Cookie BIGipServerPTN2_PRD_Pool=52519072.47873.0000 for www.gebiz.gov.sg/>, <Cookie wlsessionid=jgAsrtUaMpsz9zrTPYxz3IYG1V1NN6G1tJWd-_hPnEFPGll5eNpS!1863425311 for www.gebiz.gov.sg/>]>
```
最后拼接cookie串
```
cook_value = ''
for x in cook:
    cook_value += x.name + '=' + x.value + ';'
cook_value = cook_value[:len(cook_value)-1]
print(cook_value)
#打印结果
__cfduid=d9ed16845e45ce7496268e8b2293dadc81456745242;BIGipServerPTN2_PRD_Pool=18964640.47873.0000;wlsessionid=nUIsyGBSLqjakq4P5dEDh4TNUJBYtw4nIpxkyITzrj2A5CalOWZ9!-936114045

```
则此时我们已经成功获取到了cookie值。整理代码即可成功使用cookie抓取
[0]: http://zhihu.com/question/28168585/answer/74840535 "如何应对网站反爬虫策略？如何高效地爬大量数据?"