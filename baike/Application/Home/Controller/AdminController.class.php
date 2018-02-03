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
        顶级分类列表
        // $Model = M();
        // $Model->query('SELECT * FROM baike_user WHERE status = 1');
     **/
    public function entryClass(){
        $dict = get_dict('class');
        $limit = 10;
        $class = M('class');
        $p = input('get.p',1);
        $level = input('get.level',0);
        $name = input('get.name','');
        $p_id = input('get.p_id','');
        $p_name = input('get.p_name','');
        if(!empty($level)){
            $map['level'] = $level;  
        }
        
        if(!empty($name)){
            $map['name'] = $name;  
        }

        if(!empty($p_id)){
            $map['p_id'] = $p_id;  
        }
        
        $map['status'] = 1;
        //p($map);
        $list = $class->where($map)->order('create_time')->page($p.','.$limit)->select();
        //p(M()->getLastSql());
        $count= $class->where($map)->count();
        //p($list);
        $Page = new \Org\Util\UserPage($count,$limit);  
        foreach($map as $key=>$val) {//分页参数
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show = $Page->Show();
        $this->assign('map',$map);
        $this->assign('p_name',$p_name);
        $this->assign('dict',$dict);
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