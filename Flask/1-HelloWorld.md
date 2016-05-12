[TOC]
#前言
我正在写一个使用ptyhon flask微型框架来做web应用的系列教程，这是第一篇。
>提示：
文章已于2014年八月修订过，以保证内容和Python、Flask同步。

#作者信息
![][1]
我是一名软件工程师，有十几年的使用不同语言开发大型应用的经验。我第一次接触Pyhton是在工作中为了粘合一个C++库。
除了Python之外，我还用PHP、Ruby、Smalltalk甚至C++写过web应用。在所有这些当中，我认为Python/Flask是最灵活的。
>UPDATE：
我已经写了一本书《Flask Web Development》，通过 O'Reilly Media于2014年出版。这本书和现在这个教程互补，书里有更多更高级的Flask的用法，但是这个教程也有自己独特的章节。访问 http://flaskbook.com 来获取更多信息。

#这个教程将要完成的应用
通过这个教程，我们将会开发出一款有特色的微型博客系统，我决定称它为mircoblog.
在这个教程中，我们将逐步学习以下内容：

+ 用户管理：登录、会话、角色、个人信息、头像
+ 数据库管理：migration handling（Find：这个不知道怎么翻译）
+ 网页表单支持：填写域的合法验证
+ 长列表的分页
+ 全文搜索
+ 邮件通知
+ HTML模板
+ 多国语言支持
+ 缓存和其他性能优化
+ 开发和上线之后的调试功能
+ 在服务器上安装

正如你所看到的，我们将做很多东西。我希望结束后这个应用能作为写其他应用的模板。
#要求
有一台运行python的电脑，这就足够了。该应用可运行在Windows、OS X和Linux上。除非特别说明，否则代码已经通过了Python2.7和3.4的测试。
在此之前，我们假设你已经熟悉了命令行界面（对于windows用户来说，是命令提示符）的使用并且了解你使用的操作系统基本的文件管理命令。否则，我强烈建议你去学习怎样使用命令行来创建文件夹、复制文件等等。
最后，你应该对Python的模块和包比较熟悉，能用Python实现一些基础的东西。
#安装Flask
##创建虚拟环境
当然前提是安装Python。
现在我们要安装Flask和几个我们要用到的插件。我建议你创建一个虚拟的环境来安装这些，这样你的Python就不会受到影响，而且，安装的时候不需要root权限。
现在，打开一个命令行窗口，选择一个你想安装Flask的位置，创建一个新的文件夹来存放它。本教程建立的文件夹是microblog.

###如果你是Python3.4
cd进新建立的micriblog，用下面的命令创建一个虚拟环境：
~~`$ python -m venv flask`~~
>提示
在某些操作系统下，你必须用python3而不是python。

>Find注：
这样创建出来的解释器，bin文件夹下没有pip，而后面需要用到，因此还是直接安装一个virtualenv吧。如果你的linux环境是Pyhton2和3共存，安装命令如下：
```
$sudo apt-get install python3-pip
$sudo pip3 install virtualenv
```


上面这个命令创建了一个独立的Python解释器版本
> Find注：Ubuntu14.04下有bug，bug的解决方案：
创建一个不含pip的虚拟环境，然后手动安装pip：
```
$pyvenv-3.4 --without-pip flask
$source flask/bin/activate
$curl https://bootstrap.pypa.io/get-pip.py | python
$source flask/bin/activate
```

###如果你的Python版本低于3.4
需要先下载安装一个[Virtual.py][2]才能创建虚拟环境。
Mac下可以这条命令安装：
```
$ sudo easy_install virtualenv
```
Linux则下载对应发行版的安装包，如果是Ubuntu：
```
$ sudo apt-get install python-virtualenv
```
Windows用户可以直接安装Python3.4,否则最简便的安装virtualenv的方法是先安装[pip][3]，然后执行：
```
$ pip install virtualenv
```
安装完毕virtualenv之后，创建虚拟环境：
```
$ virtualenv flask
```
##安装Flask和插件
不管你用以上哪种方法创建完毕，都会得到一个flask文件夹，其中包含了完整的Python环境来供我们使用。
虚拟环境可以根据自己的需求开启或关闭。开启虚拟环境会把当前创建的虚拟环境的`/bin`文件夹路径添加到系统的`path`中去，在这个虚拟环境中敲入python，你将获得的是这个虚拟环境的python版本而不是你操作系统的。但是虚拟环境并不是必须的，它等效于调用你下载的python的绝对路径来作为你代码的解释器。
###如果你是Linux、OS X或者Cygwin
一条条执行以下命令来安装flask和它的扩展：
```
$ flask/bin/pip install flask
$ flask/bin/pip install flask-login
$ flask/bin/pip install flask-openid
$ flask/bin/pip install flask-mail
$ flask/bin/pip install flask-sqlalchemy
$ flask/bin/pip install sqlalchemy-migrate
$ flask/bin/pip install flask-whooshalchemy
$ flask/bin/pip install flask-wtf
$ flask/bin/pip install flask-babel
$ flask/bin/pip install guess_language
$ flask/bin/pip install flipflop
$ flask/bin/pip install coverage
```
>Find注：
我相信绝对不会有人想一条一条执行的，Linux直接将上面代码去掉$符，然后新建一个run.sh,粘贴进去，添加运行权限运行即可。

