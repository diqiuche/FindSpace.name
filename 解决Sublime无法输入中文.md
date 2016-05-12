#Pre
sublime是一款非常优秀的跨平台编辑器，但是默认是不能输入中文的。这里给出解决方案。

本经验目前在Ubuntu14.04环境下，已有搜狗输入法 for Linux和Sublime Text 3的情况下安装成功。
##保存下面的代码到文件sublime_imfix.c
```
/*
sublime-imfix.c
Use LD_PRELOAD to interpose some function to fix sublime input method support for linux.
By Cjacker Huang <jianzhong.huang at i-soft.com.cn>

gcc -shared -o libsublime-imfix.so sublime_imfix.c  `pkg-config --libs --cflags gtk+-2.0` -fPIC
LD_PRELOAD=./libsublime-imfix.so sublime_text
*/
#include <gtk/gtk.h>
#include <gdk/gdkx.h>
typedef GdkSegment GdkRegionBox;

struct _GdkRegion
{
  long size;
  long numRects;
  GdkRegionBox *rects;
  GdkRegionBox extents;
};

GtkIMContext *local_context;

void
gdk_region_get_clipbox (const GdkRegion *region,
            GdkRectangle    *rectangle)
{
  g_return_if_fail (region != NULL);
  g_return_if_fail (rectangle != NULL);

  rectangle->x = region->extents.x1;
  rectangle->y = region->extents.y1;
  rectangle->width = region->extents.x2 - region->extents.x1;
  rectangle->height = region->extents.y2 - region->extents.y1;
  GdkRectangle rect;
  rect.x = rectangle->x;
  rect.y = rectangle->y;
  rect.width = 0;
  rect.height = rectangle->height; 
  //The caret width is 2; 
  //Maybe sometimes we will make a mistake, but for most of the time, it should be the caret.
  if(rectangle->width == 2 && GTK_IS_IM_CONTEXT(local_context)) {
        gtk_im_context_set_cursor_location(local_context, rectangle);
  }
}

//this is needed, for example, if you input something in file dialog and return back the edit area
//context will lost, so here we set it again.

static GdkFilterReturn event_filter (GdkXEvent *xevent, GdkEvent *event, gpointer im_context)
{
    XEvent *xev = (XEvent *)xevent;
    if(xev->type == KeyRelease && GTK_IS_IM_CONTEXT(im_context)) {
       GdkWindow * win = g_object_get_data(G_OBJECT(im_context),"window");
       if(GDK_IS_WINDOW(win))
         gtk_im_context_set_client_window(im_context, win);
    }
    return GDK_FILTER_CONTINUE;
}

void gtk_im_context_set_client_window (GtkIMContext *context,
          GdkWindow    *window)
{
  GtkIMContextClass *klass;
  g_return_if_fail (GTK_IS_IM_CONTEXT (context));
  klass = GTK_IM_CONTEXT_GET_CLASS (context);
  if (klass->set_client_window)
    klass->set_client_window (context, window);

  if(!GDK_IS_WINDOW (window))
    return;
  g_object_set_data(G_OBJECT(context),"window",window);
  int width = gdk_window_get_width(window);
  int height = gdk_window_get_height(window);
  if(width != 0 && height !=0) {
    gtk_im_context_focus_in(context);
    local_context = context;
  }
  gdk_window_add_filter (window, event_filter, context); 
}
```

##安装C/C++的编译环境和gtk libgtk2.0-dev
```
sudo apt-get install build-essential
sudo apt-get install libgtk2.0-dev
```
（我的ubuntu14.04已经有了build-essential，只需要安装第二个）

##编译
```
gcc -shared -o libsublime-imfix.so /home/chen/sublime_imfix.c  `pkg-config --libs --cflags gtk+-2.0` -fPIC
```
编译完成后，会在同一目录下生成一个libsublime-imfix.so，将它复制到sublime的安装目录。
我的sublime 是用deb包安装的，安装在/opt/sublime_text/下面，拷入后即有/opt/sublime_text/libsublime-imfix.so
##测试

在上面步骤完成以后，我们来测试一下，是否可以输入中文在终端输入

>LD_PRELOAD=./libsublime-imfix.so subl

##更改快捷方式

用命令行启动不方便，我们进行下一步
```
cd /usr/share/applications
cp sublime_text.desktop sublime_text.desktopback
 //备份一下
sudo vim sublime_text.desktop
```
打开以后是这样
```
[Desktop Entry]
Version=1.0
Type=Application
Name=Sublime Text
GenericName=Text Editor
Comment=Sophisticated text editor for code, markup and prose
Exec=/opt/sublime_text/sublime_text %F
Terminal=false
MimeType=text/plain;
Icon=sublime-text
Categories=TextEditor;Development;
StartupNotify=true
Actions=Window;Document;

[Desktop Action Window]
Name=New Window
Exec=/opt/sublime_text/sublime_text -n
OnlyShowIn=Unity;

[Desktop Action Document]
Name=New File
Exec=/opt/sublime_text/sublime_text --command new_file
OnlyShowIn=Unity;
```
我们修改掉所有带Exec的参数

把/opt/sublime_text/sublime_text 修改为 bash -c 'LD_PRELOAD=/opt/sublime_text/libsublime-imfix.so /opt/sublime_text/sublime_text'

PS：这里的两个路径都是/opt/sublime_text/libsublime-imfix.so和/opt/sublime_text/sublime_text，都是我的路径，一个是库的路径，一个是sublime的安装路径，大家照着自己的路径改

改完以后是这样的
```
[Desktop Entry]
Version=1.0
Type=Application
Name=Sublime Text
GenericName=Text Editor
Comment=Sophisticated text editor for code, markup and prose
Exec=bash -c "LD_PRELOAD=/opt/sublime_text/libsublime-imfix.so /opt/sublime_text/sublime_text %F"
Terminal=false
MimeType=text/plain;
Icon=sublime-text
Categories=TextEditor;Development;
StartupNotify=true
Actions=Window;Document;

[Desktop Action Window]
Name=New Window
Exec=bash -c "LD_PRELOAD=/opt/sublime_text/libsublime-imfix.so /opt/sublime_text/sublime_text -n"
OnlyShowIn=Unity;

[Desktop Action Document]
Name=New File
Exec=bash -c "LD_PRELOAD=/opt/sublime_text/libsublime-imfix.so /opt/sublime_text/sublime_text --command new_file"
OnlyShowIn=Unity;
```
改了三处。注意使用双引号，**不然没法打开带空格的文件**。win下参考
https://www.v2ex.com/t/156950
然后保存
ok
##添加命令
```
touch /usr/bin/sublime
vim /usr/bin/sublime
```
内容如下：
```
#!/bin/bash
bash -c "LD_PRELOAD=/opt/sublime_text/libsublime-imfix.so /opt/sublime_text/sublime_text $1"
```
记得最后要`chmod a+x /usr/bin/sublime`
则以后可以通过命令行输入  sublime来启动可以输入中文的sublime

##~~问题：输入的时候，输入法不跟随，装了imesupport也没用~~
##问题解决：
[SUBLIME LINUX下中文输入框不跟随问题解决](http://www.findspace.name/res/1223)
#Reference
本文转载代码部分并添加了自己的配置，你也可以参照这个百度经验：
http://jingyan.baidu.com/article/f3ad7d0ff8731609c3345b3b.html

