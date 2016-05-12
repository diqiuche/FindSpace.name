#wget下载jdk

##使用场景
+ ssh到远程机器，没有窗口界面。
+ wget有时比浏览器自带的下载速度要快一些。
##wget命令
```
wget --no-cookies --no-check-certificate --header "Cookie: gpw_e24=http%3A%2F%2Fwww.oracle.com%2F; oraclelicense=accept-securebackup-cookie" "http://download.oracle.com/otn-pub/java/jdk/8u25-b17/jdk-8u25-linux-x64.tar.gz"
```
如果有新的版本，只要更改最后的8u25-b17和最后的文件名8u25中的版本号。
##Other
[Ubuntu配置JDK环境][1]


[1]: http://www.findspace.name/easycoding/155 "Ubuntu JDK"
