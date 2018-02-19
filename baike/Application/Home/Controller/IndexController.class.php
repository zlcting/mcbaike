<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {


    //构造函数
    public function _initialize()  
    {  
        $user = array();
        if(!empty($_SESSION['baike'])){
            $user = $_SESSION['baike'];
        }
        $this->assign('user',$user);
    } 


    public function index(){
        
        $p = input('get.p',1);
        $map['status'] = 2;
        $map['e_id'] = 0;
        $map['type'] = 2;
        $class = M('pic');
        $list = $class->where($map)->order('update_time desc')->limit(7)->select();
        // p(M()->getLastSql());


        $index_nav_db = M('index_nav');


        $nav_res =  $index_nav_db
        ->join(' as i LEFT JOIN __CLASS__ as c  ON i.class_id = c.id')
        ->field('c.id as e_id,i.id,i.img_url,c.name')
        ->select();
        //  p(M('')->getLastSql());
        // p($nav_res);
        $dict = get_dict('pic');

        $this->assign('list',$list);
        $this->assign('nav_res',$nav_res);
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

        $nav = $this->getNav();
        $map['class_id'] = array('IN',$nav['sub_str']);
        $map['status'] = 1;
        $entry = M('entry')->where($map)->select();
        $entry_arr = array();
        foreach ($entry as $key => $value) {
            $entry_arr[$value['class_id']][$value['id']] = $value;
        }
        //echo M()->getLastSql();
        //p($nav);
        p($entry_arr);exit();
        $this->assign('entry_arr',$entry_arr);
        $this->assign('nav',$nav);
        $this->display();

    }

    public function search(){
        $nav = $this->getNav();
        $top_class_id = input('get.top_class_id');
        $class_id = input('get.class_id');
        $top = $nav['top'];
        $sub = $nav['sub'][$top_class_id];
        //p($sub);exit();
        $dict = get_dict('entry');
        $this->assign('dict',$dict);
        $this->assign('top_class_id',$top_class_id);
        $this->assign('top',$top);
        $this->assign('sub',$sub);
        $this->display();
    }


    public function foot(){
        $this->display();
    }


    private function getNav(){
        $nav = M('nav')->find();
        $nav = json_decode($nav['nav'],true);
        $re = array();
        foreach ($nav as $key => $value) {
            $re['top'][$key]['name'] = $value['name'];
            $re['top'][$key]['id'] = $value['id'];
            foreach ($value['sub'] as $k => $v) {
                $re['sub'][$key][$k] = $v;
                $re['sub_str'][] = $v['id'];
            }
        }
        return $re;
    }

}