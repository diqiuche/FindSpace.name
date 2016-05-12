#Pre
不深入了解，只说明满足实验要求的。
实验中要求按钮对应事件是旋转和前进后退。
#代码
```
static GLfloat spin=0.0;
static GLfloat movtion=0.0;
static void key(unsigned char key, int x, int y)
{
    switch (key)
    {
    case 27 :
    case 'q':
        exit(0);
        break; //按ESC键(ASCII码为27)和q键为退出
    case 'a':
            spin>360?spin-=360:spin+=2;
                glutPostRedisplay();
        break;
    case 'd':
            spin<0?spin+=360:spin-=2;
                glutPostRedisplay();
        break;
    case 'w':
        movtion=movtion>8?movtion-=8:movtion+=0.2;
        glutPostRedisplay();                //标记当前窗口需要重绘，否则不会旋转
        break;
    case 's':
        movtion=movtion<0?movtion+=8:movtion-=0.2;
        glutPostRedisplay();                //标记当前窗口需要重绘，否则不会旋转
        break;
}
```
spin是旋转量，
movtion是移动量.
则在display中，相应的旋转和移动函数即可
```
 //交互
    //旋转一定的角度
    glRotatef(spin,0,0,1);
    glTranslatef(0.0, movtion, 0.0);
```
注意main中`glutKeyboardFunc(key);`绑定监听函数。