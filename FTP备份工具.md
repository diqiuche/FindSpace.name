#Introduction
多备份的免费额度已经停止了，只好自己写个ftp的备份工具。
我的主力机器是linux，可[配合linux的corn服务](http://www.findspace.name/res/902)实现定时备份到本地。
windows下使用自带的任务计划即可。
#项目地址
github地址
https://github.com/FindHao/backupFTP
coding.net地址
https://coding.net/u/findspace/p/backupFTP/git
开源中国地址
http://git.oschina.net/findspace/backupFTP

#使用说明
##配置文件
clone项目之后，在项目文件夹下新建`config.ini`并填入如下内容
```ini
[ftp]
# ftp登录的地址
address = your_ftp_address
# 登录用户名
name = your_user_name
# 登录密码
password = your_password
# 登录端口，默认21
port = 21
# 需要下载的绝对路径
remote_dir = /wwwroot/adds/
# 需要下载到本地的路径，如果使用相对路径，请用./开头,如果是绝对路径，请以/开头。默认是./back/此项非必需,可直接去掉
local_dir = 
```
将配置文件里右边的键值改成你自己的。
##运行
```bash
python3 entry.py
```
即可自动将配置的远程目录同步到本地指定目录

#Todo list

+ 增加数据库备份功能
+ 增加增量备份功能
+ 增加备份到百度云等云端存储的功能
+ 增加邮件通知功能

#Reference
主要参考
 http://canlynet.iteye.com/blog/836996
这篇博客的代码。