# OnlineJudge

## WEB
### 配置文件
`src/Front/config/params.php`和`src/Front/config/db.php`中的字段按照需要进行修改
### 文件权限修改
如果使用Nginx则需要`sudo chown -R www-data:www-data OnlineJudge/src/front/uploads/avatar/user/`
### 安装并开启GD库
`sudo apt-get install php5.6-gd && sudo service nginx restart`
### 修改文件上传大小限制
`sudo gedit /etc/nginx/nginx.conf`添加`client_max_body_size 20M;`
`sudo /etc/php/5.6/fpm/php.ini`修改`post_max_size 64M`和`upload_max_filesize 64M`

### Ubuntu Mysql 关闭 ONLY_FULL_GROUP_BY

1. `sudo vim /etc/mysql/my.cnf`
2. Add this to the end of the file
```
[mysqld]
sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
```

## Memcached
1. `sudo apt-get install memcached`
2. `sudo apt-get install php5.6-memcached`
3. 重启php-fpm`sudo service php5.6-fpm restart`
4. 修改`/etc/memcached.conf`文件中的`-m`项为128
5. 重启memcached`sudo service memcached restart`

## 限制比赛提交
通过`src/Front/config/params.php`文件`contestWhiteList`字段以白名单方式管理，若这个列表为空则表示无限制，否则只有列表中的比赛可以提交，非比赛提交`id`为`0`。
例：
- 没有限制：`[]`
- 只有比赛1和比赛2可以提交：`[1, 2]`
- 只有比赛3和非比赛提交：`[0, 3]`
