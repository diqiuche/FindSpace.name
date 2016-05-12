#函数定义
plot函数是用来画点的，当然也可以画线。
```
matplotlib.pyplot.plot(*args, **kwargs)
Plot lines and/or markers to the Axes. args is a variable length argument, allowing for multiple x, y pairs with an optional format string. For example, each of the following is legal:

plot(x, y)        # plot x and y using default line style and color
plot(x, y, 'bo')  # plot x and y using blue circle markers
plot(y)           # plot y using x as index array 0..N-1
plot(y, 'r+')     # ditto, but with red plusses
```

#参数说明
英文很简单，不详细注释，主要两类，style是线的样式，marker是点的样式。

|character|description|
|-|-|
|'-'|solid line style|
|'--'|dashed line style|
|'-.'|dash-dot line style|
|':'|dotted line style|
|'.'|point marker|
|','|pixel marker|
|'o'|circle marker|
|'v'|triangle_down marker|
|'^'|triangle_up marker|
|'<'|triangle_left marker|
|'>'|triangle_right marker|
|'1'|tri_down marker|
|'2'|tri_up marker|
|'3'|tri_left marker|
|'4'|tri_right marker|
|'s'|square marker|
|'p'|pentagon marker|
|'*'|star marker|
|'h'|hexagon1 marker|
|'H'|hexagon2 marker|
|'+'|plus marker|
|'x'|x marker|
|'D'|diamond marker|
|'d'|thin_diamond marker|
|'|'|vline marker|
|'_'|hline marker|

颜色的设置：

|character|color|
|-|-|
|‘b’|blue|
|‘g’|green|
|‘r’|red|
|‘c’|cyan|
|‘m’|magenta|
|‘y’|yellow|
|‘k’|black|
|‘w’|white|

#样例代码
```
#!/usr/bin/env python3

import matplotlib.pyplot as plt

def draw():
    plt.plot([1,2,3], [1,2,3], 'go-', label='line 1', linewidth=2)
    plt.plot([1,2,3], [1,4,9], 'rv',  label='line 2')
    plt.axis([0, 4, 0, 10])
    plt.legend()
    plt.show()

draw()
```
代码说明，
两个plot就画了一条线和一组散列点，如果不指定线的类型，那么默认就是散列点。
`axis`函数则指定了x,y坐标的范围，如果去掉，默认是点的坐标极值为范围。
`legend`函数的调用则表示显示图例说明。
`show`则是把图片显示出来