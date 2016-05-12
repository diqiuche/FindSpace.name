[TOC]
#Pre
五一三天假期，本来说要写图形学实验，结果前两天一直没有做，被某人说不靠谱。
>不！靠！谱！从来不适用于哥！

第三天花了一天时间来写实验，最后写了差不多吧，就是一些细节问题了。
#入门
个人感觉入门只需要搞明白两个函数：
gluPerspective和gluLookAt
请阅读此文时，抛去你前面几个实验的所有概念。
#坐标系
一般来说，建模时采用建模坐标系，然后在绘制的时候，先把建模坐标系转换到世界坐标系．
>opengl的坐标系（世界坐标系），Ｚ的正方向指向屏幕外的（属于右手坐标系），Ｘ的正方向指向右，Ｙ的正方向指向上。

这个坐标的概念非常重要，因为咱们平时画的坐标系与这个不同，而且，在opengl的自带的一些画立体图形的函数中，是按照咱们平时的坐标系（右→x 上↑z 左下↙ y轴），所以画图之前，**首先要把图形绕x轴旋转90°**
#gluPerspective
##函数原型
```
gluPerspective(GLdouble fovy,GLdouble aspect,GLdouble zNear,GLdouble zFar)

```
>
`fovy`,眼睛睁开的角度,即,视角的大小,如果设置为0,相当你闭上眼睛了,所以什么也看不到,如果为180,那么可以认为你的视界很广阔|我看到的一些代码都是设置60
`aspect`,这个好理解,就是实际窗口的纵横比,即x/y|这个比例一般是程序窗口的比例，否则就会变形
`zNear`,这个呢,表示你近处,的裁面,
`zFar`表示远处的裁面,

如图
![gluPerspective][1]
#gluLookAt
##函数原型
gluLookAt(GLdoble eyex,GLdouble eyey,GLdouble eyez,GLdouble centerx,GLdouble centery,GLdouble centerz,GLdouble upx,GLdouble upy,GLdouble upz);
>该函数定义一个视图矩阵，并与当前矩阵相乘。
第一组eyex, eyey,eyez 相机在世界坐标的位置
第二组centerx,centery,centerz 相机镜头对准的物体在世界坐标的位置
第三组upx,upy,upz 相机向上的方向在世界坐标中的方向
你把相机想象成为你自己的脑袋：
第一组数据就是脑袋的位置
第二组数据就是眼睛看的物体的位置
第三组就是头顶朝向的方向（因为你可以歪着头看同一个物体）。
##注意
如果并没有调用gluLookAt(),那么照相机就被设置为默认的位置和方向。在默认情况下，照相机位于原点，指向z轴的负方向，朝上向量为(0,1,0)。
##建议
可以修改原来的代码。把视图变换函数gluLookAt()函数，**改为模型变换函数glTranslatef(),并使用参数(0.0,0.0,-5.0)**。这个函数的效果和使用gluLookAt()函数的效果是完全相同的，原因：
gluLookAt()函数是通过移动照相机（使用试图变换）来观察这个立方体，而glTranslatef()函数是通过移动茶壶（使用模型变换）。

##示例代码
```
#include "stdafx.h"
#include <GL/glut.h>
#include <stdlib.h>

void init(void) 
{
   glClearColor (0.0, 0.0, 0.0, 0.0); //背景黑色
}

void display(void)
{
   glClear (GL_COLOR_BUFFER_BIT);
   glColor3f (1.0, 1.0, 1.0); //画笔白色

   glLoadIdentity();  //加载单位矩阵

   gluLookAt(0.0,0.0,5.0,  0.0,0.0,0.0,  0.0,1.0,0.0);
   glutWireTeapot(2);
   glutSwapBuffers();
}

void reshape (int w, int h)
{
   glViewport (0, 0, (GLsizei) w, (GLsizei) h); 
   glMatrixMode (GL_PROJECTION);
   glLoadIdentity ();
   gluPerspective(60.0, (GLfloat) w/(GLfloat) h, 1.0, 20.0);
   glMatrixMode(GL_MODELVIEW);
   glLoadIdentity();
   gluLookAt (0.0, 0.0, 5.0, 0.0, 0.0, 0.0, 0.0, 1.0, 0.0);
}
int main(int argc, char** argv)
{
   glutInit(&argc, argv);
   glutInitDisplayMode (GLUT_DOUBLE | GLUT_RGB);
   glutInitWindowSize (500, 500); 
   glutInitWindowPosition (100, 100);
   glutCreateWindow (argv[0]);
   init ();
   glutDisplayFunc(display); 
   glutReshapeFunc(reshape);
   glutMainLoop();
   return 0;
}
```
上面的display()函数中：gluLookAt(0.0,0.0,5.0, 0.0,0.0,0.0, 0.0,1.0,0.0); 相当于我们的脑袋位置在(0.0,0.0,5.0)处，眼睛望向(0.0,0.0,0.0),即原点。后面的三个参数(0.0,1.0,0.0),y轴为1，其余为0，表示脑袋朝上，就是正常的情况。看到的情况如下图：
![][2]
<sub>[2]</sub>
壶嘴在右，壶柄在坐，壶底在下，壶盖在上。
 
二、若将gluLookAt的后三个参数设置为（0.0,-1.0,0.0）,即y轴为-1,其余为0。这样表示脑袋向下，即人眼倒着看，看到的效果如下图：
![][3]
#总结：
在实际编码中，最好不要用glulookat,宁可多写一些平移和旋转也不要用，因为很容易会把自己整糊涂。
没有手动设置glulookat就是使用默认值，读者可以自己在第一个图片感受下坐标系。
#opengl三维绘图入门系列：
[OpenGL三维绘图学习1入门](http://www.findspace.name/easycoding/1212)
[OPENGL三维绘图学习2画常见立体图形](http://www.findspace.name/easycoding/1214)
[OPENGL三维绘图学习3键盘监听](http://www.findspace.name/easycoding/1215)
[OPENGL三维绘图学习4裁剪(画半球体)](http://www.findspace.name/easycoding/1218)
#Reference：
[openGL坐标系](http://blog.csdn.net/hunter8777/article/details/5890899)
[Opengl---gluLookAt函数详解](http://blog.csdn.net/ivan_ljf/article/details/8764737)

[1]: http://www.findspace.name/wp-content/uploads/2015/06/perspective.jpg "gluPerspective"
[2]: http://img.my.csdn.net/uploads/201304/06/1365245844_7068.gif "lookat函数示例图"
[3]: http://img.my.csdn.net/uploads/201304/06/1365246094_2782.gif "lookat函数示例图2"
[4]: http://www.findspace.name/wp-content/uploads/2015/06/androidRobot.mp4 "androidRobot"