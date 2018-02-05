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
    
    //添加

    public function entryClassAdd(){
        //p($map);
        $post = input('post.');
        if(!empty($post)){
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


    //编辑
    public function entryClassEditor(){
        $post = input('post.');
        if(!empty($post)){
            $data['id'] = $post['id'];
            $data['name'] = $post['name'];
            $data['level'] = $post['level'];
            $data['top_p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
            $data['p_id'] = !empty($post['p_id'])?$post['p_id']:0;
            $data['create_time'] = time();
            $data['status'] = 1;
            $class = M('class');
            $re = $class->save($data);
            // p(M()->getLastSql());
            // p($re);
            // exit;
            if($re){
                //$this->redirect('entryClass', array(), 0, '页面跳转中...');
                $this->success('更新成功', 'entryClass');
            }else{
                $this->error('保存失败');
            }
            
        } else {

            $id = input('get.id');
            $dict = get_dict('class');
            $class = M('class');
            $class_info = $class->where(array('id'=>$id))->find();
            $top_map['status'] = 1;
            $top_map['level'] = 1;
            $class_list = $class->where($top_map)->select();
            $p_map['status'] = 1;
            $p_map['level'] = 2;
            $p_class_list = $class->where($p_map)->select();
            $this->assign('dict',$dict);
            $this->assign('class_info',$class_info);
            $this->assign('class_list',$class_list);
            $this->assign('p_class_list',$p_class_list);
        
            $this->display();
        }
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

    //导航管理
    public function entryClassLevel(){
        $post = input('post.');
        if(!empty($post)){
            //p($post);
            $id = array();
            $tree = $post['tree'];
            foreach ($tree as $k => $v) {
                $id[$k] = $k;
                foreach ($v as $kk => $vv) {
                    $id[$kk] = $kk;
                    foreach ($vv as $value) {
                        $id[$value] = $value;
                    }
                }
            }
            $class = D('class');
            $class_list = $class->getClassbyMap(array('id'=>array('IN',$id)));
            $nav = array();
            //第一级
            foreach ($tree as $k => $v) {
                $nav[$k]['name'] = $class_list[$k]['name'];
                $nav[$k]['id'] = $class_list[$k]['id'];
                //第二级
                foreach ($v as $kk => $vv) {
                    $nav[$k]['sub'][$kk]['name'] = $class_list[$kk]['name'];
                    $nav[$k]['sub'][$kk]['id'] = $class_list[$kk]['id'];
                    //第三级
                    foreach ($vv as $value) {
                        $nav[$k]['sub'][$kk]['sub'][$value]['id'] = $value;
                        $nav[$k]['sub'][$kk]['sub'][$value]['name'] = $class_list[$value]['name'];
                    }
                }
            }
            $nav_json = json_encode($nav);
            $data['id'] = 1;
            $data['nav'] = $nav_json;
            $re = M('nav')->save($data);

            if($re){
                //$this->redirect('entryClass', array(), 0, '页面跳转中...');
                $this->success('更新成功', 'entryClassLevel');
            }else{
                $this->error('保存失败');
            }

        }else{
            $class = D('class');
            $tree = $class->classtree();
            $nav = M('nav')->find();
            $nav = json_decode($nav['nav'],true);
                        //p($nav);
            $this->assign('nav',$nav);
            $this->assign('tree',$tree);
            $this->display(); 
        }

    }

    //词条列表
    public function entryList(){

        $p = input('get.p',1);
        $name = input('get.name','');
        $entryDb = D('entry');
        if(!empty($name)){
             $map['name'] = $name;
        }
        $map['status'] = 1;
        $limit = 10;
        $list = $entryDb->where($map)->order('create_time desc')->page($p.','.$limit)->select();
        //p(M()->getLastSql());
        $count= $entryDb->where($map)->count();
        $Page = new \Org\Util\UserPage($count,$limit);  
        foreach($map as $key=>$val) {//分页参数
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show = $Page->Show();

        $dict = get_dict('entry');

        $this->assign('map',$map);
        $this->assign('dict',$dict);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    //百科词条添加
    public function entryAdd() {

        $post = input('post.');
        if (!empty($post)) {
            $data['name'] = $post['name'];
            $data['character'] = $post['character'];
            $data['tem'] = $post['tem'];
            $data['len'] = $post['len'];
            $data['class_id'] = !empty($post['class_id'])?$post['class_id'];
            $data['create_time'] = time();
            $data['status'] = 1;
            $entry = M('entry');
            $re = $entry->add($data);
            if($re){
                //$this->redirect('entryClass', array(), 0, '页面跳转中...');
                $this->success('保存成功', 'entryList');
            } else {
                $this->error('保存失败');
            }
        } else {
            $map['status']=1;
            $map['level']=1;
            $class = D('class');
            $class_list = $class->where($map)->select();
            $dict = get_dict('entry');
            $this->assign('dict',$dict);
            $this->assign('class_list',$class_list);
            $this->display();
        }

    }

    //词条编辑
    public function entryEditor() {
        $post = input('post.');
        if(!empty($post)) {
            $data['id'] = $post['name'];
            $data['name'] = $post['name'];
            $data['character'] = $post['character'];
            $data['tem'] = $post['tem'];
            $data['len'] = $post['len'];
            $data['class_id'] = !empty($post['class_id'])?$post['class_id'];
            $data['create_time'] = time();
            $data['status'] = 1;
            $entry = M('entry');
            $re = $entry->save($data);
        } else {

            $id = input('get.id');
            $map['status'] = 1;
            $map['level'] = 1;
            $class = D('class');
            $class_list = $class->where($map)->select();
            $entry = D('entry');
            $entry_info = $entry->where(array('id'=>$id))->find();
            $dict = get_dict('entry');
            $this->assign('dict',$dict);
            $this->assign('class_list',$class_list);
            $this->assign('entry_info',$entry_info);
            $this->display();
        }
    }

    public function buttons(){
        $this->display();
    }

    public function form_basic(){
        $this->display();
    }

}