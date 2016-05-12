#Pre
![][1]
自从换了主题，决定用markdown写文章之后，就再也不用原来的使用网站转换代码成高亮了然后插入文本了。但是新主题对代码效果不好，纠结。
终于在使用Remarkable这款linux下的markdown编辑器的时候，发现可以保存成有代码高亮格式的网页，我以为是通过css样式，查看了下源码，原来是利用一个挂在网上的js脚本。Great！

一看到前面部分我就明白了，
#开始动手：
##复制link和script部分：
```
<link href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/styles/github.min.css" rel="stylesheet"/>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/highlight.min.js">
  </script>
```
后来发现直接添加这个就是在作死，拉低博客的访问速度，从[百度网盘][2]的分享下载下来，然后上传到自己博客，改成自己博客的链接就可以了。
在主题文件夹中找到header.php，添加到`</header>`之前都可以。
##复制脚本函数调用部分：
```
<script>
   hljs.initHighlightingOnLoad();
  </script>
```
找到footer.php，添加到`</body>`之前
##上传覆盖，[删除缓存，如果你用了wp-super cache之类的话]，刷新网页，ok了！

#PS：
这个比用插件，或者自己修改css简单多了！
不过注意的是：
如果你是用markdown写，代码要用
\`\`\`
\`\`\`
括起来，如果直接wordpress自带，点插入代码，或者在文本中用```<code></code>```括起来。

[1]: http://www.findspace.name/wp-content/uploads/2015/06/wordpressCodeHighLigh.png "wordpressCodeHigh"
[2]: http://pan.baidu.com/s/1jGADiSY "两个文件"