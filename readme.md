# 用户授权中心SDK

## SDK

用户授权中心项目目的是为了提供中心化的用户登录功能，此SDK方便的集成了授权管理中心在各个客户端进行`SSO登录`等一系列功能。

## 安装

1、composer 安装

私有库：

```
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/liutongyue/uauth-sdk.git"
        }
    ]
```

安装组件：

```
composer require uauth/uauth-sdk dev-master
```

2、修改配置：
```

添加 `config/app.php` 文件的 `providers` 中添加 `UAuth\SDK\Laravel\UAuthServerProvider::class`
```

3、添加配置文件：

先运行命令
```
$> php artisan vendor:publish --provider="UAuth\SDK\Laravel\UAuthServerProvider"

```

//将会添加配置文件 config/entrust.php
//设置该文件的参数：

```
[
    ...
    
    'uauth_host' => '用户授权中心地址',
    'app_id' => '在授权中心注册的app_id',
    'crypt_key' => '授权中心填写的加密key',
    'route_prefix' => '可设定的路由前缀'
    
    ...

]
```

4、添加路由到`routes/web.php`

```
UAuth\SDK\Laravel\SsoController::route();

// 跳转SSO登录的路由：          route('uauth.to_login')
// SSO登录后返回并本地登录的路由：route('uauth.login_back')
// 本地登出并跳转SSO登出：       route('uauth.logout')

//注意，本地如果有登录页面则需要验证是否已登录，如果已登录则直接跳转来源页面

```
