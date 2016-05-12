#Introduction
##[HEVC学习笔记目录](http://www.findspace.name/easycoding/1434 )
本文主要介绍了HM代码的阅读和调试工具clion的相关设置。
# Clion简介
CLion是JetBrains推出的全新C/C++跨平台集成开发环境。使用CMake作为构建系统、集成了调试器GDB以及一些流行的版本控制器，如SVN、Git、GitHub等。同时，该版本还增强了代码编辑功能，如一键导航、代码自动补全、代码分析等
换句话说，我是jetbrains脑残粉。
但是jetbrains公司的几个ide真的太爽，关键是职能补全虐eclipse几条街。
一般都提供了免费的社区版，~~当然你也可以通过`idea.lanyus.com`来破解，直接填服务器即可~~，除了学生，请自觉购买产品来支持正版。
# Clion的配置
对于一般的项目，开箱即用。但是clion仅支持cmake项目，对于只有makefile的项目，clion也会自动根据项目目录等自动生成一个cmakelist文件，但是有时需要进行简单的修改。
对于HM，