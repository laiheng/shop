<?php

return array(
    //'配置项'=>'配置值'   
    'DB_TYPE'     => 'mysql', // 数据库类型
    'DB_HOST'     => '127.0.0.1', // 服务器地址
    'DB_NAME'     => 'app', // 数据库名
    'DB_USER'     => 'root', // 用户名
    'DB_PWD'      => 'root', // 密码
    'DB_PORT'     => '3306', // 端口
    'DB_PREFIX'   => 'shop_', // 数据库表前缀
    'DB_CHARSET'  => 'utf8', // 字符集
    'DB_DEBUG'    =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

    'DB_PARAMS'   =>  array(
        PDO::ATTR_CASE=>PDO::CASE_NATURAL,
    ), // 数据库连接参数,可区分大小写
	
	'URL_MODEL'   => 2,//URL访问模式
	
	'TMPL_PARSE_STRING'     =>  array(
        '__CSS__'   => RES_URL.'Public/'.MODULE_NAME.'/css',
        '__JS__'   => RES_URL.'Public/'.MODULE_NAME.'/js',
        '__IMG__'   => RES_URL.'Public/'.MODULE_NAME.'/images',
    )
);
