#Introducation
由于是arm平台，使用的应该是arm hf。教育网支持ipv6,改为国内的源更新会更快，速度可到10MB/s.
#国内教育网的源
```html
# See http://help.ubuntu.com/community/UpgradeNotes for how to upgrade to
# newer versions of the distribution.

deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty main restricted universe multiverse
deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty main restricted universe multiverse

## Major bug fix updates produced after the final release of the
## distribution.
deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-updates main restricted universe multiverse
deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-updates main restricted universe multiverse

## Uncomment the following two lines to add software from the 'universe'
## repository.
## N.B. software from this repository is ENTIRELY UNSUPPORTED by the Ubuntu
## team. Also, please note that software in universe WILL NOT receive any
## review or updates from the Ubuntu security team.
# deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty universe
# deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty universe
# deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-updates universe
# deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-updates universe

## N.B. software from this repository may not have been tested as
## extensively as that contained in the main release, although it includes
## newer versions of some applications which may provide useful features.
## Also, please note that software in backports WILL NOT receive any review
## or updates from the Ubuntu security team.
# deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-backports main restricted
# deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-backports main restricted

deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-security main restricted universe multiverse
deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-security main restricted universe multiverse
# deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-security universe
# deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-security universe
# deb http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-security multiverse
# deb-src http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/ trusty-security multiverse

```
ustc（中科大）的源是我的习惯性选择，但是找了找，这个源里没有armhf。在清华的源里找到了。
我只是将默认源里所有的` http://ports.ubuntu.com/ubuntu-ports/`替换成`http://mirrors.6.tuna.tsinghua.edu.cn/ubuntu-ports/`即可。
然后`sudo apt-get update && sudo apt-get upgrade`,如果是教育网，速度很快就会过1MB/s。