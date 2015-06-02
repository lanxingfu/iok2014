<?php
return array(
	'TRACE_EXCEPTION'		=> true,
	// 数据库配置
	'DB_DSN' 				=> 'mysql://root:meiming2013#jishubu@172.16.0.20:3306/newb2b1219',
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
	'ONLINETIME'=>600,
	// page
	'VAR_PAGE'=>'page',
	'VAR_RECORD'=>'record',
	// 开启令牌验证 
	'TOKEN_ON'=>true,
	'TOKEN_NAME'=>'tokenid',
	'TOKEN_TYPE'=>'md5',
	'TOKEN_RESET'=>true,
	
	// sina weibo
	'SINA_WB_AKEY'=>'4061209210',
	'SINA_WB_SKEY'=>'530423ea94cca893bc5192c452519805',
	'SINA_WB_CALLBACK_URL'=>'http://sunwei.iokokok.com/weibo/index.php?a=callback',
);
?>