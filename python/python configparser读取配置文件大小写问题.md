#Introduction
在使用python2 configparser读取ini配置文件的时候，发现没法保留配置文件大小写，经搜索发现如下两种方法。同时也发现了python2和python3的configparser的一些小区别。
# 保留配置文件大小写
ConfigParse源码里有这样一段：
```python
def optionxform(self, optionstr):
	return optionstr.lower()
```
所以最后统一返回的是小写。
## 1.直接修改源码
可以直接在`/usr/lib/python2.7/ConfigParser.py`这是我的python路径，参考。
去掉`.lower()`即可。
但是这样会影响所有用户的使用。
## 2.类继承重写`optionxform`函数
```python
import ConfigParser  
class myconf(ConfigParser.ConfigParser):  
    def __init__(self,defaults=None):  
        ConfigParser.ConfigParser.__init__(self,defaults=None)  
    def optionxform(self, optionstr):  
        return optionstr  
conf=myconf()  
conf.read("db.conf")  
print conf.sections()  
for  i in conf.sections():  
    print conf.options(i)  
    for option in  conf.options(i):  
        print option,conf.get(i,option)  
```
可以打印下看下变化。
# python2,3中configparser的区别
## 访问方式
最显著的变化是，3中支持通过`[section][option]`这样的方式去访问，2中只能用`get`等函数。比如打印某个section的所有数据
## 包名
包名字也从2的`ConfigParser`变成了`configparser`

未完待续

# Reference

http://blog.csdn.net/xluren/article/details/40298561