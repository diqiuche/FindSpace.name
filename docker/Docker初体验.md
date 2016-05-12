#Introduction
简单记录下自己用docker的一些经历和经验。并不对docker进行介绍，可自行查阅下面的链接。
先发一些阅读的的东西。
推荐这个简短的小教程：
[Docker 从入门到实践](http://dockerpool.com/static/books/docker_practice/index.html)
这是官方的文档：
[Docker Documents](https://docs.docker.com/mac/)
后面的一些安装也是按照官方文档里说的进行。
[利用Docker构建开发环境](http://tech.uc.cn/?p=2726)

#安装
我的运行环境：
Debian 8 Jessie x86_64
[参考官方文档](https://docs.docker.com/linux/step_one/)
```bash
 wget -qO- https://get.docker.com/ | sh
```
系统会要求你输入sudo的密码，然后会安装docker和它的依赖包。
注意在安装完成的时候，终端里会有个提示，大概意思就是如果你想让普通用户也可以运行docker命令，需要把你的用户添加到docker group里，
```bash
sudo usermod -aG docker username
```
#获取镜像
先从[官网的hub](https://hub.docker.com/explore/)查看都有哪些镜像可以用
比如点到debian的详细信息，可以看到支持很多版本：8.3，8,7,等等。右边有提示命令：
```bash
docker pull debian
```
默认都是latest版本，想要下载指定版本可以通过在后面添加`:version`：
```bash
docker pull debian:stretch
```
等待下载完成即可
#管理镜像
```bash
docker images
```
在列出信息中，可以看到几个字段信息

+ 来自于哪个仓库，比如 ubuntu
+ 镜像的标记，比如 14.04
+ 它的 ID 号（唯一）
+ 创建时间
+ 镜像大小

其中镜像的 ID 唯一标识了镜像，注意到 ubuntu:14.04 和 ubuntu:trusty 具有相同的镜像 ID，说明它们实际上是同一镜像。

TAG 信息用来标记来自同一个仓库的不同镜像。例如 ubuntu 仓库中有多个镜像，通过 TAG 信息来区分发行版本，例如 10.04、12.04、12.10、13.04、14.04 等。
#进入镜像
下面的命令指定使用镜像debian:stretch来启动一个容器。
```bash
docker run -t -i debian:stretch /bin/bash
```
如果不指定具体的标记，则默认使用 latest 标记信息。`-t`是指明tag，`-i`是绑定tty到当前的命令行终端。不然的话，就直接运行一下就结束了，不能产生交互效果。
默认里面啥也没有，所以先apt update，然后装自己需要的就行。因为默认的镜像源很慢，所以跟修改普通的一个系统一样，修改sourcelist，再重新update。
#卸载
官网文档给的很详细，
To uninstall the Docker package:
```bash
sudo apt-get purge docker-engine
```
To uninstall the Docker package and dependencies that are no longer needed:
```bash
sudo apt-get autoremove --purge docker-engine
```
The above commands will not remove images, containers, volumes, or user created configuration files on your host. If you wish to delete all images, containers, and volumes run the following command:
```bash
rm -rf /var/lib/docker
```
You must delete the user created configuration files manually.
#与宿主机共享数据
[官网挂载宿主机文件夹到docker的说明](https://docs.docker.com/engine/userguide/dockervolumes/#mount-a-host-directory-as-a-data-volume)
简单解释：
```bash
docker run -v /Users/<path>:/<container path>
```
符合from to的参数规则，先写from路径，冒号，to路径，注意每个路径后面没有`/`，如果路径不存在，docker会自动创建。注意，这个参数最好写在前面，起码是在`-ti`参数前面。
默认挂载是rw模式，可读可写。