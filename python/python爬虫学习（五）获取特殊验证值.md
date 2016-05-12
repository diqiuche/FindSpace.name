#Introducation
##[Python爬虫学习目录](http://www.findspace.name/easycoding/1625)
实际上这个网站还是一个特殊的验证值，在每次post的时候，都有一个特殊的数据，`javax.faces.ViewState`，经过简单的搜索，我认为这是网站开发者使用`java.faces`这个框架中使用的一个简单的验证机制。进一步排除无脑cookie爬虫。
# 获取`javax.faces.ViewState`
在[第三节 抓取页面 ](http://www.findspace.name/easycoding/1631)中，我们在查看输出结果的时候，里面就有个`javax.faces.ViewState`值，而且经过同一个session访问多个
页面的时候，这个值每次都出现，但是都是同一个值，因而考虑把它和cookie视为一样的。
而且在实验过程中，发现如果这两个值有错误，获取的结果就会出错。
那么，考虑获取这个值。
这里直接上代码
#代码
```python
# coding: utf8
import requests
import time

cook_value = ''
javax_view_state = ''

def get_response(s):
    global cook_value, javax_view_state
    # general部分的request url
    url = 'https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml?origin=menu'
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
                'content-type': 'application/x-www-form-urlencoded',
                'dnt': '1',
                'cookie': cook_value,
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
        'javax.faces.ViewState': javax_view_state,
        'javax.faces.source:contentForm': 'j_idt260_2_2',
        'javax.faces.partial.event': 'click',
        'javax.faces.partial.execute':'contentForm:j_idt260_2_2 contentForm:j_idt260',
        'javax.faces.partial.render':'contentForm:j_idt209',
        'javax.faces.behavior.event': 'action',
        'javax.faces.partial.ajax': 'true',
    }
    print(cook_value)
    print(javax_view_state)
    # 使用requests发送post请求
    resp = s.post(url, headers=headers, data=data)
    return resp


def get_javax_view_state(a_content):
    """
    把javax从返回的内容中用正则表达式提取出来。
    :param a_content:返回的网页内容
    :return: javax值
    """
    a_javax_view_state = ''
    if a_content.find('javax.faces.ViewState') >= 0:
        import re
        # reg = 'CDATA\[([\d:-]+)'
        reg = 'javax.faces.ViewState" value="([\d:-]+)'
        reg = re.compile(reg)
        a_javax_view_state = reg.findall(a_content)[0]
    return a_javax_view_state

# 建立session
sess = requests.session()
# 伪造header
a_header={
    'host': 'www.gebiz.gov.sg',
    'method': 'GET',
    'path': '/ptn/opportunity/BOListing.xhtml?origin=menu',
    'scheme': 'https',
    'version': 'HTTP/1.1',
    'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'accept-encoding': 'gzip, deflate, sdch',
    'accept-language': 'en-US,en;q=0.8,zh-CN;q=0.6,zh;q=0.4',
    'cache-control': 'max-age=0',
    'dnt': '1',
    'upgrade-insecure-requests': '1',
    'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36',
}
# 先访问个页面获取cookie和javax
req1 = sess.get("https://www.gebiz.gov.sg/ptn/opportunity/BOListing.xhtml?origin=menu", headers=a_header)
cook = req1.cookies
# 此时需要打印一下content，自己测试，查看网页内容是什么，写提取javax的正则表达式应该怎样写。
content = req1.content
print(cook)
# 经测试，这里的cookie必须是三个字段，当然因网站而异
if len(cook) != 3:
    print('error')
    exit()
for x in cook:
    cook_value += x.name + '=' + x.value + ';'
    print(x)
cook_value = cook_value[:len(cook_value)-1]
print(cook_value)
javax_view_state = get_javax_view_state(content)
print(javax_view_state)
# ！！！这里很重要，延时访问。
time.sleep(1)
response = get_response(sess)
print(response.content)
# 最后别忘了关闭，以免给服务器造成大量负担
sess.close()
```
# 总结
程序仍然有些不稳定，尚在测试中。但成功获取的概率还是挺大的，正在分析原因。