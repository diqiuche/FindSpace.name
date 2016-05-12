#DTH11针脚
![][1]
![][3]
DHT11引脚有格子空的为正面，引脚朝下，从左数4个引脚分别为

|DHT11引脚名称|DHT11引脚功能|连接到树莓派的GPIO|
|-|-|
|VCC|正极，连接3.3V或5V|物理接口1，即3.3V|
|DATA|数据输入输出|物理接口7，即GPIO 7|
|NC|悬空（就是不连任何地方）|不连|
|GND|负极|物理接口 6，即GND|

[参考文档下载，请详细阅读参考文档][2]
#code
```
# -*- coding: utf-8 -*-
"""
This is the code for DTH11 to get the humidity and temperature
"""

import RPi.GPIO as gpio
import time
gpio.setwarnings(False)
gpio.setmode(gpio.BCM)
time.sleep(1)
#用来接收收到的数据
data=[]
pin = 26
j=0
#start work
gpio.setup(pin,gpio.OUT)
#先给个低电平
gpio.output(pin,gpio.LOW)
time.sleep(0.02)
#再给个高电平
gpio.output(pin,gpio.HIGH)
i=1
i=1

#wait to response
#设置成in模式，即板子读这个pin脚的数据
gpio.setup(pin,gpio.IN)

while gpio.input(pin)==0:
    continue
while gpio.input(pin)==1:
        continue
#get data
#数据是40bit的
while j<40:
    k=0
    while gpio.input(pin)==0:
        continue
    #根据1的个数（即高电平的时间来判断是0还是1）
    while gpio.input(pin)==1:
        k+=1
        if k>100:break
    if k<3:
        data.append(0)
    else:
        data.append(1)
    j+=1

print "Sensor is working"
#get temperature
#下面就是处理接收到的数据
humidity_bit=data[0:8]
humidity_point_bit=data[8:16]
temperature_bit=data[16:24]
temperature_point_bit=data[24:32]
check_bit=data[32:40]

humidity=0
humidity_point=0
temperature=0
temperature_point=0
check=0



for i in range(8):
    humidity+=humidity_bit[i]*2**(7-i)
    humidity_point+=humidity_point_bit[i]*2**(7-i)
    temperature+=temperature_bit[i]*2**(7-i)
    temperature_point+=temperature_point_bit[i]*2**(7-i)
    check+=check_bit[i]*2**(7-i)

tmp=humidity+humidity_point+temperature+temperature_point
if check==tmp:
    print "temperature is ", temperature,"wet is ",humidity,"%"
    print "something is successful the humidity,humidity_point,temperature,temperature_point,check is",humidity,humidity_point,temperature,temperature_point,check
else:
    print "something is worong the humidity,humidity_point,temperature,temperature_point,check is",humidity,humidity_point,temperature,temperature_point,check

```
如手册中的图
![][4]
首先，程序设置为26为out，然后发一个低电平，再发一个高电平，表示我想要你的数据，然后DHT回一个低电平，一个高电平，表示我要发送给你数据了，接下来的就是数据部分，一共40bit，0和1的区分就在于高电平的延时不同。所以通过统计1的数量来区分延时大小。
接收到的数据：
```
Sensor is working
temperature is  24 wet is  21 %
something is successful the humidity,humidity_point,temperature,temperature_point,check is 21 0 24 0 45
```
不过程序并不稳定，经常会出现数据分析错误的情况。还在分析中。

#Reference
[树莓派从 DHT11 温度湿度传感器读取数据][0]
[0]: http://shumeipai.nxez.com/2014/10/10/raspberry-dht11-get-temperature-data.html
[1]: http://shumeipai.nxez.com/wp-content/uploads/2014/10/DHT11_Pins.png
[2]: http://share.weiyun.com/db3ecec82f49bd3db08074ab9a3d74a2
[3]: http://www.findspace.name/wp-content/uploads/2015/12/dth11.png
[4]: http://www.findspace.name/wp-content/uploads/2015/12/dth11_2.jpg