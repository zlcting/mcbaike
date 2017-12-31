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
        //p(APP_PATH);exit;
        //import("Lib.Ucclient.client");
        //include APP_PATH."Lib/Ucclient/client.class.php";
        $uc_api = new \Lib\Ucclient\client();
        $uid = $uc_api->uc_user_register(123123, 123123, 321231);
        p($uid);exit;
        
    }

}