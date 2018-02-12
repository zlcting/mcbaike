<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        
        $User = D('User');
        $this->assign('name',$name);
        $this->display();
    }

    public function res(){
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

    public function detail(){
        $this->display();

    }

    public function search(){
        $this->display();

    }

}