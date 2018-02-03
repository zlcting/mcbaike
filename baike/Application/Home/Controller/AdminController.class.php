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
        $class = M('class');
        //p($map);
        $list = $class->where($map)->order('create_time desc')->page($p.','.$limit)->select();
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
    public function entryClassAdd(){
        //p($map);
        $post = input('post.');
        if(!empty($post)){
            p($post);
            $data['name'] = $post['name'];
            $data['level'] = $post['level'];
            $data['top_p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
            $data['p_id'] = !empty($post['p_id'])?$post['p_id']:0;
            $data['create_time'] = time();
            $data['status'] = 1;
            $class = M('class');
            $re = $class->add($data);
            // p(M()->getLastSql());
            // p($re);
            // exit;
            if($re){
                //$this->redirect('entryClass', array(), 0, '页面跳转中...');
                $this->success('新增成功', 'entryClass');
            }else{
                $this->error('保存失败');
            }
            

        } else {

            $dict = get_dict('class');
            $class = M('class');
            $map['status'] = 1;
            $map['level'] = 1;
            $class_list = $class->where($map)->select();
            //p($class_list);exit();
            $this->assign('dict',$dict);
            $this->assign('class_list',$class_list);
            $this->display();
        }

    }


    public function entryClassEditor(){

        $this->display();
    }

    public function entryAjax(){
        $category = 1;
        $map['status'] = 1;
        $map['p_id'] = input('get.p_id');
        if(!empty($map['p_id'])){
            $class = M('class');
            $class_list = $class->where($map)->select();
            echo json_encode($class_list);
        }
    }

    public function entryClassLevel(){

        $this->display();
    }

    public function buttons(){
        $this->display();
    }

    public function form_basic(){
        $this->display();
    }

}