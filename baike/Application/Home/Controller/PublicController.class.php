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

     //验证邮箱
     public function veremail(){
       $ver =  input('get.ver');
       $ver = base64_decode($ver);
       $ver_arr = json_decode($ver,true);
       $map['uid'] = $ver_arr['uid'] ;
       $map['createtime'] = array('GT',time()-60*60*24);
       $map['status'] = 0;
       $user_email = M('user_email');
       $list = $user_email->where($map)->select();
       $status = false;
       foreach ($list as $key => $value) {
           if($ver_arr['ver_email']==$value['ver']){
                $status = true;
                break;
           }
       }

       if($status){
         $user_email->where($map)->save(array('status'=>1));

         $re = M('user')->where(array('uid'=>$ver_arr['uid']))->save(array('status'=>1));
       }
       if($re){

            $this->redirect('index/index', array(), 0, '页面跳转中...');
       }else{
        echo "失败";
       }

     }


}