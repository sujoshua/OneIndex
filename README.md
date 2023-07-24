# OneInde(原版本修复)

## 原版本功能：

>不占用服务器空间，不走服务器流量，
直接列出 OneDrive 目录，文件直链下载。  

可以借此实现把OneDrive当作图床等功能

## 修复版本说明：

**Onedrive Directory Index** 为一款十分好用的基于onedrive api构建出的onedrive目录索引软件。

但因未知原因，原作者已经删库，目前github高star的几个fork的原版本，都已经无法年久失修，无法直接部署使用。

本版本在作者最后发布的最新的版本上进行了一些基础修复：

1. 修复安装步骤中第二步中回调URL的无法使用问题：由于微软的api回调要求回调URL必须是https协议，若安装者使用的协议是http协议，原作者的解决方案是提供了一个桥接网址，能转发微软的api回调，从而在UI上写死了第二步中的回调URL的更改。但是由于原版本的删库，该桥接网址也已经失效，但在UI上仍是写死状态。本版本，允许安装者自定义回调url的地址，因此安装者可以把回调url调整为自己服务器的网址直接与微软进行对接，但因此也必须要求使用https协议。但基于目前https的tls证书申请是十分方便的以及http的传输不安全性，建议使用者申请tls证书，实现https协议
2. 添加了php的curl模块相关参数的修改：在原版本curl参数是写死在了php代码里面的，此版本修复了这一问题，提供了修改方式，并相应的添加url。此修复可以解决传输一些较大文件时，会出现的连接超时现象。
3. 提供了下载反代理服务器功能。虽说流量不走本机，而是直接使用onedrive的传输服务器，但是由于众所周知的原因，onedrive在国内访问是不稳定的。因此本版本允许安装者使用反代服务器来加速资源的下载。
4. 目前Github的docker镜像提供的版本也有少许问题，因此本版本提供了对应修复版本的docker，修复版本docker默认开启了取消地址栏的功能，使得url更加好看。
5. 修复在php8环境下，加载onedrive首页出现的报错导致的无法使用

**有任何问题，欢迎在issue提出**

## 安装运行

与原版本一致

### 源码安装运行：

#### 需求：
1、PHP空间，PHP 5.6+ 需打开curl支持  
2、OneDrive 账号 (个人、企业版或教育版/工作或学校帐户)  
3、OneIndex 程序   

#### 配置：

