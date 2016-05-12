#PRE
有广告屏蔽就有反广告屏蔽，对于广大小站长来说，广告是站的收入主要来源。但是随着民众能力的提高，以及百度这个大流氓的不要脸，越来越多的人使用各种广告屏蔽插件和软件。
我强烈谴责百度广告联盟，百度的广告不限个数不限形式，甚至官方推荐各种弹窗形式的广告，而且匹配出来的内容大多数都是不堪入目的，根本做不到精准推广。
反观google的adsense，广告数量明确要求，每个页面不得超过三个图文形式的，不得超过三个纯文字链接的。广告形式也只有内嵌的图文，而且不得粘贴在自建的iframe（可以通过这种形式进行弹窗）。而且推荐的广告能很好的反应你的搜索历史。
**广告本身无害，有害的是投放广告的形式和数量。**
合适的广告投放甚至能提升用户体验。
#wordpress上反广告屏蔽的插件
我主要用过`Adblock Notify by b*web`和`Ad Blocking Detector`，但是第一个并不好用，尽管它能自动完成很多功能，但是它并不能检测出对广告div进行假填充的广告屏蔽。所以这里主要介绍如何`Ad Blocking Detector`。
它主要是通过短码的形式，短码是wordpress的一个小特性，可以通过短码做很多事情。
在文章中使用短码只需要写内容
```
[yourShortCode]
```
就可以调用你自己在functions或者插件里生成的短码。
然而短码默认只在文章内容里生效，本文将提供详细步骤如何在整个主题范围生效。

#Ad Blocking Detector
安装完成后左侧会出现`Ad Blocking`的菜单，主要有Getting Started、Manage Shortcodes、Add New Shortcode、Advanced Settings、Statistics、Report a Problem / Debug这几个功能页。这个插件还会要求安装它的伴随插件`Ad Blocking Detector - Block List Countermeasure`来统计数据。
主要就是Manage Shortcodes、Add New Shortcode两个功能页。
##Manage Shortcodes
是对你已经新建的短码进行管理的。
你新建之后，这里会展示的内容格式如下：
![][0]

|参数|意义|
|-|-|
|**Get This Shortcode**|获取真正短码的代码,格式如下：![][1]|
|Name / Description|你新建的短码的名字|
|No Ad Blocker Detected Content|没有检测到广告被屏蔽时显示的内容，即你的广告|
|Ad Blocker Detected Content|当你的广告被屏蔽时显示的内容，注意**不支持高级代码，不能调用js等，仅可显示提示等简单文字**|
|User-Defined Wrapper CSS Selectors|忽略吧|

##Add New Shortcode
这里的内容则相对应manage。按照上面的说明填写就行，填完之后就会出现在manage里。


##侧边栏
如果是侧边栏的广告模块，ABD已经提供了封装好的模块，在插件后台新建广告模块，然后用新的小工具替换你原来的，在这个小工具里选择你的shortcode就行。
#应用到整个主题
短码是无法在除了文章内容以外的地方直接使用的，但是可以通过调用函数的方法来做。
比如我的广告添加在了`footer.php`里，那么就在abd插件里新建广告短码，然后在`footer.php`把广告内容替换成
```
<?php echo do_shortcode("用修改过的短码替换这里"); ?>
```
**上面的格式有错误**。根据你自己的改，注意，默认生成的短码是![][1]，带有双引号，**需要在短码的双引号前添加`\`转移符告诉php这个`"`是我字符串里面的**。也就是说，上面的`"568ef82"`应该改成`\"568ef72\"`
由于我开启了这个插件，没法在文章里写这样的格式，所以我是截的图片贴上去的。
如果主题自带广告模块，请自己测试能不能在主题选项的广告模块里添加，否则需要直接修改主题的源代码。
#Tips
对于自己收藏的一些博客，我都会添加广告白名单，一般在插件上右键就可以添加。
拒绝百度广告&&百度，从我做起。


[0]: http://www.findspace.name/wp-content/uploads/2016/01/abd1.jpg
[1]: http://www.findspace.name/wp-content/uploads/2016/01/abd2.jpg