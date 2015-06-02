<?php
/**
 * @name config.php
 * @author wayne
 */
$commoncfg =  array(
	'TRACE_EXCEPTION'		=> true,
	// 数据库配置
	//'DB_DSN' 			=> 'mysql://root:meiming2013#jishubu@221.123.160.29:23306/webv2',
	'DB_DSN' 			=> 'mysql://root:988188@localhost:3306/iokokcom',
	'DB_PREFIX' 			=> 'iok_', // 数据库表前缀
	'DB_CHARSET'			=> 'utf8',
	'DB_FIELDS_CACHE'		=> false,
	// 模板配置
	'TMPL_ENGINE_TYPE'=>'Smarty', 
	//'TMPL_ACTION_ERROR'=>'../Public/dispatch_jump', // 默认错误跳转对应的模板文件
	//'TMPL_ACTION_SUCCESS'=>'../Public/dispatch_jump', // 默认成功跳转对应的模板文件
	//'TMPL_EXCEPTION_FILE' => '../Public/think_exception',// 异常页面的模板文件
	'TMPL_CACHE_ON'=>true,
	'TMPL_CACHE_TIME'=>0,
	'TMPL_PARSE_STRING'=> array(

	),
	'TMPL_ENGINE_CONFIG'=>array(
		'caching'			=>false, 
		'cache_lifetime'	=>3600, 
		'template_dir'		=>TMPL_PATH, 
		'compile_dir'		=>CACHE_PATH, 
		'cache_dir'			=>CACHE_PATH, 
		'left_delimiter'	=>"{", 
		'right_delimiter'	=>"}"
	),
	// session
	'SESSION_AUTO_START'=>true,
	// page
	'VAR_PAGE'=>'page',
	'VAR_RECORD'=>'record',
	// 开启令牌验证 
	'TOKEN_ON'=>true,
	'TOKEN_NAME'=>'tokenid',
	'TOKEN_TYPE'=>'md5',
	'TOKEN_RESET'=>true,
	
	//设置模板常量路径
	'TMPL_PARSE_STRING'  	=>array(
	    '__PUBLIC__' => '/public',
		'__CSS__' => '/public/style',
		'__JS__'  => '/public/script',
		'__IMAGES__' => '/public/image'
	),
	
);
require(APP_NAME . '/Conf/errors.data.php');
require(APP_NAME . '/Conf/items.data.php');
return array_merge($commoncfg, array('ERRORS'=>$errors, 'ITEMS'=>$items));
?>