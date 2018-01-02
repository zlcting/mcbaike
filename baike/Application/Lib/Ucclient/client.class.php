<?php 
namespace Lib\Ucclient;

define('UC_CONNECT', 'mysql');

define('IN_UC', TRUE);
define('UC_CLIENT_VERSION', '1.6.0');
define('UC_CLIENT_RELEASE', '20110501');
define('UC_ROOT', __DIR__.'/');
define('UC_DATADIR', UC_ROOT.'data/');
define('UC_DATAURL', UC_API.'data');
define('UC_API_FUNC', UC_CONNECT == 'mysql' ? 'uc_api_mysql' : 'uc_api_post');
//db
define('UC_DBHOST', '127.0.0.1');
define('UC_DBUSER', 'root');
define('UC_DBPW', '123');
define('UC_DBCHARSET', 'UTF-8');//字符集
define('UC_DBCONNECT', '3306');//端口
define('UC_DBTABLEPRE', '`mcbaike`.uc_');//数据库名和前缀

$GLOBALS['uc_controls'] = array();

class client {

    static public function uc_user_register($username, $password, $email, $questionid = '', $answer = '', $regip = '') {
    	$function = UC_API_FUNC;
    	$re = self::$function('user', 'register', array('username'=>$username, 'password'=>$password, 'email'=>$email, 'questionid'=>$questionid, 'answer'=>$answer, 'regip' => $regip));
         return $re;
    }

    static public function uc_addslashes($string, $force = 0, $strip = FALSE) {
		!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		if(!MAGIC_QUOTES_GPC || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = self::uc_addslashes($val, $force, $strip);
				}
			} else {
				$string = addslashes($strip ? stripslashes($string) : $string);
			}
		}
		return $string;
	}

    static public function uc_api_mysql($model, $action, $args=array()) {

		global $uc_controls;

		if(empty($uc_controls[$model])) {
			//p(UC_ROOT.'./lib/db.class.php');exit;
			include_once UC_ROOT.'lib/db.class.php';
			include_once UC_ROOT.'model/base.php';
			include_once UC_ROOT."control/$model.php";
			eval("\$uc_controls['$model'] = new {$model}control();");
		}
		if($action{0} != '_') {
			$args = self::uc_addslashes($args, 1, TRUE);
			$action = 'on'.$action;
			$uc_controls[$model]->input = $args;
			return $uc_controls[$model]->$action($args);
		} else {
			return '';
		}
	}

}    