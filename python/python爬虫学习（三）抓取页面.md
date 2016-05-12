#Introducation
##[Python爬虫学习目录](http://www.findspace.name/easycoding/1625)
本文接上节介绍抓取页面。根据上节说明的，则可直接伪造一个request headers和form data。此处的处理有技巧，可从浏览器复制该部分然后粘贴到支持正则表达式替换的文本编辑器里，使用正则表达式即可迅速把浏览器的`accept:*/*
accept-encoding:gzip, deflate
accept-language:en-US,en;q=0.8,zh-CN;q=0.6,zh;q=0.4
content-length:486`这种格式替换成`'accept': '*/*',
'accept-encoding': 'gzip, deflate',
'accept-language': 'en-US,en;q=0.8,zh-CN;q=0.6,zh;q=0.4',
'content-length': '486',`
#python代码
直接上代码
```
# coding:utf8
import requests

def getResponse():
    # general部分的request url
    url = 'https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml'
    # 完全从request hreader里处理过来的数据
    headers = {'host': 'www.gebiz.gov.sg',
                'method': 'POST',
                'path': '/ptn/opportunity/BOListing.xhtml',
                'scheme': 'https',
                'version': 'HTTP/1.1',
                'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'accept-encoding': 'gzip, deflate',
                'accept-language': 'en-US,en;q=0.8,zh-CN;q=0.6,zh;q=0.4',
                'cache-control': 'max-age=0',
                'content-length': '486',
                'content-type': 'application/x-www-form-urlencoded',
                'dnt': '1',
                'cookie': '__cfduid=d3945804a6f00d7cb1bb79047fd1f1e101456553632; BIGipServerPTN2_PRD_Pool=18964640.47873.0000; wlsessionid=edosaUufRur8IsiykZH7-o1WhSA1eV348F07T4udzbLUxNDjB_Wj!1656571856',
                'faces-request': 'partial/ajax',
                'origin': 'https://www.gebiz.gov.sg',
                'referer': 'https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml?origin=menu',
                'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36',
               }
    # 完全从form data里处理过来的数据
    data = {
        'contentForm': ' contentForm',
        'contentForm': ' j_idt54_listButton2_HIDDEN-INPUT:',
        'contentForm': ' j_idt61_searchBar_INPUT-SEARCH:',
        'contentForm': ' j_idt203_select:0',
        'contentForm:j_id36': 'contentForm:j_idt260_2_2',
        'javax.faces.ViewState:-6614576643680056808': '7012720147893489297',
        'javax.faces.source:contentForm': 'j_idt260_2_2',
        'javax.faces.partial.event': 'click',
        'javax.faces.partial.execute':'contentForm:j_idt260_2_2 contentForm:j_idt260',
        'javax.faces.partial.render':'contentForm:j_idt209',
        'javax.faces.behavior.event': 'action',
        'javax.faces.partial.ajax': 'true',
    }
    # 使用requests发送post请求
    resp = requests.post(url, headers=headers, data=data)
    return resp

if __name__ == '__main__':
    print('start')
    print(getResponse().content)
```
# 输出结果
![](http://www.findspace.name/wp-content/uploads/2016/02/gebiz_spider1.png)
一定是有`<update id="contentForm:j_idt209"><![CDATA[<tr><td>`这样的字样。
如果只有很短的描述`javax.faces.ViewState`的值，说明你的`javax.faces.ViewState`和cookies值很可能已经失效了，在页面上重新点过来，查看这两个值并重新赋上。
# 验证输出结果
如果你是点击的第二页，则查看第二页的数据是否包含在返回的数据里即可，否则就是某些步骤有问题。
![](http://www.findspace.name/wp-content/uploads/2016/02/gebiz_spider2.png)
现在，这个页面数据就在实验情况下被抓下来了。
# CDATA
XML 解析器通常情况下会处理XML文档中的所有文本。
当XML元素被解析的时候，XML元素内部的文本也会被解析。如果在XML文档中使用类似"<" 的字符, 那么解析器将会出现错误，因为解析器会认为这是一个新元素的开始。为了避免出现这种情况，必须将字符"<" 转换成实体，像`<message>if salary &lt; 1000 then</message>`
在CDATA内部的所有内容都会被解析器忽略。
如果文本包含了很多的"<"字符和"&"字符——就象程序代码一样，那么最好把他们都放到CDATA部件中。
一个 CDATA 部件以`<![CDATA[`标记开始，以`]]>`标记结束。
通过复制粘贴输出结果到文本编辑器，可以看到，这个CDATA里面实际上就是要展示的部分页面。


# requests
[中文入门文档](http://docs.python-requests.org/zh_CN/latest/)

#Reference
[XML CDATA是什么？](http://www.cnblogs.com/qiantuwuliang/archive/2010/03/29/1699361.html)