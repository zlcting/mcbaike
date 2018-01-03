<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller {
    public function index(){
        $name = 'ThinkPHP';
        $this->assign('name',$name);
        $this->display();
    }

    //登录页面

    public function login(){


        $this->display();

    }

    //验证登录
    public function check_login(){

       $name =  I('post.name');
       $pass =  I('post.pass');
       $code =  I('post.code');

       $re = check_verify($code); 
       $re =1;
        if($re){
            $email_check= filter_var($name, FILTER_VALIDATE_EMAIL);
            if($email_check){
                $isuid = 2;//邮箱登录
            }else{
                 $isuid = 0;//用户名登录
            }
            $uc_api = new \Lib\Ucclient\client();

            list($uid, $username, $password, $email) = $uc_api -> uc_user_login($name, $pass, $isuid);

                if($uid > 0) {
                    $succ =  '登录成功';
                } elseif($uid == -1) {
                    $error =   '用户不存在，或者被删除';
                } elseif($uid == -2) {
                    $error = '密码错误';
                } else {
                    $error =  '未定义错误';
                }


       } else {

            $error = '验证码错误';
       }

       if(!empty($error)) {

            $this->error($error, U('User/login'));

       } else {

            $this->success($succ, U('Index/index'));
       }

    }

    public function register(){

        $this->display();
    }


    public function updateRegister(){
        $uc_api = new \Lib\Ucclient\client();
        $uid = $uc_api->uc_user_register('test_user', '123', 'test@163.com');
        if($uid <= 0) {
            if($uid == -1) {
                echo '用户名不合法';
            } elseif($uid == -2) {
                echo '包含要允许注册的词语';
            } elseif($uid == -3) {
                echo '用户名已经存在';
            } elseif($uid == -4) {
                echo 'Email 格式有误';
            } elseif($uid == -5) {
                echo 'Email 不允许注册';
            } elseif($uid == -6) {
                echo '该 Email 已经被注册';
            } else {
                echo '未定义';
            }
        } else {
            echo '注册成功';
        }
        p($uid);exit;
    }

}