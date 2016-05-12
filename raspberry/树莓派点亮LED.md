#LED最简单的点亮方式
![][0]

LED灯长的针脚是正极，连接到树莓派连接出3v的高电平，短的是负极，连接到GND（接地），此时灯就亮了。
#GPIO口连接
![][1]
此处链接到了GPIO26(在某些GPIO扩展套件上是26,在前面介绍的通过`gpio readall`读出来的是BCM26,GPIO25)
##命令行方式点亮
```
#设置管脚为输出模式，-g参数表示是以BCM编号方式，如果去掉这个参数测以wiringPi编号方式，即为25。
gpio -g mode 26 out
#设置管脚为高电平，点亮LED.
gpio -g write 26 1
#设置管脚为低电平，熄灭LED,
gpio -g write 26 0
#读取管脚当前的状态
gpio -g read 26
```
##程序点亮
直接给个循环点亮的，代码很简单。
```

"""This simple py dedicate the way to control led and make it blink.
@author Find Hao
@time 2015.12.18
"""
import RPi.GPIO as GPIO
import time

#要使用的针脚，根据mode的不同，以及选择的不同而变化
LED = 26
#设置模式为BCM模式，在前面的文章中已经说明过几种模式的不同
GPIO.setmode(GPIO.BCM)
#设置26号的模式为输出模式，即系统向26写数据
GPIO.setup(LED,GPIO.OUT)
try:
    while 1:
	#输出数据1,即置26针脚为高电平
        GPIO.output(LED,1)
        time.sleep(1)
	#输出数据0,即置针脚26为低电平
        GPIO.output(LED,0)
        time.sleep(1)
except KeyboardInterrupt:
    #清除针脚的状态，有点类似读写文件时的close
    GPIO.cleanup()
```
[0]: http://www.findspace.name/wp-content/uploads/2015/12/led.png
[1]: http://www.findspace.name/wp-content/uploads/2015/12/ledGpio.png