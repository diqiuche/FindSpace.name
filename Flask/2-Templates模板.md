[TOC]
#扼要重述
如果你跟着[前一节][0]做了，那么你已经有了一个能够工作的简单的web应用，目录结构如下：
```
.
├── app\
│   ├── __init__.py
│   ├── static\
│   ├── templates\
│   └── views.py
├── flask\
│   └──<virtual environment files>
├── run.py
└── tmp\
```
执行'run.py'来运行这个应用，在浏览器中输入'http://localhost:5000'来打开主页
我们将紧接着之前的成果继续做，所以一定要确保你的简易服务器能正常运行。

#为什么需要模板
让我们思考下我们该怎样扩展我们的小应用。
我们想要我们的微型博客应用主页能显示欢迎用户登录的标题，这是很正常的交互。暂时忽略我们现在还不能登录用户，这个是后话。

一个很简单的方法是在view函数里直接打印出HTML格式的标题：
```
from app import app

@app.route('/')
@app.route('/index')
def index():
    user = {'nickname': 'Miguel'}  # fake user
    return '''
<html>
  <head>
    <title>Home Page</title>
  </head>
  <body>
    <h1>Hello, ''' + user['nickname'] + '''</h1>
  </body>
</html>
'''
```
运行你的服务器，在浏览器里试试吧。
考虑到我们还没有提供对用户的支持，我只能使用一个假的用户对象，有时称为测试对象。这样我们就能够专注于那些依赖我们还没有添加的功能的部分了。
你应该能发现，上面这种把网页直接写到输出的方式非常不优雅。想象一下如果你要返回一个内容复杂量大的动态网页这个网页该复杂到什么程度。如果你还要修改网站的布局呢？每一个网页都直接返回html代码？显然这种方法缺乏可扩展性。
#模板
如果应用的逻辑处理和网页布局的展现能分开，应该会更加便于管理，你觉得呢？你甚至可以雇佣一个前端来写前端，而网站的数据处理则交给python。模板可以用来实现这种分离。
让我们来写第一个模板（文件 app/templates/index.html）:
```
<html>
  <head>
    <title>{{ title }} - microblog</title>
  </head>
  <body>
      <h1>Hello, {{ user.nickname }}!</h1>
  </body>
</html>
```
正如你所见，我们写了一个标准的HTML页面，只是有一点点不同，用`{{...}}`括起来了。
现在，让我们来看看怎么把模板应用到view函数中去（文件 app/views.py）：
```
from flask import render_template
from app import app

@app.route('/')
@app.route('/index')

def index():
	user = {'nickname':'Find'} #fake user
	return render_template('index.html',title='Home',user=user)

```
尝试重新运行应用，看看模板是怎么发挥作用的。等浏览器渲染出了网页之后，你可以查看网页源码，看看它和模板有什么区别。
为了渲染模板，我们必须新导入flask的函数`render_template`。这个函数的传入参数包括模板文件名和模板变量表，返回经过渲染变量填充过的网页。

Under the covers,`render_template`函数调用了['Jinja2'][1]模板引擎，Jinja2会替换所有的`{{...}}`为传入的对应的变量值。
#模板中的控制语句
Jinja2也支持控制语句，通过`{%...%}`来标识。下面我们给模板添加if语句（文件 app/templates/index.html）:
```
<html>
  <head>
    {% if title %}
    <title>{{ title }} - microblog</title>
    {% else %}
    <title>Welcome to microblog</title>
    {% endif %}
  </head>
  <body>
      <h1>Hello, {{ user.nickname }}!</h1>
  </body>
</html>
```
现在我们的模板已经有点点智能了。如果view函数忘记定义网页标题，网页将显示一个默认的标题而不是显示一个空标题。你可以尝试从`render_template`函数里去掉`title`参数来查看这条if语句是怎么起作用的。
#模板中的循环语句
我们博客应用的登录用户可能会想查看最近的文章，让我们来看看这个功能怎么实现吧。

开始之前，我们先手动创建一些用户和文章的测试数据（文件： /app/views.py）:
```
def index():
  user = {'nickname':'Find'} #fake user
  posts = [
    {
      'author':{'nickname':'John'},
      'body':'Beautiful day in Portland!'
    },
    {
      'author':{'nickname':'Susan'},
      'body':'The Avengers movie was so Cool!'
    }
  ]

  return render_template('index.html',title='Home',user=user,posts=posts)

```
我们用列表来存储文章，文章列表的每个元素都有author和body两个字段。当我们后面实现真正的数据库的时候，我们会保留这些字段名，这样我们就能设计测试我们的模块而不用担心当后期使用数据库的时候需要大幅修改它。
在模板网页上我们需要解决新的问题。文章列表可以有任意数量的元素，这取决于view函数想要展现多少文章。模板网页不能预知传递过来的文章数量，所以它需要尽可能多的渲染view函数传递过来的文章。
来看看我们应该怎么用for循环吧（文件：app/templates/index.html）:
```
<html>
  <head>
    {% if title %}
    <title>{{ title }} - microblog</title>
    {% else %}
    <title>Welcome to microblog</title>
    {% endif %}
  </head>
  <body>
      <h1>Hello, {{ user.nickname }}!</h1>
      {% for post in posts %}
      <div><p>{{post.author.nickname}}says:<b>{{post.body}}</b></p></div>
      {% endfor %}
  </body>
</html>
```
很简单吧？尝试在文章列表里添加更多元素，运行一下。
#模板的继承
我们的microblog应用还需要一个页面顶部的导航栏，包括几个链接：修改个人资料、登录、退出等等。
我们当然可以简单把导航栏添加到主页模板上，但是随着应用程序的复杂，我们会实现更多页面，而且这些页面使用的是同一个导航栏。如果在每个页面上都手动添加导航栏，并且维护它的更新，随着页面和模板的增加，这将是一个非常繁重的任务。
我们可以利用Jinja2的模板继承特性，这个特性允许我们提取出所有模板页面中相同的部分，作为一个新的模板，而其他模板继承它就可以。
定义一个包含导航栏和标题的基础模板（文件 app/templates/base.html）：
```
<html>
  <head>
    {% if title %}
    <title>{{ title }} - microblog</title>
    {% else %}
    <title>Welcome to microblog</title>
    {% endif %}
  </head>
  <body>
    <div>Microblog: <a href="/index">Home</a></div>
    <hr>
    {% block content %}{% endblock %}
  </body>
</html>
```
在这个模板页面中，我们使用了`block`控制语句，继承它的模板可以在block语句内容里添加它们自己的内容。
现在，我们需要修改index.html模板页面，使之继承于base.html(文件：app/templates/index.html):
```
{% extends "base.html" %}
{% block content %}
    <h1>Hi, {{ user.nickname }}!</h1>
    {% for post in posts %}
    <div><p>{{ post.author.nickname }} says: <b>{{ post.body }}</b></p></div>
    {% endfor %}
{% endblock %}
```
base.html已经为我们处理了页面的结构，所以在index.html中只剩下了填充的内容。`extends`块建立了这两个模板页面的继承关系，这样，Jinja2就知道要渲染index.html页面的时候，需要先渲染base.html页面。而`block content`中的`content`标记告诉了Jinja2 index.html的内容需要放在base.html的哪里。当我们需要创建新的模板的时候，我们同样要继承base.html。









[0]: ThePrevious
[1]: http://jinja.pocoo.org/ "Jinja2"