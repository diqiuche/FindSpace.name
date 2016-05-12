#Pre
linux用户必备，将当前目录下的所有文件都转换成utf8编码格式。

新建sh文件，粘贴进去，然后修改下运行权限，跑一遍就行了。
#Code
```
for i in *
do
if test -f $i
then
iconv -f gbk -t utf8 $i -o /tmp/$i.new
cp /tmp/$i.new $i
rm /tmp/$i.new
fi
done
```
#注意
只能在当前目录下，而且当前目录的文件必须都是文本，否则会出错。如果是单个文件可以这样写：
```
iconv -f gbk -t utf8 文件名 -o 要保存的文件名
```