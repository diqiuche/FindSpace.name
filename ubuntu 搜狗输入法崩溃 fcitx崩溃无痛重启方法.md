#Pre
Ubuntu的搜狗输入法bug还是多多啊，比如总有那么几次，fcitx的cpu占用率到了100%，就听到cpu风扇呼呼呼地转。或者偶尔直接提示你崩掉了，让你重启。

注销有时能解决问题，可是一旦注销了，所有打开的程序都关了。这里给一种无伤的重启fcitx方法。
#方法
1. 首先top，列出进程表，找到fcitx的pid或者直接pidof fcitx
2. sudo kill 掉fcitx  以上两步可以直接用：pidof fcitx|xargs kill来使用
3. fcitx &  这里的意思是后台跑fcitx，回车几次就可以。
4.  sogou-qimpanel &  同样，后台启动搜狗输入法面板。

此时就可以切换输入法试试看了
#UPDATE：
最终还是放弃了搜狗输入法，其实fcitx-googlepinyin也很不错，使用了云插件模块也足以弥补一些简单词语的拼写。

##UPDATE:2015.10.22
最近发现搜狗拼音输入法For linux更新了2.0，尝试下，看看这个bug修复了没有。但是sublime下，输入框还是不能跟随。那么sublime里面就少打中文吧。
详情查看：
[Sublime Linux下中文输入框不跟随问题解决](http://www.findspace.name/res/1223)
## April 8, 2016 12:36 PM
# 上面重启方法的脚本内容
```bash
#!/bin/sh
pidof fcitx | xargs kill
pidof sogou-qimpanel | xargs kill
nohup fcitx  1>/dev/null 2>/dev/null &
nohup sogou-qimpanel  1>/dev/null 2>/dev/null &
```
将以上内容保存到`restart_sogou`,并复制到`/usr/bin`,并添加可执行权限，即可在任意地方从命令行执行`restart_sogou`来重启搜狗面板
```bash
sudo cp ./restart_sogou /usr/bin/
sudo chmod a+x /usr/bin/restart_sogou
```

#新方法
使用`cpulimit`这个工具，apt安装即可，可以限制某个进程的cpu占用率。
比如fcitx的进程id是1226：
```
cpulimit -p 1226 -l 20 -b
```
参数可以`cpulimit --help`来查看。
`-p`是进程的pid
`-l`是限制的占用率大小
`-b`是后台运行

###保存脚本
将如下内容保存到`limit_fcitx.sh`
```bash
#!/bin/sh
for pid in `pidof fcitx`
do 
nohup cpulimit -p $pid -l 30 -b 1>/dev/null 2>/dev/null &
done 
```
里面的l参数可以自行设置，然后添加到开机启动项：
```
sudo vim /etc/rc.local
#在最后的exit 0之前添加你脚本的位置
nohup /home/find/Dropbox/scripts/limit_fcitx.sh &
```
重启即可生效。