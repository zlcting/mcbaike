<?php


//格式化输出
function p($var) {
    echo "<hr>";
    dump($var);
    echo "<hr>";
}


/* 验证码校验 */
function check_verify($code, $id = ''){
	$verify = new \Think\Verify();
	$res = $verify->check($code, $id);
	return $res;
}

