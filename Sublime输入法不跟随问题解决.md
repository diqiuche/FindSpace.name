![][3]
#Pre
Sublime真是太好用了，不过在linux下面中文输入法问题挺大的。
[输入中文的解决方法看这里](http://www.findspace.name/res/291)
首先是搜狗的那个linux输入法有个很大的bug，经常导致fcitx的CPU占用率飙升到100%。只能手动kill并重启fcitx，关于这个方法戳这篇文章：
[UBUNTU 搜狗输入法崩溃 FCITX崩溃无痛重启方法][1]
而且，这个输入法在sublime里，输入框是无法跟随光标的，再次输入的时候，输入框又蹦到了左下角。
#问题解决
##卸载这个包
最终放弃了搜狗输入法，使用fcitx-googlepinyin，突然发现，输入框可以记录你拖动的位置了！尽管仍然不跟随，但是你可以手动拖动输入框到一个自己感觉比较合适的位置，而不是每次都强制出现在左下角。
折腾了好久，发现是这个包的问题：
>fcitx-ui-qimpanel

装了这个包之后，会多出一套管理面板，有了一些皮肤和更详细的设置。
不过我宁可使用sublime时更爽一点。
所以：
如果你装了这个，卸载掉，注销一下，登录，打开sublime尝试一下吧。
##默认的控制面板是
```
fcitx-config-common      
fcitx-config-gtk 
fcitx-config-gtk2
```
#Tips
##云输入
`fcitx-module-cloudpinyin`这个模块可以从候选词里面给出一个联网得到的结果，在配置--》附加组件里可以设置具体信息，注意设置成百度，google速度有点慢，即使你用了[这个方法][2]来上google
##Windows下
一个日本开发者写了个插件：
IMESupport
直接在sublime里搜索即可


#UPDATE
此方法并不可以。。

[1]: http://www.findspace.name/res/786 "UBUNTU 搜狗输入法崩溃 FCITX崩溃无痛重启方法"
[2]: http://www.findspace.name/res/72 "修改hosts上Google"
[3]: http://www.findspace.name/wp-content/uploads/2015/06/fcitx.png "Fcitx"