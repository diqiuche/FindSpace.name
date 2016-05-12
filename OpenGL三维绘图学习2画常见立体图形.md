#Pre
有同学用的是手算看到图形的比例，然后给出一个正方体的参数（约定好某个点+边长）来画，我偷懒直接用库函数。
![][1]
#库函数
```
//球  
glutWireSphere(8.0f,20,20);  
glutSolidSphere(8.0f,20,20);  
//锥体  
glutWireCone(4.0f,8.0f,20,20);  
glutSolidCone(4.0f,8.0f,20,20);  
//立体  
glutWireCube(8.0f);  
glutSolidCube(8.0f);  
//甜圈  
glutWireTorus(3.0f,6.0f,20,20);  
glutSolidTorus(3.0f,6.0f,20,20);  
//十六面体，默认半径1.0  
glScalef(6.0f,6.0f,6.0f);//x,y,z轴均放大6倍  
glutWireDodecahedron();  
glutSolidDodecahedron();  
//茶壶  
glutWireTeapot(8.0f);  
glutSolidTeapot(8.0f);  
//八面体，默认半径1.0  
glScalef(6.0f,6.0f,6.0f);//x,y,z轴均放大6倍  
glutWireOctahedron();  
glutSolidOctahedron();  
//四面体，默认半径1.0  
glScalef(6.0f,6.0f,6.0f);//x,y,z轴均放大6倍  
glutWireTetrahedron();  
glutSolidTetrahedron();  
//二十面体，默认半径1.0  
glScalef(6.0f,6.0f,6.0f);//x,y,z轴均放大6倍  
glutWireIcosahedron();  
glutSolidIcosahedron();  
```
gluWire\*的是线，上一节中的示例便是
gluSolid\*的是面，如本节第一个立方体的图
长方体的可以这样来做：
```
glPushMatrix();
// do the scale here
// do the draw here

glPopMatrix();
```
这个push和pop，是opengl状态机，可以这样来理解，push到栈里之后，一些列的操作矩阵，在pop之后，都会按原路返回，也就是说，此时你的绘图焦点，回到了push之前。

#Reference
[基于Glut OpenGL显示一些立体图形示例程序：](http://blog.csdn.net/yearafteryear/article/details/9174465)


[1]: http://bibei.68suo.com/twmp/fileimport/?id=7d7a1b73-3cef-49f8-8d8e-906ae7a04cbc&ext=.jpg "正方体"