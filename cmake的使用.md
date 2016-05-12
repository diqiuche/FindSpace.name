# Introducation
本文系转载，源于我要修改CLion自动生成的cmakelist。
# 一、基本使用
安装：下载二进制包后可直接解压使用
从源码安装则执行命令：`./bootstrap; make; make install`——尝试执行bootstrap失败
使用：cmake dir_path，生成工程文件或makefile文件
# 二、概念
out-of-source build，与in-source build相对，即将编译输出文件与源文件放到不同目录中；

# 三、基本结构

1. 依赖CMakeLists.txt文件，项目主目标一个，主目录中可指定包含的子目录；
2. 在项目CMakeLists.txt中使用project指定项目名称，add_subdirectory添加子目录
3. 子目录CMakeLists.txt将从父目录CMakeLists.txt继承设置（TBD，待检验）
# 四、语法

1. `#`注释
2. 变量：使用set命令显式定义及赋值，在非if语句中，使用`${}`引用，if中直接使用变量名引用；后续的set命令会清理变量原来的值；
3. `command (args ...)`  #命令不分大小写，参数使用空格分隔，使用双引号引起参数中空格
4. `set(var a;b;c) <=> set(var a b c) `定义变量var并赋值为a;b;c这样一个string list
5. `Add_executable(${var}) <=> Add_executable(a b c)`变量使用${xxx}引用
6. 条件语句：
 `if(var) #var 非empty 0 N No OFF FALSE... #非运算使用NOT	else()/elseif() … endif(var)`
7.       循环语句
`Set(VAR a b c),Foreach(f ${VAR})…Endforeach(f)`
8.       循环语句
`WHILE() … ENDWHILE()`

# 五、内部变量
##变量及用途
|变量|用途|
|-|-|
|CMAKE_C_COMPILER|指定C编译器|
|CMAKE_CXX_COMPILER||
|CMAKE_C_FLAGS|编译C文件时的选项，如-g；也可以通过add_definitions添加编译选项|
|EXECUTABLE_OUTPUT_PATH|可执行文件的存放路径|
|LIBRARY_OUTPUT_PATH|库文件路径|
|CMAKE_BUILD_TYPE|build 类型(Debug, Release, ...)，CMAKE_BUILD_TYPE=Debug|
|BUILD_SHARED_LIBS|Switch between shared and static libraries|

##内置变量的使用：
在CMakeLists.txt中指定，使用set
cmake命令中使用，如`cmake -DBUILD_SHARED_LIBS=OFF`
# 六、命令

|命令|解释|范例|
|-|-|-|
|project (HELLO)|指定项目名称，生成的VC项目的名称；|>>使用${HELLO_SOURCE_DIR}表示项目根目录|
|include_directories|指定头文件的搜索路径，相当于指定gcc的-I参数|>> include_directories (${HELLO_SOURCE_DIR}/Hello)  #增加Hello为include目录|
|link_directories|动态链接库或静态链接库的搜索路径，相当于gcc的-L参数|>> link_directories (${HELLO_BINARY_DIR}/Hello)     #增加Hello为link目录|
|add_subdirectory|包含子目录|>> add_subdirectory (Hello)|
|add_executable|编译可执行程序，指定编译，好像也可以添加.o文件| >> add_executable (helloDemo demo.cxx demo_b.cxx)   #将cxx编译成可执行文件——|
|add_definitions|添加编译参数|>> add_definitions(-DDEBUG)将在gcc命令行添加DEBUG宏定义；>> add_definitions( “-Wall -ansi –pedantic –g”)|
|target_link_libraries|添加链接库,相同于指定-l参数|>> target_link_libraries(demo Hello) #将可执行文件与Hello连接成最终文件demo|
|add_library||>> add_library(Hello hello.cxx)  #将hello.cxx编译成静态库如libHello.a|
|add_custom_target|
|message( status fatal_error, “message”)||
|set_target_properties( ... )| lots of properties... OUTPUT_NAME, VERSION, ....
|link_libraries( lib1 lib2 ...)| All targets link with the same set of libs

# 七、 说明
1，CMAKE生成的makefile能够处理好.h文件更改时只编译需要的cpp文件；
# 八、FAQ

