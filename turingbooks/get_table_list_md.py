#!/usr/bin/env python3
import requests
from bs4 import BeautifulSoup
from urllib import parse

class Book:
    def __init__(self):
        self.name = ''
        self.price = 0.0
        self.url = ''


book_list = []


def get_list():
    global book_list
    temp_dict = {}
    with open('books2', 'r')as fin:
        while 1:
            line = fin.readline()
            if not line:
                break
            # a_book = Book()
            line = line.replace('\n','')
            # a_book.name = line
            line2 = fin.readline()
            line2 = line2.replace('\n', '')
            temp_dict[line] = float(line2)
            # a_book.price = float(line2)
            # book_list.append(a_book)
    for key in temp_dict:
        a_book = Book()
        a_book.name = key
        a_book.price = temp_dict[key]
        book_list.append(a_book)

def get_book_url(book):
    """获取书籍的真实地址"""
    book_name = book.name.replace('#', '%23')
    req = requests.get('http://www.ituring.com.cn/search?q=' + book_name)
    soup = BeautifulSoup(req.text, 'lxml')
    book_items = soup.body.find_all(class_='row book-item')
    # with open(book_name + '.html', 'w')as f:
    #     f.write(soup.prettify())
    got = False
    for item in book_items:
        if item.find_all(class_='code pull-right')[0].text == '图书':
            got = True
            book.url = 'http://www.ituring.com.cn/'+item.find_all(class_='span7')[0].h3.a['href']
    if not got:
        print(book_name)


def work():
    for node in book_list:
        get_book_url(node)
    print('----------')
    for node in book_list:
        print("|%s|%.2f|%s |"%(node.name, node.price, node.url))


get_list()
work()