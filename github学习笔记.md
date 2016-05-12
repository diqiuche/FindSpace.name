##安装与配置
```
sudo apt-get install git 
git config --global user.name "Your Name Here"
git config --global user.email "your_email@example.com"
//设置让credential helper 帮助我们在一定的时间内在内存中保存我们的代码，其中第二行的命令是设置超时的时间（两句分别执行）
git config --global credential.helper cache
git config --global credential.helper 'cache --timeout=3600'
```
##项目建立与更新
###新建立空的项目
先建立一个目录，该目录名跟你新建立的repository有关，命令如下(一行一个命令)：
```
mkdir ~/Hello-World        //（其中的hello0-World就是你新建立的repository的名称）
cd  ~/Hello_World
git init  //(初始化一个空的Git repository )
touch README     //README 暂时写入“hello world”(建立一个文件，README文件的主要用途是描述项目或者一些加入信息的文档，例如关于如何安装该项目或者怎么使用这个项目)
//提交刚加入的文件README,命令如下(下面的两步是不能省略的，文件名可以改为你想要提交的文件名)：
git add README
git commit -m 'first commit'

push 提交(这里提交的方式是使用http的方式，也有ssh的提交方法，这里面就不做介绍了)
git remote add origin https://github.com/username/Hello-World.git 
 //(其中的https://github.com/username/Hello-World.git，是该项目的http,这可以在网页上得到，复制过来即可)
//之后会要求输入用户名和密码。提交的命令是：
git push origin master
```
github上的提示
```

Create a new repository on the command line

touch README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin https://github.com/Findxiaoxun/smartpellow.git
git push -u origin master
Push an existing repository from the command line

git remote add origin https://github.com/Findxiaoxun/smartpellow.git
git push -u origin master
```
###远端已经存在的项目
先clone下来
>git clone https://github.com/username/Hello-World.git  
然后更新之后add，push
###本地已经存在的项目
```
git remote add origin https://github.com/username/Hello-World.git  
$git fetch origin    //获取远程更新
$git merge origin/master //把更新的内容合并到本地分支
```
然后再git push origin master
Reference：

http://www.cnblogs.com/Findxiaoxun/p/3574427.html

##撤销
###撤销本地还未提交到远端的commit
本地直接：

>git reset --hard HEAD~2

表示恢复到2次提交以前，这里是本地的恢复。远端此时并没有改变，此时commit是不行的，会提示你落后于远端
###撤销已经提交到远端的

>git push origin HEAD --force

这就是强行推送了。
###Git的一些常用的撤销提交版本的的命令：

git revert HEAD                  撤销前一次 commit
git revert HEAD^               撤销前前一次 commit
git revert commit （比如：fa042ce57ebbe5bb9c8db709f719cec2c58ee7ff）撤销指定的版本，撤销也会作为一次提交进行保存。
revert 后要submmit ~

 
修改最后一次提交 git commit --amend
##推送到不同的远端
比如我要同时推送到github和gitoschina。
###直接修改config文件：
[remote "origin"]
url = ssh://server.example.org/home/ams/website.git
url = ssh://other.exaple.org/home/foo/website.git
这样，每次push origin master的时候，会一个一个问你帐号和密码来推送
###添加不同的名字
>git remote add origin xxx
git remote add another yyy
git push origin master
git push another master

如果所在的地方github被墙了，可以用这个方法，然后利用翻墙单独推送github的。
##一些错误
###Github “fatal: remote origin already exists” 解决办法

最近遇到这个问题, 可以采用直接修改config文件的方法, 首先, 显示隐藏文件, 进入 .git/ 目录, 目录下面有一个

config文件, 以文本文件方式打开该文件, 在后面添加
>[remote "origin"]
url =项目的git地址
即可.
###更新被拒绝，因为您当前分支的最新提交落后于其对应的远程分支。
可以输入：
git push -f 





 

