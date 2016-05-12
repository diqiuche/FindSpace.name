#!/usr/bin/python3
# coding : utf8
import requests
from bs4 import BeautifulSoup
from urllib import parse
booksa = ["MongoDB权威指南（第2版）", "发布！软件的设计与部署", "程序员必读之软件架构", "项目百态：软件项目管理面面观（修订版）", "软件开发与创新：ThoughtWorks文集（续集）",
         "Software Design 中文版 03", "Software Design 中文版 01", "代码之髓：编程语言核心概念", "代码的未来", "C#并发编程经典实例", "Java 8函数式编程",
         "Swift开发指南（修订版）", "NumPy攻略： Python科学计算与数据分析"]
books = ["JavaScript异步编程：设计快速响应的网络应用", "精通Ext JS", "Python网络编程攻略", "Swift基础教程", "Scala与Clojure函数式编程模式：Java虚拟机高效编程", "Java程序员修炼之道", "JavaScript设计模式与开发实践", "Clojure经典实例", "C++程序设计：现代方法", "自制编程语言", "Groovy程序设计", "Go并发编程实战", "渐进增强的Web设计", "Web性能权威指南", "Flask Web开发：基于Python的Web应用开发实战", "ASP.NET Web API设计", "MEAN Web开发", "Bootstrap实战", "WEB+DB PRESS 中文版 02", "WEB+DB PRESS 中文版 01", "Ruby基础教程（第4版）", "Bootstrap用户手册：设计响应式网站（GitHub有史以来最受欢迎的开源项目，中文版使用指南面面俱到，一册在手，别无所求！）", "TCP Sockets编程", "jQuery Mobile开发指南", "网络游戏核心技术与实战", "Unix内核源码剖析", "理解Unix进程", "Linux Shell脚本攻略（第2版）", "Linux系统架构和应用技巧 ", "Docker开发实践", "精通Linux（第2版）", "MongoDB权威指南（第2版）", "发布！软件的设计与部署", "程序员必读之软件架构", "软件开发与创新：ThoughtWorks文集（续集）", "项目百态：软件项目管理面面观（修订版）", "Swift开发指南（修订版）", "Software Design 中文版 01", "代码之髓：编程语言核心概念", "代码的未来", "Software Design 中文版 03", "C#并发编程经典实例", "Java 8函数式编程"]

def get_book_url(book_name):
    """获取书籍的真实地址"""
    book_name = book_name.replace('#', '%23')
    req = requests.get('http://www.ituring.com.cn/search?q=' + book_name)
    soup = BeautifulSoup(req.text, 'lxml')
    book_items = soup.body.find_all(class_='row book-item')
    # with open(book_name + '.html', 'w')as f:
    #     f.write(soup.prettify())
    got = False
    for item in book_items:
        if item.find_all(class_='code pull-right')[0].text == '图书':
            got = True
            print('http://www.ituring.com.cn/'+item.find_all(class_='span7')[0].h3.a['href'])
    if not got:
        print(book_name)


def work():
    for book_name in books:
        if book_name in booksa:
            continue
        get_book_url(book_name)


work()
