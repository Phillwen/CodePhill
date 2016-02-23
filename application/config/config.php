<?php
/**
 * 默认的路由
 */
$CONFIG['route'] = array(
	'default_file'         => '',
    'default_controller'   => 'welcome',
    'default_action'       => 'welcome'
	);

/**
 * 系统文件
 */
$CONFIG['system'] = array(
	'controller'     => 'cp_controller',
	'mysqli'         => 'db'
	);

/**
 * 数据库配置
 */
$CONFIG['db'] = array(
	'Server_host'    => 'localhost',  //服务器地址
	'Server_name'    => 'root',       //用户名
	'Server_pass'    => 'root',        //密码
	'Server_db'      => 'person2',       //数据库名称
	'Server_socket'  => 3306,
 	'prefiex'        => '',        //表前缀
	);

/**
 * 用户扩展类
 */
$CONFIG['lib'] = array(
	'' => 'okiterator'
	);

/**
 * 缓存设置
 */
$CONFIG['cache'] = array(
	'cache_name' => 'Redis',
	'cache_host' => '127.0.0.1',
	'cache_port' => '6379'
	);
