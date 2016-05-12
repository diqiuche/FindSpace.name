#Pre
在linux下面用过android studio的都知道，它的界面看起来很不正常，感觉各种锯齿。
#设置

+ 使用Oracle JDK([安装参考][0])，卸载自带的OpenJDK(`sudo apt-get purge openjdk-\*`)
+ 根据[官方提供的个人设置文件放置位置][1],在位置下新建文件来覆盖默认设置，比如Linux下在`～/.AndroidStudio1.4/`,新建`studio64.vmoptions`（32位则去掉文件名里的64），将下面的设置内容添加进去：
```
-Dawt.useSystemAAFontSettings=on
-Dswing.aatext=true
-Dsun.java2d.xrender=true
```
+ 在`Setting-->Editor-->Font`单击`save as`新建一个自定义的样式，选择自己想要的字体，同时测试效果。
我一直都比较喜欢[`Source Code Pro`][2]这款开源的等宽字体，非常好看~
![][3]

#Reference
[How to fix font anti-aliasing in IntelliJ IDEA when using high DPI?](http://superuser.com/questions/614960/how-to-fix-font-anti-aliasing-in-intellij-idea-when-using-high-dpi)


[0]: www.findspace.name/easycoding/155
[1]: http://tools.android.com/tech-docs/configuration
[2]: https://github.com/adobe-fonts/source-code-pro
[3]: http://www.findspace.name/wp-content/uploads/2015/11/androidstudioFonts.png