+ 怎样获得一个目录下的所有源文件
`aux_source_directory(<dir> <variable>)`
将dir中所有源文件（不包括头文件）保存到变量variable中，然后可以add_executable (ss7gw ${variable})这样使用。
+ 怎样指定项目编译目标
  project命令指定
+ 怎样添加动态库和静态库
 target_link_libraries命令添加即可
+ 怎样在执行CMAKE时打印消息
`message([SEND_ERROR | STATUS | FATAL_ERROR] "message to display" ...)`
 注意大小写
+ 怎样指定头文件与库文件路径
 include_directories与link_directories
可以多次调用以设置多个路径
 link_directories仅对其后面的targets起作用
+ 怎样区分debug、release版本
建立debug/release两目录，分别在其中执行`cmake -DCMAKE_BUILD_TYPE=Debug（或Release）`，需要编译不同版本时进入不同目录执行make即可；
Debug版会使用参数-g；Release版使用-O3 –DNDEBUG
 另一种设置方法——例如DEBUG版设置编译参数DDEBUG
```
IF(DEBUG_mode)
  add_definitions(-DDEBUG)
ENDIF()
```
在执行cmake时增加参数即可，例如cmake -D DEBUG_mode=ON
+ 怎样设置条件编译
例如debug版设置编译选项DEBUG，并且更改不应改变CMakelist.txt
 使用option command，eg：
```
option(DEBUG_mode "ON for debug or OFF for release" ON)
IF(DEBUG_mode)
  add_definitions(-DDEBUG)
ENDIF()
```
 使其生效的方法：首先cmake生成makefile，然后make edit_cache编辑编译选项；Linux下会打开一个文本框，可以更改，该完后再make生成目标文件——emacs不支持make edit_cache；
 局限：这种方法不能直接设置生成的makefile，而是必须使用命令在make前设置参数；对于debug、release版本，相当于需要两个目录，分别先cmake一次，然后分别make edit_cache一次；
 期望的效果：在执行cmake时直接通过参数指定一个开关项，生成相应的makefile——可以这样做，例如`cmake –DDEBUGVERSION=ON`
+ 怎样添加编译宏定义
 使用add_definitions命令，见命令部分说明
+ 怎样添加编译依赖项
用于确保编译目标项目前依赖项必须先构建好
add_dependencies
+   怎样指定目标文件目录
 建立一个新的目录，在该目录中执行cmake生成Makefile文件，这样编译结果会保存在该目录——类似
 SET_TARGET_PROPERTIES(ss7gw PROPERTIES
        RUNTIME_OUTPUT_DIRECTORY "${BIN_DIR}")
+   很多文件夹，难道需要把每个文件夹编译成一个库文件？
 可以不在子目录中使用CMakeList.txt，直接在上层目录中指定子目录
+   怎样设定依赖的cmake版本
cmake_minimum_required(VERSION 2.6)
+   相对路径怎么指定
 `${projectname_SOURCE_DIR}`表示根源文件目录，`${ projectname _BINARY_DIR}`表示根二进制文件目录？
+   怎样设置编译中间文件的目录
 TBD
+   怎样在IF语句中使用字串或数字比较
数字比较LESS、GREATER、EQUAL，字串比STRLESS、STRGREATER、STREQUAL，
 Eg：
```
set(CMAKE_ALLOW_LOOSE_LOOP_CONSTRUCTS ON)
set(AAA abc)
IF(AAA STREQUAL abc)
  message(STATUS "true") #应该打印true
ENDIF()
```
+   更改h文件时是否只编译必须的cpp文件
 是
+   机器上安装了VC7和VC8，CMAKE会自动搜索编译器，但是怎样指定某个版本？
 TBD
+   怎样根据OS指定编译选项
 IF( APPLE ); IF( UNIX ); IF( WIN32 )
+   能否自动执行某些编译前、后命令？
 可以，TBD
+   怎样打印make的输出
`make VERBOSE=1`


参考文献：
[1] CMake_Tutorial.pdf
[2] CMake使用总结，http://blog.csdn.net/keensword007/archive/2008/07/16/2663235.aspx
[3] http://www.cmake.org/
[4] 安装包中文档
[5] Andrej Cedilnik，HOWTO: Cross-Platform Software Development Using CMake，October, 2003
[6] Cjacker，CMake实践.PDF

#原文地址

[Cmake怎样使用](http://blog.csdn.net/netnote/article/details/4051620)

