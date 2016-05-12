#Pre
Wrodpress友情链接多起来之后，如果主题的效果不好，很容易就影响版面。
通过修改style.css样式表即可。
修改完成之后的效果就是现在右边侧边栏友情链接的效果。
#代码
找到主题文件夹下的`style.css`
ftp下载下来，用编辑器搜索到`blogroll`,
```css
.blogroll {
    width:100%;
    display:block;
    overflow:auto;
    zoom:1;
    height:400px; 
    overflow-y:auto; 
}

ul.xoxo .blogroll {}

ul.xoxo .blogroll {}
.blogroll li {
    float:left;
    width:50%;
    display:block;
    font-size: 10pt;
}
```
如果没有搜索到，则直接复制粘贴上面的内容到style.css里面即可。
这是wordpress默认的友情链接类名。
如果使用的主题有，则一般情况下只需要添加`height:400px`即可。
#Reference：
感谢anotherhome的指导。
