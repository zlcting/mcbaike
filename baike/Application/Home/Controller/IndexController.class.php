<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        //  p(array(1,1,1,1,2));

        
        $name = 'ThinkPHP';
        $this->assign('name',$name);
        $this->display();
    }

    public function index1(){
        // 导入Org类库包 Library/Org/Util/Date.class.php类库
        import("Org.Util.Date");
        // 导入Home模块下面的 Application/Home/Util/UserUtil.class.php类库
        import("Home.Util.UserUtil");
        // 导入当前模块下面的类库 
        import("@.Util.Array");
        // 导入Vendor类库包 Library/Vendor/Zend/Server.class.php
        import('Vendor.Zend.Server');
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

}