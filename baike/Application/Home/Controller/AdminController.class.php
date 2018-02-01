<?php
namespace Home\Controller;
use Think\Controller;

class AdminController extends Controller {


    public function blank(){
        echo "欢迎使用萌宠百科后台";

    }

    //后台首页
    public function index(){

        $this->assign('name',$name);
        $this->display();
    }

     /**
        顶级分类添加
        // $Model = M();
        // $Model->query('SELECT * FROM baike_user WHERE status = 1');
     **/
    public function entryClass(){
        //$class = new \Home\Model\ClassModel();
        $limit = 2;
        $class = M('class');

        $map['status'] = 1;

        $list = $class->where($map)->order('create_time')->page($_GET['p'].','.$limit)->select();
        $count= $class->where($map)->count();

        $Page = new \Org\Util\UserPage($count,$limit);  

        foreach($map as $key=>$val) {//分页参数
            $Page->parameter[$key]   =   urlencode($val);
        }
        
        $show = $Page->Show();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();

    }

    public function entryClassLevel(){

        $this->display();
    }

    public function buttons(){
        $this->display();
    }

}