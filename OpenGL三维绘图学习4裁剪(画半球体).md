#图像变换
使用opengl一定要建立一个观念，所有的变化都是矩阵变换，可以变换，也就可以还原。即push和pop matrix
#示例
```
GLdouble eqn [4]={0.0,0.0,-1.0,0.0};
glClipPlane(GL_CLIP_PLANE0,eqn);
glEnable(GL_CLIP_PLANE0);
glutSolidSphere(headR,slices,slices);
//截完了之后，再撤消,防止对其他部分产生影响。该实验的代码，我都没有用push和pop但是实际上这个功能一看就知道很强大。
glDisable(GL_CLIP_PLANE0);
```
#代码说明
`void glClipPlane(GLenum plane, const GLdouble *equation); `
定义一个裁剪平面。equation参数指向平面方程Ax + By + Cz + D = 0的4个系数。
equation=（0，-1，0,0），前三个参数（0，-1,0）可以理解为法线向下，只有向下的，即Y<0的才能显示，最后一个参数0表示从z=0平面开始。这样就是裁剪掉上半平面。
equation=（0,1,0,0）表示裁剪掉下半平面，
equation=（1,0,0,0）表示裁剪掉左半平面，
equation=（-1,0,0,0）表示裁剪掉右半平面，
equation=（0,0,-1,0）表示裁剪掉前半平面，
equation=（0,0,1,0）表示裁剪掉后半平面
#注意
由于世界坐标系与我们头脑中的坐标系不同，所以这里的x,y,z也要相应改变。用的时候写几个例子看看效果就知道了。
切割的效果看入门的那篇博客中的视频即可。
#opengl三维绘图入门系列：
[OpenGL三维绘图学习1入门](http://www.findspace.name/easycoding/1212)
[OPENGL三维绘图学习2画常见立体图形](http://www.findspace.name/easycoding/1214)
[OPENGL三维绘图学习3键盘监听](http://www.findspace.name/easycoding/1215)
[OPENGL三维绘图学习4裁剪(画半球体)](http://www.findspace.name/easycoding/1218)

#Reference
[图形学OpenGL 切割球体](http://tieba.baidu.com/p/405784883)