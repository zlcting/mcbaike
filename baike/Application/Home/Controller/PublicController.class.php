<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller {
    public function index(){
        $name = 'ThinkPHP';
        $this->assign('name',$name);
        $this->display();
    }


    /* 生成验证码 */
    public function verify(){
        $config = [
            'fontSize' => 10, // 验证码字体大小
            'length' => 4, // 验证码位数
            'imageH' => 20
        ];

        $Verify = new \Think\Verify();
        $Verify->entry();
     }


}