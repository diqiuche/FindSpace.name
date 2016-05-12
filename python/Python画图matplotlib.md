#官网
>matplotlib.org

Tutorial
http://matplotlib.org/1.5.1/users/pyplot_tutorial.html

截止2016.1.14,最新稳定版为1.5.1
#安装
Ubuntu下直接
```
sudo apt-get install python3-matplotlib
```
或者pip3(Python3)安装
```
sudo pip3 install matplotlib
```
运行下面的样例代码的时候，提示你缺什么库，就用pip3或者apt安装什么库就行。
#入门
##做一个简单的折线图,
```
#!/bin/env python3

import matplotlib.pyplot as plt

def draw():
    plt.plot([1.3, 6.4, 2.8, 4])
    plt.ylabel("test numbers")
    plt.show()

draw()
```
##代码说明：
这是画折线图，[plot函数官方文档里的说明][1]，（官方手册是最有用的东西，一定不要忽视），在这个样例代码里，plot函数只传入了一个list，默认x轴的间距是1.0,设置y轴的说明文字是`test numbers`
当然可以设置对应的(x,y)这样形式的点。后面的章节说明。
##运行生成图
![][0]

[0]: http://www.findspace.name/wp-content/uploads/2016/01/matplotlibExample1.png
[1]: http://matplotlib.org/1.5.1/api/pyplot_api.html#matplotlib.pyplot.plot