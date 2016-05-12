#Pre
在gridview中，选中的区块变色功能的实现。
#之前的效果
![][0]
#选中以后
![][1]
同样，取消选择以后，红色背景又变回白色。
#需要在原有工程上修改的地方

在重写的baseadapter里getview里面加上
```
public View getView(int position, View convertView, ViewGroup parent) {
        
              。。。。。。。。。
              。。。。。。。。。
            //这里的getIsSelected返回的是记录checkbox选中状态的数组
        if(getIsSelected().get(position)){
            convertView.setBackgroundColor(Color.RED);
        }else{
            convertView.setBackgroundColor(Color.WHITE);
            
        }
        
        return convertView;
    }
```
此处的color也可以改成自定义的背景，调用setbackground方法

在`gridviewactivity`里，`setOnItemClickListener`中记得一定要`notifydatasetchanged`
```
listview.setOnItemClickListener(new OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                    int position, long id) {
                。。。。。。。。。。。。
                mAdapter.notifyDataSetChanged();
                。。。。。。。。
                
            }
            
        });
```

不然的话，选中是没有效果的。

#UPDATE 15. 三月 2016 
代码从原来的项目里抽出来整理了下，push到github上去了。
原来截图里的顶部文字“已选中XX项”先不整理了。很简单的功能，可以自己实现下。下次再push的话，会加上gridview 中使用textedit。
[github地址](https://github.com/FindHao/GridViewExample)
有些地区上不去github。同步到了国内的git平台：
[开源中国地址](http://git.oschina.net/findspace/GridViewExample)
[coding.net地址](https://coding.net/u/findspace/p/GridViewExample/git)

[0]: http://www.findspace.name/wp-content/uploads/2015/06/gridview1.png "未选择状态"
[1]: http://www.findspace.name/wp-content/uploads/2015/06/gridview2.png "选中状态"