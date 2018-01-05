<?php


//格式化输出
function p($var) {
    echo "<hr>";
    dump($var);
    echo "<hr>";
}


/* 验证码校验 */
function check_verify($code, $id = '', $reset = true){

	$verify = new \Think\Verify(array('reset'=>$reset));
	$res = $verify->check($code, $id);
	return $res;

}

