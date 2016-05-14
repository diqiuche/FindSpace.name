# Introduction
之前写过一篇[Python救急HttpServer和Ftpserver](http://www.findspace.name/easycoding/1479)，简单描述了如何开启python内置的httpserver，但是内置的是单线程的，同时只能允许一个人访问。本文则提供了简单的多线程开启httpserver的例程。
# Show me the code
```python
import os
from threading import Thread
import time

port_number = "8000"


def run_on(port):
    os.system("python -m http.server " + port)

if __name__ == "__main__":
    server = Thread(target=run_on, args=[port_number])
    #run_on(port_number) #Run in main thread
    #server.daemon = True # Do not make us wait for you to exit
    server.start()
    time.sleep(2) #Wait to start the server first


def test():
    url = "http://localhost:" + port_number


test()
```
# 代码说明

+ os.system("python -m http.server " + port)，“python -m http.server 8000”是一个cmd，能够启动一个http server。
+ server = Thread(target=run_on, args=[port_number])， 创建一个线程用来启动http server。如果启动在主线程里面启动http server，将会阻塞主线程，而不能执行下面的代码。
+ server.start()， 启动线程。
+ time.sleep(2)，等待启动http server。

