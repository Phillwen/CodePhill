<?php
/**
 * @copyright  2015
 * @author PHILL 
 * @version 1.0 初版
 */
/****定义一些全局路径信息****/
define('BASEPATH',dirname(__FILE__));
define('APPLICATION_PATH',BASEPATH.'\application');
define('SYSTEM_PATH',BASEPATH.'\system');
define('CONFIG_PATH',APPLICATION_PATH.'\config');
define('CONTROLLER_PATH',APPLICATION_PATH.'\controller');
define('LIBRARY_PATH',BASEPATH.'\library');
define('MODEL_PATH',APPLICATION_PATH.'\model');
define('VIEWS_PATH',APPLICATION_PATH.'\views');
//加载入口文件
require_once(SYSTEM_PATH.'\core\app.php');   
Appliction::run();