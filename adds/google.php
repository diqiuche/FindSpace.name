<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<center>
	<title>Google镜像列表</title>
</center>
</head>
<body>
<center>
<H1>Google镜像列表</H1>
<h2>仅供学术交流使用</h2>
<h2>欢迎收藏该网页<br>
(http://www.findspace.name/adds/google.php)<br>
不定期刷新</h2>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Google镜像 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8527554614606787"
     data-ad-slot="2158499150"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<p>如果一个不行，多打开几个试试，按下鼠标滚轮可直接在新窗口打开。感谢点击广告支持！</p>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- googleMirror -->
<ins class="adsbygoogle"
     style="display:inline-block;width:200px;height:90px"
     data-ad-client="ca-pub-8527554614606787"
     data-ad-slot="6805869955"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

<?php 
$url = "https://github.com/greatfire/wiki/wiki/google"; 
$contents = file_get_contents($url); 
$regex = '/(\<li\>\<a[\s\S]+?)\<\/ul\>/';
$matches = array();  
if(preg_match($regex,$contents,$matches)){
	echo($matches[1]) ;
}
?>
<p>上面的链接每隔几分钟会刷新一次，注意保存。</p>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 404page -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8527554614606787"
     data-ad-slot="9681765956"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</center>

<p>copyright <a href="http://www.findspace.name">FindSpace</a></p>
</body>
</html>
