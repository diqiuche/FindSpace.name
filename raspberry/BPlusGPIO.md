#树莓派B+GPIO
GPIO的接口定义,可通过执行`gpio readall`:
```
 +-----+-----+---------+------+---+--B Plus--+---+------+---------+-----+-----+
 | BCM | wPi |   Name  | Mode | V | Physical | V | Mode | Name    | wPi | BCM |
 +-----+-----+---------+------+---+----++----+---+------+---------+-----+-----+
 |     |     |    3.3v |      |   |  1 || 2  |   |      | 5v      |     |     |
 |   2 |   8 |   SDA.1 |   IN | 1 |  3 || 4  |   |      | 5V      |     |     |
 |   3 |   9 |   SCL.1 |   IN | 1 |  5 || 6  |   |      | 0v      |     |     |
 |   4 |   7 | GPIO. 7 |   IN | 0 |  7 || 8  | 1 | ALT0 | TxD     | 15  | 14  |
 |     |     |      0v |      |   |  9 || 10 | 1 | ALT0 | RxD     | 16  | 15  |
 |  17 |   0 | GPIO. 0 |   IN | 0 | 11 || 12 | 0 | IN   | GPIO. 1 | 1   | 18  |
 |  27 |   2 | GPIO. 2 |   IN | 0 | 13 || 14 |   |      | 0v      |     |     |
 |  22 |   3 | GPIO. 3 |   IN | 0 | 15 || 16 | 0 | IN   | GPIO. 4 | 4   | 23  |
 |     |     |    3.3v |      |   | 17 || 18 | 0 | IN   | GPIO. 5 | 5   | 24  |
 |  10 |  12 |    MOSI |   IN | 0 | 19 || 20 |   |      | 0v      |     |     |
 |   9 |  13 |    MISO |   IN | 0 | 21 || 22 | 0 | IN   | GPIO. 6 | 6   | 25  |
 |  11 |  14 |    SCLK |   IN | 0 | 23 || 24 | 1 | IN   | CE0     | 10  | 8   |
 |     |     |      0v |      |   | 25 || 26 | 1 | IN   | CE1     | 11  | 7   |
 |   0 |  30 |   SDA.0 |   IN | 1 | 27 || 28 | 1 | IN   | SCL.0   | 31  | 1   |
 |   5 |  21 | GPIO.21 |   IN | 1 | 29 || 30 |   |      | 0v      |     |     |
 |   6 |  22 | GPIO.22 |   IN | 1 | 31 || 32 | 0 | IN   | GPIO.26 | 26  | 12  |
 |  13 |  23 | GPIO.23 |   IN | 0 | 33 || 34 |   |      | 0v      |     |     |
 |  19 |  24 | GPIO.24 |   IN | 0 | 35 || 36 | 0 | IN   | GPIO.27 | 27  | 16  |
 |  26 |  25 | GPIO.25 |   IN | 1 | 37 || 38 | 0 | IN   | GPIO.28 | 28  | 20  |
 |     |     |      0v |      |   | 39 || 40 | 0 | IN   | GPIO.29 | 29  | 21  |
 +-----+-----+---------+------+---+----++----+---+------+---------+-----+-----+
 | BCM | wPi |   Name  | Mode | V | Physical | V | Mode | Name    | wPi | BCM |
 +-----+-----+---------+------+---+--B Plus--+---+------+---------+-----+-----+

```
这里有这样几列：

+ `BCM`：这个pin脚在`Broadcom SOC channel`中的序号
+ `wPi`: 这个pin脚在wiringPi中的序号
+ `Name`:这个pin脚的名字
+ `Mode`: 这个pin脚当前的模式
+ `V`：这个pin脚当前的电压是高电平(1)还是低电平(0)
+ `Physical`:这个pin脚在板子上的物理序号

#观察的顺序
![][1]
按照如图拜访，则pin脚的顺序就跟前面打印出来的结果一样。左上角是1,从左到右，从上到下的顺序遍历。
我直接买的[这种扩展板（戳链接跳过去）][2]，不用自己一个一个连到面包板上，直接在面包板上安上这个GPIO扩展板，然后一根排线接过去，而且上面还自己标好了GPIO口的名字。

#可用的库
常用的有两种

##wiringPi
WiringPi是应用于树莓派平台的GPIO控制库函数，WiringPi中的函数类似于Arduino的wiring系统。官网：
http://wiringpi.com/ 
c语言
##RPi.GPIO
python的。最常用，最简单。我后面的一些文章写的代码，主要以这个为主。

还有个java的就不说了，它是基于wiringPi的。

#面包板
[面包板就用这样普通的就行（戳链接）][3],注意面包板的连接：
![][4]
如图所示，图中一个颜色的线上，在底部实际上是连在一起的。
最上面和最下面，一个大行是连在一起的，中间两块大的，则是五个小孔一根线连在一起的。






[0]: http://raspberrypi.stackexchange.com/questions/12966/what-is-the-difference-between-board-and-bcm-for-gpio-pin-numbering 
[1]: http://www.findspace.name/wp-content/uploads/2015/12/piBPlus.jpg
[2]: http://redirect.simba.taobao.com/rd?w=unionnojs&f=http%3A%2F%2Fai.taobao.com%2Fauction%2Fedetail.htm%3Fe%3D0SF334zz9qjuDAZjWhpTWEKDbck%252Fs9BdfbQtVPYapgFBWJVBnwmj7tnO073KpEUuesayvrQ7hvmI%252B6SsYddQHqzdQULRBtuth53ypyukiCeKFhsx3kELq1Rmtaud%252B0v%252B%252FGBQJhSgwZuXjHmUHTWI5A%253D%253D%26ptype%3D100010%26from%3Dbasic&k=5ccfdb950740ca16&c=un&b=alimm_0&p=mm_32131183_8764411_43660960
[3]: http://redirect.simba.taobao.com/rd?w=unionnojs&f=http%3A%2F%2Fai.taobao.com%2Fauction%2Fedetail.htm%3Fe%3D8LVKbm7uPY%252B6k0Or%252B%252BH4tCLO%252FsivzajjokW4bPA0wMWLltG5xFicOdXrTUTgh9sMDPIwxrc30riUoQH65Fgld08B2iYKuIYUF6hBXFXX7qycWoUMOCIYPG3abJM7sDg2Nln9Dm4BLEBvhxoo0JDTCQ%253D%253D%26ptype%3D100010%26from%3Dbasic&k=5ccfdb950740ca16&c=un&b=alimm_0&p=mm_32131183_8764411_43660960
[4]: http://www.findspace.name/wp-content/uploads/2015/12/mianbaoban.png