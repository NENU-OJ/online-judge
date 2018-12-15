# https 配置文件使用说明

如果部署环境使用了国内服务器，且没有已备案域名的情况下，可以使用 `nenuoj_https` 文件配置配置域名。

切记将 `public key` 、 `private key` 以及 `dhparam` 替换为实际生产环境下的对应文件

`dhparam` 可以使用 `openssl dhparam -out /usr/local/nginx/conf/ssl/dhparam.pem 2048` 生成，目录根据需要自行替换

如果提示缺少 `openssl` ，请自行安装

未备案情况下仅可使用 https 访问，且需手动添加。Chrome 浏览器可以将域名手动加入 HSTS 列表实现自动 https 访问 

如果有备案域名亦可使用本文件，同时可加入 301 跳转可实现全站强制 https 访问