###Windows用户
```
$ flask\Scripts\pip install flask
$ flask\Scripts\pip install flask-login
$ flask\Scripts\pip install flask-openid
$ flask\Scripts\pip install flask-mail
$ flask\Scripts\pip install flask-sqlalchemy
$ flask\Scripts\pip install sqlalchemy-migrate
$ flask\Scripts\pip install flask-whooshalchemy
$ flask\Scripts\pip install flask-wtf
$ flask\Scripts\pip install flask-babel
$ flask\Scripts\pip install guess_language
$ flask\Scripts\pip install flipflop
$ flask\Scripts\pip install coverage
```
#"Hello,World"
##目录结构
现在，在你的microblog文件夹下有一个flask文件夹，里面是Python的解释器、Flask框架以及将要用到的插件。现在，让我们开始写地一个web应用吧。
cd进microblog文件夹，创建基本的web应用目录结构：
```
$ mkdir app
$ mkdir app/static
$ mkdir app/templates
$ mkdir tmp
```
app文件夹存放应用包。
static子文件夹存储图像、脚本、css等静态文件
templates存放网页模板
##初始化脚本
让我们从创建一个简单的初始化app文件夹（包）的脚本(文件：app/\_\_init\_\_.py)开始：
```
from flask import Flask
app=Flask(__name__)
from app import views
```
上面这个脚本简单地创建了一个应用对象(Flask类的)，然后导入了views模块（这个模块我们还没有写）。不要混淆了变量app(作为Flask的实现)和包app（就是我们建立的app文件夹）

你也许会疑惑为什么还有import放在了最后，而不是像大多数情况下放到文件开头。这是为了避免循环引用，因为导入views模块需要先定义app变量。
>Find注：
此处的循环引用没太明白，普通的两个py文件的循环引用我明白，但是这里怎么就循环引用了？是因为如果先写from，那么就是先导入了app，然后再定义app吗？

views是用来处理响应浏览器和客户端请求的(Handler)。在Flask中，handler就是自己写的python函数。每个view的函数都映射到一个或多个请求链接上。
##第一个view函数
第一个view函数(文件 app/views.py)：
```
from app import app

@app.route('/')
@app.route('/index')

def index():
	return 'Hello,World!'
```
这个view非常简单，只是返回了一个字符串，显示在客户端的浏览器上。两个route声明创建了从`/`和`index`链接到这个函数的映射。
最后一步是创建一个启动脚本来运行我们的web server。
在根目录（microblog）下创建run.py：
```
#!flask/bin/python
from app import app
app.run(debug=True)
```
这个脚本从app包里导入了app变量并且调用了它的run方法来开启服务器。记住：之前我们创建的变量app是Flask的实例
##运行
###OS X、Linux和Cygwin用户
运行之前，需要给这个文件添加可执行权限：
`$ chmod a+x run.py`
然后运行它：
`$./run.py`
###windows用户
略有不同，不需要设置它的可执行属性，而是在microblog目录下执行：
```
$ flask\Scripts\python run.py
```
###运行
服务器初始化之后会监听5000端口等待有连接。现在用你的浏览器打开
`http://localhost:5000/`
或者:
`http://localhost:5000/index`
不知道你注意到了route函数的映射了没有，'/'和'index'都映射到了view函数，所以它们会产生同样的效果。如果你输入了其他的URL就会出错，因为只有这两个链接指定了映射。
可以通过'Ctrl-C'来结束服务器。

#下节小窥
下一节，我们将给应用添加HTML模板。


[1]: http://blog.miguelgrinberg.com/static/miguel.jpg "Author Photo"
[2]: http://virtualenv.readthedocs.org/en/latest/installation.html "Virtual env"
[3]: https://pip.pypa.io/en/latest/installing.html "Pip"
[4]: http://zengrong.net/post/2167.htm "Python虚拟环境"