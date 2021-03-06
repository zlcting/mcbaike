<?php

//分类等级
$dict['class']['level'] = array(
    "1" => "一级",
    "2" => "二级",
    //"3" => "三级",
);

$dict['class']['status'] = array(
    "1" => "正常",
    "2" => "删除",
);


$dict['entry']['status'] = array(
    "1" => "正常",
    "2" => "删除",
);

// //温度
// $dict['entry']['tem'] = array(
//     "1" => "10度以下",
//     "2" => "10-15度",
//     "3" => "16-20度",
//     "4" => "21-25度",
//     "5" => "25-30度",
//     "6" => "30度以上",
// );
 
// //长度
// $dict['entry']['len'] = array(
//     "1" => "1CM以下",
//     "2" => "1-5CM",
//     "3" => "6-10CM",
//     "4" => "11-15CM",
//     "5" => "15-20CM",
//     "6" => "20-25CM",
//     "7" => "26-30CM",
//     "8" => "30CM以上",
// );

// $dict['entry']['ph'] = array(
//     "1" => "6.5-7",
//     "2" => "7-7.5",
//     "3" => "7.5-8",
// );

// $dict['entry']['food'] = array(
//     "1" => "杂食",
//     "2" => "肉食",
//     "3" => "素食",
//     "4" => "其它",
// );

$dict['entry']['status'] = array(
    "1" => "正常",
    "2" => "删除",
);

$dict['entry']['character'] = array(
    "1" => "活泼",
    "2" => "安静",
);

$dict['qa']['status'] = array(
    "1" => "正常",
    "2" => "删除",
);


$dict['pic']['status'] = array(
    "1" => "正常",
    "2" => "封面应用",
    "-1" => "删除",
);


return array(
    'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  '127.0.0.1', // 服务器地址
	'DB_NAME'               =>  'mcbaike',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  '123',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  'baike_',    // 数据库表前缀
	'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查 3.2.3版本废弃
	'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
	'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
	'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
	'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
	'DB_SQL_BUILD_CACHE'    =>  false, // 数据库查询的SQL创建缓存 3.2.3版本废弃
	'DB_SQL_BUILD_QUEUE'    =>  'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc 3.2.3版本废弃
	'DB_SQL_BUILD_LENGTH'   =>  20, // SQL缓存的队列长度 3.2.3版本废弃
	'DB_SQL_LOG'            =>  false, // SQL执行日志记录 3.2.3版本废弃
	'DB_BIND_PARAM'         =>  false, // 数据库写入数据自动参数绑定
	'DB_DEBUG'              =>  false,  // 数据库调试模式 3.2.3新增 
	'DB_LITE'               =>  false,  // 数据库Lite模式 3.2.3新增 

	//'配置项'=>'配置值'
	'AUTOLOAD_NAMESPACE' => array(
		//'Lib'     => APP_PATH.'Lib',
	),
	'URL_MODEL'          =>  2,


	'BAIKE_DICT'         => $dict, 
      // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__IMG__'=>  '/public/img/',          
    ],
    'URL_ROUTER_ON'   => true,
    //静态路由 
	'URL_MAP_RULES'=>array(
	    'api/uc' => 'uc/index',
	)
);