安装步骤与[uuou](https://github.com/uuou)/[Oneindex](https://github.com/uuou/Oneindex)基本一致，除了下方单独列出的地方需要注意

<img width="658" alt="image" src="/media/files/install.gif">  

不一致的点：下方的回调url的输入框，需要填写你oneindex所安装服务器的域名，本版本的docker版本会默认填写你安装服务器的url。

必须确保你的url支持https协议，若不支持建议google一下如何部署，教程有很多的。

<img width="658" alt="image" src="/media/files/install_2.png">  

#### 计划任务  
[可选]**推荐配置**，非必需。后台定时刷新缓存，可增加前台访问的速度。  
```
# 每小时刷新一次token
0 * * * * /具体路径/php /程序具体路径/one.php token:refresh

# 每十分钟后台刷新一遍缓存
*/10 * * * * /具体路径/php /程序具体路径/one.php cache:refresh
```

### Docker 安装运行

- 基于[TimeBye/oneindex](https://github.com/TimeBye/oneindex)修改而来，写入本版本，完善其细节，修复了GithuAction的构建能力，同时默认添加了伪静态，实现状态栏去除功能。



> 镜像地址：[joshuasu/oneindex](https://hub.docker.com/repository/docker/joshuasu/oneindex)

#### 版本：

- `latest`：以alpine为基础系统，跟踪本仓库的最新提交。
- `alpine-commit_sha`：以alpine为基础系统，本仓库Commit sha对应的提交。

#### 运行：

- 使用`docker run`命令运行：

    ```
    docker run -d --name oneindex \
        -p 80:80 --restart=always \
        -v ~/oneindex/config:/var/www/html/config \
        -v ~/oneindex/cache:/var/www/html/cache \
        joshuasu/oneindex
    ```
    
    - 停止删除容器：
        ```
        docker stop oneindex
        docker rm -v oneindex
        ```

#### 变量：

- `TZ`：时区，默认`Asia/Shanghai`
- `PORT`：服务监听端口，默认为80
- `DISABLE_CRON`：是否禁用crontab自动刷新缓存，设置任意值则不启用
- `REFRESH_TOKEN`：使用crontab进行token更新，默认`0 * * * *`，即每小时更新一次
- `REFRESH_CACHE`：使用crontab进行缓存更新，默认`*/10 * * * *`，即每10分钟更新一次
- `SSH_PASSWORD`：sshd用户密码，用户名为`root`，若不设置则不启用sshd

#### 变量使用方式:

```
docker run -d --name oneindex \
    -p 80:80 --restart=always \
    -v ~/oneindex/config:/var/www/html/config \
    -v ~/oneindex/cache:/var/www/html/cache \
    -e REFRESH_TOKEN='0 * * * *' \
    -e REFRESH_CACHE='*/10 * * * *' \
    -e PORT='81'\
    joshuasu/oneindex
```

#### 持久化：

- `/var/www/html/cache`：缓存存储目录
- `/var/www/html/config`：配置文件存储目录

## 特殊文件实现功能  
` README.md `、`HEAD.md` 、 `.password`特殊文件使用  

可以参考[https://github.com/donwa/oneindex/tree/files](https://github.com/donwa/oneindex/tree/files)  

**在文件夹底部添加说明:**  
>在 OneDrive 的文件夹中添加` README.md `文件，使用 Markdown 语法。  

**在文件夹头部添加说明:**  
>在 OneDrive 的文件夹中添加`HEAD.md` 文件，使用 Markdown 语法。  

**加密文件夹:**  
>在 OneDrive 的文件夹中添加`.password`文件，填入密码，密码不能为空。  

**直接输出网页:**  
>在 OneDrive 的文件夹中添加`index.html` 文件，程序会直接输出网页而不列目录。  
>配合 文件展示设置-直接输出 效果更佳。  

## 命令行功能  
仅能在PHP CLI模式下运行  

**清除缓存:**  
```
php one.php cache:clear
```
**刷新缓存:**  
```
php one.php cache:refresh
```
**刷新令牌:**  
```
php one.php token:refresh
```
**上传文件:**  
```
php one.php upload:file 本地文件 [OneDrive文件]
```


**上传文件夹:**  
```
php one.php upload:folder 本地文件夹 [OneDrive文件夹]
```

例如：  
```
//上传demo.zip 到OneDrive 根目录  
php one.php upload:file demo.zip  

//上传demo.zip 到OneDrive /test/目录  
php one.php upload:file demo.zip /test/  

//上传demo.zip 到OneDrive /test/目录并将其命名为 d.zip  
php one.php upload:file demo.zip /test/d.zip  

//上传up/ 到OneDrive /test/ 目录  
php one.php upload:file up/ /test/
```

## 反代服务器功能

如下图所示，需要先开启反代功能按钮

<img width="658" alt="image" src="/media/files/proxy.png">

之后如图输入反代服务器地址

保存即可

### 反代服务器配置

反代服务器的实现方式有很多种，此处拿nginx作反向代理服务器举例。

编辑反向代理服务器的nginx配置，插入如下代码

```nginx
server{
    listen 80;
    listen 443 ssl; #https协议监听

    server_name oneindexproxy.com;  #反代理服务器域名
    
    #https协议相关设置
    ssl_certificate      /etc/nginx/cert/oneIndex/cert.pem;
    ssl_certificate_key  /etc/nginx/cert/oneIndex/pem.crt;
    ssl_session_cache   shared:SSL:1m;
    ssl_session_timeout 5m;
    ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    ssl_prefer_server_ciphers on;

    #反向代理服务器核心设置
    location / {
        expires 1h;
        if ($request_uri ~* "(php|jsp|cgi|asp|aspx)")
        {
            expires 0;
        }
        proxy_pass https://xxx-my.sharepoint.com; # 你的onedrive的直链下载地址，下文会讲述如何获取
        proxy_set_header Host xxx-my.sharepoint.com;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header REMOTE-HOST $remote_addr;
        proxy_buffering off;
        proxy_cache off;
        proxy_set_header X-Forwarded-Proto $scheme;
        add_header X-Cache $upstream_cache_status;
    }
}
```

onedrive直链获取方式：

浏览器输入：https://你的oneindex域名/images/

随意传入一个图片

按下F12按键，调出浏览器调式工具

按下图方式，首先点击左上角，然后讲鼠标点在图片上方，调式工具的代码会跳至图片的url地址，图片中的框选部分即使你的onedrive直链下载地址

<img width="658" alt="image" src="/media/files/proxy2.png">

## 可能的问题

### 1.本版本已把上传大小限制调整到4GB，但是部分带宽较小机器会出现大文件无法上传成功的问题。

此问题是php的curl模块有默认的传输超时限制，需要调大该参数，本版本提供专门页面进行修改：

进入设置，选择网络设置

<img width="658" alt="image" src="/media/files/proxy.png">

将**curl连接服务器超时时间** 参数适当调大。
