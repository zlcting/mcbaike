<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller {
    public function index(){
        
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
            $uc_api = new \Ucenter\Client\UcApi();

            list($uid, $username, $password, $email) = $uc_api -> uc_user_login($name, $pass, $isuid);
            echo $uc_api->uc_user_synlogin($uid);
           // p($uid);exit();
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
    
    //注册

    public function register(){
        $this->display();
    }

    //注册action
    public function check_register(){
       $name =  I('post.username');
       $pass =  I('post.pass');
       $email = I('post.email');
       $code =  I('post.code');
       $re = check_verify($code); 
        if(!$re){
             $error =  '验证码错误';
        }

        if(empty($name) || empty($pass) || empty($email)){
            $error =  '请正确填写注册信息';
        }

        if(empty($error)){
            $uc_api = new \Ucenter\Client\UcApi();
            // $uid = $uc_api->uc_user_register($name, $pass, $email);
            // if($uid <= 0) {
            //     if($uid == -1) {
            //         $error =  '用户名不合法';
            //     } elseif($uid == -2) {
            //         $error =  '包含要允许注册的词语';
            //     } elseif($uid == -3) {
            //         $error =  '用户名已经存在';
            //     } elseif($uid == -4) {
            //         $error =  'Email 格式有误';
            //     } elseif($uid == -5) {
            //         $error =  'Email 不允许注册';
            //     } elseif($uid == -6) {
            //         $error = '该 Email 已经被注册';
            //     } else {
            //         $error =  '未定义';
            //     }
            // } else {
            //     $succ =  '注册成功';
            // }
            $ucresult = $uc_api->uc_user_checkemail($_GET['email']);
            if($ucresult == -4) {
                $error =  'Email 格式有误';
            } elseif($ucresult == -5) {
                 $error =  'Email 不允许注册';
            } elseif($ucresult == -6) {
                 $error =  '该 Email 已经被注册';
            }

            $ucresult = $uc_api ->uc_user_checkname($_GET['email']);
            if($ucresult == -1) {
                $error = '用户名不合法';
            } elseif($ucresult == -2) {
                $error = '包含要允许注册的词语';
            } elseif($ucresult == -3) {
                $error = '用户名已经存在';
            }

               $user = M('user');
               $data['name'] = $name;
               $data['goup'] = 1;
               $data['account'] = $name;
               $data['email'] = $email;
               $data['pass'] = md5($pass);
               $data['status'] = 0;
               $uid = $user->add($data);
               if(!$uid){
                $error = "数据库错误";
               }
        }
            if(empty($error)) {
               $ver_email = $this->ver_email($uid,$email);
               if($ver_email){
                   $title = "萌宠百科邮箱验证"；
                   $content = "<p>请点击下面链接完成邮箱验证</p>";
                   $content .="<p><a href = '$ver_email' target='_blank'>$ver_email</a></p>"   
                   $re = send_mail('274480298@qq.com',$title,$content);
               }
               $this->assign('ver_email',$ver_email);
               $this->assign('email',$email);
               $this->display();
           } else {
                 $this->error($error, U('User/register'));
           }
    }

    //异步校验验证码
    public function ajaxCheck(){
        $code =  I('post.code');
        $re = check_verify($code,'',false); 
        if($re){
            echo 1;
        }else{
            echo 2;
        }
    }

    //生成邮箱验证秘钥串
    private function ver_email($uid,$email){
        $data['uid'] = $uid;
        $data['email'] = $email;
        $data['createtime'] = time();
        $data['status'] = 0;
        $ver = json_encode($data);
        $ver = base64_encode($ver);
        $ver = md5($ver);
        $user_email =  M('user_email');
        $re = $user_email ->add($data);
        if($re){
            return $ver;
        }else{
            return false;
        }
    }

}