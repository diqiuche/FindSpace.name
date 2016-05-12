#Pre
最近心血来潮，把Ubuntu的语言设置成了英文，发现Chrome的部分网页中文字体发虚，效果图如下：
![Ubuntu Chrome 字体发虚][0]
这是从论坛找的图，我的显示是许多中文默认成了宋体，而且有些字体大小不一样，歪歪扭扭的，特别难受。
#解决方法
网上有一些解决方法，比如设置chrome的字体等等，但是我因为迷恋一款叫Source Code Pro的字体（免费字体，github上开源），等宽，非常舒服，我设置的所有字体都是它，但是没用。
我用Ubuntu Tweak设置的字体默认也是Source Code Pro，也没用。
也找到了一个写一个简单的插件，然后设置的。同样不生效，最后在论坛发现了这样的设置步骤：
##字体配置文件
` /etc/fonts/conf.avail/69-language-selector-zh-cn.conf `这个文件是配置中文字体的，修改它
`sudo gedit /etc/fonts/conf.avail/69-language-selector-zh-cn.conf`

替换为下面的代码：
```
<?xml version="1.0"?>
<!DOCTYPE fontconfig SYSTEM "fonts.dtd">
<fontconfig>

  <match target="pattern">
    <test qual="any" name="family">
      <string>serif</string>
    </test>
    <edit name="family" mode="prepend" binding="strong">
      <string>DejaVu Serif</string>
      <string>Bitstream Vera Serif</string>
      <string>HYSong</string>
      <string>AR PL UMing CN</string>
      <string>AR PL UMing HK</string>
      <string>AR PL ShanHeiSun Uni</string>
      <string>AR PL New Sung</string>
      <string>WenQuanYi Bitmap Song</string>
      <string>AR PL UKai CN</string>
      <string>AR PL ZenKai Uni</string>
    </edit>
  </match>
  <match target="pattern">
    <test qual="any" name="family">
      <string>sans-serif</string>
    </test>
    <edit name="family" mode="prepend" binding="strong">
      <string>DejaVu Sans</string>
      <string>Bitstream Vera Sans</string>
      <string>WenQuanYi Micro Hei</string>
      <string>WenQuanYi Zen Hei</string>
      <string>Droid Sans Fallback</string>
      <string>HYSong</string>
      <string>AR PL UMing CN</string>
      <string>AR PL UMing HK</string>
      <string>AR PL ShanHeiSun Uni</string>
      <string>AR PL New Sung</string>
      <string>AR PL UKai CN</string>
      <string>AR PL ZenKai Uni</string>
    </edit>
  </match>
  <match target="pattern">
    <test qual="any" name="family">
      <string>monospace</string>
    </test>
    <edit name="family" mode="prepend" binding="strong">
      <string>DejaVu Sans Mono</string>
      <string>Bitstream Vera Sans Mono</string>
      <string>WenQuanYi Micro Hei Mono</string>
      <string>WenQuanYi Zen Hei Mono</string>
      <string>Droid Sans Fallback</string>
      <string>HYSong</string>
      <string>AR PL UMing CN</string>
      <string>AR PL UMing HK</string>
      <string>AR PL ShanHeiSun Uni</string>
      <string>AR PL New Sung</string>
      <string>AR PL UKai CN</string>
      <string>AR PL ZenKai Uni</string>
    </edit>
  </match>

</fontconfig>
```
你可以查看原来的内容，自从Ubuntu13.04开始中文默认字体应该是WenQuanYi Zen Hei，修改里面的WenQuanYi Zen Hei为你喜欢的字体就可以。
##主要是删掉
```
 <test name="lang">
            <string>zh-cn</string>
        </test>
```
部分。

##推荐直接用上面的配置覆盖源文件
刷新字体缓存`sudo fc-cache -vf`
或者注销重新登录下就好了。


#Reference
http://forum.ubuntu.org.cn/viewtopic.php?f=48&t=422887
http://www.ubuntukylin.com/ukylin/forum.php?mod=viewthread&tid=20023
http://forum.ubuntu.org.cn/viewtopic.php?f=73&t=266229&start=0

[0]: http://www.findspace.name/wp-content/uploads/2015/06/UbuntuChromeFonts.png "字体发虚"