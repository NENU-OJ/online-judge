# OnlineJudge

## Judger

# 依赖
1. glog https://github.com/google/glog
2. Mysql Connector/C https://dev.mysql.com/downloads/connector/c/6.1.html

# 编译
在`OnlineJudge/src/Judger`文件夹下执行`cmake . && make`命令

# 支持语言
status.language / problem.is_special_judge | language
---- | ---
1 | GNU C++
2 | GNU C++ 11
3 | Java
4 | Python2
5 | Python3

# 配置

## 配置文件
配置文件名为`config.ini`，其中的任何字段都不可删除。
## 用户
1. 新建低权限用户`judger`，`sudo useradd judger`
2. 给`judger`指定密码，`sudo passwd judger`

## 测试数据
测试数据文件夹由`config.ini`中的`test_files_path`字段指定，该文件夹与其中的内容的属主应为非`judger`用户。
测试输入数据文件为`{test_files_path}/{problem_id}/input`，
测试输出数据文件为`{test_files_path}/{problem_id}/output`，
即使输入数据或输出数据为空也应该创建该文件，此时该文件为空文件。
若无输入数据文件或输出数据文件会返回`Judger Error`。

## 临时文件夹
临时文件夹由`config.ini`中的`temp_path`字段制定，该文件夹创建后使用如下命令修改权限`sudo chown -R judger:{another_user} {temp_path}/`

## Special Judge
Special Judge所需的文件夹由`config.ini`中的`spj_files_path`字段指定，该文件夹与其中的内容的属主应为非`judger`用户。
若题目的`is_special_judge`字段的值为`0`则表明该题目非Special Judge，
否则Special Judge程序的语言由`is_special_judge`的值决定。
格式为`{spj_files_path}/{problem_id}/spj.{ext}`。
例：题目1000, Special Judge语言为C++，则格式为`{spj_files_path}/1000/spj.cpp`。
### 使用方法
SPJ程序的标准输入会有三行被写入，分别是测试数据输入文件名，测试数据输出文件名和用户输出数据文件名，你需要从这三个文件中来读取数据。
若SPJ程序的exit_code为0则表示AC，否则表示WA。

# 运行
把编译后生成的`Judger`和`config.ini`与各个所需文件夹放在同一个目录下，由非`judger`用户`sudo ./Judger`运行。
日志信息全部会输出到`stderr`。

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
