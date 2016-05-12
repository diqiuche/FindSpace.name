#introduction
本文详细说明了下载到配置环境在linux下来设置jdk进行java开发。
#下载JDK
登录oracle的网站去下载JDK
http://www.oracle.com/technetwork/java/javase/downloads/index.html
![](http://www.findspace.name/wp-content/uploads/2015/06/downjdk.png)

在下载之前要先选中“Accept License Agreement” 然后，才允许下载。
版本之间简单的区别说明：
>+ x86对应的是32位操作系统下的应用程序，x64对应的是64位操作系统下的应用程序
+ rmp原本是RedHat Linux发行版专门用来管理Linux各项套件的程序，由于它遵循GPL规则且功能强大方便，因而广受欢迎。逐渐受到其他发行版的采用。    RPM套件管理方式的出现，让Linux易于安装，升级，间接提升了Linux的适用度。
+ tar.gz就是用tar和gzip压缩后的文件扩展名。 linux一般都装有tar和gz。

如果你的linux发行版默认支持rpm，则下载rpm包，否则下载tar.gz的包。
下文以`jdk-7u11-linux-i586.tar.gz`举例。
#安装JDK
##解压
我要在`/opt/java`下安装：
```bash
#解压下载的tar.gz文件,默认解压到当前文件夹下jdk_1.7.11的文件夹
tar zxvf jdk-7u11-linux-i586.tar.gz
#移动解压出来的文件夹到目标目录,
sudo mv jdk_1.7.11 /opt/java
```
##配置环境变量
用自己常用的编辑器加上sudo打开配置文件，说明下配置文件的区别

+ `/etc/profile` :此文件为系统的每个用户设置环境信息,当用户第一次登录时,该文件被执行. 并从/etc/profile.d目录的配置文件中搜集shell的设置. 
+ `/etc/bashrc`:为每一个运行bash shell的用户执行此文件.当bash shell被打开时,该文件被读取. 
+ `~/.bash_profile`:每个用户都可使用该文件输入专用于自己使用的shell信息,当用户登录时,该文件仅仅执行一次!默认情况下,他设置一些环境变量,执行用户的.bashrc文件. 
+ `~/.bashrc`:该文件包含专用于你的bash shell的bash信息,当登录时以及每次打开新的shell时,该文件被读取. 

我比较喜欢修改：
```
sudo gedit /etc/profile
```
并在最后添加如下内容（里面javahome和jrehome则改成你的目标文件夹）：
```
export JAVA_HOME=/opt/java
export JRE_HOME=/opt/java/jre
export CLASSPATH=.:$JAVA_HOME/lib:$JRE_HOME/lib:$CLASSPATH
export PATH=$JAVA_HOME/bin:$JRE_HOME/bin:$PATH
```
重启后，在终端输入`java -version`查看java的版本号。如果正确，则设置成功了。

---

#安装Eclipse
eclipse的下载地址
http://www.eclipse.org/downloads/
选择自己需要的版本下载，本文以`eclipse-jee-juno-SR1-linux-gtk.tar.gz`为例
##解压
直接解压到`/opt/eclipse`文件夹
```
sudo tar zxvf eclipse-jee-juno-SR1-linux-gtk.tar.gz -C /opt/eclipse
```
cd到文件夹，运行`./eclipse`就可以直接运行eclipse了。
#配置桌面启动器
在桌面创建`Eclipse.desktop`文件，输入以下：
```
[Desktop Entry]
Categories=Development;
Comment[zh_CN]=
Comment=
# 下面的可执行文件路径改成你自己的目标路径
Exec=env UBUNTU_MENUPROXY= /opt/eclipse/eclipse
GenericName[zh_CN]=IDE
GenericName=IDE
# 图标文件也是，eclipse默认有
Icon=/opt/eclipse/icon.xpm
MimeType=
Name[zh_CN]=eclipse
Name=eclipse
Path=
StartupNotify=true
Terminal=false
Type=Application
X-DBUS-ServiceName=
X-DBUS-StartupType=
X-KDE-SubstituteUID=false
X-KDE-Username=owen
```
保存后通过`chmod a+wx Eclipse.desktop`来增加文件的可读可执行权限，右键从属性里修改也可以。
![](http://www.findspace.name/wp-content/uploads/2015/06/eclipsedesktop.png)



