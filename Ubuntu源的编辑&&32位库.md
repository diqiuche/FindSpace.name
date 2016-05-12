#源的编辑
##首先备份Ubuntu 源列表
>sudo cp /etc/apt/sources.list /etc/apt/sources.list.backup

 （备份下当前的源列表）

##修改更新源
>sudo gedit /etc/apt/sources.list

请参考官方的说明
>http://wiki.ubuntu.org.cn/%E6%BA%90%E5%88%97%E8%A1%A8

另外，根据你的版本来修改，比如13.04是raring，如果你是13.10，那就把raring全部替换成saucy。版本号可以参考百度百科。

##保存后
>sudo apt-get update
sudo apt-get upgrade
#32位库
直接从系统设置--》软件更新--》Ubuntu软件
从这个标签页里的下载自选择其他服务器，然后单击“选择最佳服务器”，让它自己选择就行，更新完成以后，
>sudo apt-get update
sudo apt-get upgrade

然后再安装之前依赖的库就可以，如果是因为安装wps而导致，则
>sudo apt-get -f install