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
            if($data['level'] == 1){
                $data['top_p_id'] = 0;
                $data['p_id'] = 0;
            }else{
                $data['top_p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
                $data['p_id'] = !empty($post['p_id'])?$post['p_id']:0;
            }
            
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
            $data['class_id'] = !empty($post['class_id'])?$post['class_id']:'';
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
            $data['class_id'] = !empty($post['class_id'])?$post['class_id']:'';
            $data['create_time'] = time();
            $data['status'] = 1;
            $entry = M('entry');
            $re = $entry->save($data);
        } else {

            $id = input('get.e_id');
            $map['status'] = 1;
            $map['level'] = 1;
            $class = D('class');
            $class_list = $class->where($map)->select();
            $entry = D('entry');
            $entry_info = $entry->where(array('id'=>$id))->find();
            $dict = get_dict('entry');
            $this->assign('e_id',$id);
            $this->assign('dict',$dict);
            $this->assign('class_list',$class_list);
            $this->assign('entry_info',$entry_info);
            $this->display();
        }
    }

    //问答列表
    public function qaEditor(){
        $entry_id = input('get.e_id');
        $dict = get_dict('qa');
        $qa = D('question');
        $list = $qa->where(array('entry_id'=>$entry_id))->order('updatetime desc')->select();
        
        $this->assign('e_id',$entry_id);
        $this->assign('entry_id',$entry_id);
        $this->assign('list',$list);
        $this->assign('dict',$dict);
        $this->display();
    }
    //问答编辑提交
    public function qaPostUpdate(){
        $post = input('post.');
        $data['id'] = $post['id'];
        $data['title'] = $post['title'];
        $data['entry_id'] = $post['entry_id'];
        $data['content'] = $post['content'];
        $data['updatetime'] = time();
        $data['status'] = 1;
        $qa = M('question');
        $re = $qa->save($data);
         // p(M()->getLastSql());
         // exit();
        if($re){
                $this->redirect('qaEditor', array('entry_id'=>$data['entry_id']));
        }else{
                $this->error('保存失败');
         } 
    }
    public function qaPostAdd(){
        $post = input('post.');
        $data['title'] = $post['title'];
        $data['entry_id'] = $post['entry_id'];
        
        $data['content'] = $post['content'];
        $data['createtime'] = time();
        $data['updatetime'] = time();
        $data['status'] = 1;
        $qa = D('question');
        $re = $qa->add($data);
         // p(M()->getLastSql());
         // exit();
        if($re){
                $this->redirect('qaEditor', array('entry_id'=>$data['entry_id']));
                //$this->success('更新成功', 'entryClassLevel');
        }else{
                $this->error('保存失败');
         } 
    }


    //图片列表
    public function picList(){
        $p = input('get.p',1);
        $map['status'] = 1;
        $map['e_id'] = input('get.e_id');
        $class = M('pic');
        $limit = 10;
        $list = $class->where($map)->order('create_time desc')->page($p.','.$limit)->select();
        //p(M()->getLastSql());
        $count= $class->where($map)->count();
        $Page = new \Org\Util\UserPage($count,$limit);  
        foreach($map as $key=>$val) {//分页参数
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show = $Page->Show();
        $dict = get_dict('entry');
        $this->assign('e_id',$map['e_id']);
        $this->assign('dict',$dict);
        $this->assign('map',$map);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function picUpload(){
        $e_id = input('get.e_id');
        $type = input('get.type', 1);
        if(empty($e_id) && $type == 1){
             $this->ajaxReturn('参数错误');
             exit;
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     $_SERVER['DOCUMENT_ROOT'].'/baike/Public/Uploads/img/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $upload->subName   =     array('date','Ymd'); 
        // 上传文件 
        $info   =   $upload->upload();
        $thumb_name = 'thumb'.$info['file']['savename'];
        $thumb_272 = 'thumb_272'.$info['file']['savename'];
        //处理图片
        $image = new \Think\Image();
        $path = $_SERVER['DOCUMENT_ROOT'].'/baike/Public/Uploads/img/'.$info['file']['savepath'].$info['file']['savename'];
        $thumb_path =  $_SERVER['DOCUMENT_ROOT'].'/baike/Public/Uploads/img/'.$info['file']['savepath'].$thumb_name;
        $thumb272_path =  $_SERVER['DOCUMENT_ROOT'].'/baike/Public/Uploads/img/'.$info['file']['savepath'].$thumb_272;
        $image->open($path);
        $image->thumb( 1136, 460 )->save($path);
        $image->thumb( 310, 220 )->save($thumb_path);
        $image->open($path);
        $image->thumb( 272, 272 )->save($thumb272_path);
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxReturn($upload->getError());

        } else {// 上传成功

            $pic = D('pic');
            $data['e_id'] = $e_id;
            $data['name'] = $info['file']['savename'];
            $data['thumb_name'] = $thumb_name;
            $data['thumb_272'] = $thumb_272;
            $data['link'] = $info['file']['savepath'];
            $data['type'] = $type;
            $data['status'] = 1;
            $data['create_time'] = time();
            $data['update_time'] = time();
            $re = $pic ->add($data);
            if($re){
                $this->ajaxReturn('上传成功');

            } else {

                $this->ajaxReturn('DB错误：'.$re);
            }
        }
    }

    public function indexPic(){
        $p = input('get.p',1);
        $map['status'] = array('in','1,2,-1');
        $map['e_id'] = 0;
        $map['type'] = 2;
        $class = M('pic');
        $limit = 10;
        $list = $class->where($map)->order('update_time desc')->page($p.','.$limit)->select();
        // p(M()->getLastSql());
        $count= $class->where($map)->count();
        $Page = new \Org\Util\UserPage($count,$limit);  
        foreach($map as $key=>$val) {//分页参数
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show = $Page->Show();
        $dict = get_dict('pic');
        $this->assign('e_id',$map['e_id']);
        $this->assign('dict',$dict);
        $this->assign('map',$map);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();  
    }

    //删除图片方法
    public function picdel(){
        $post = input('post.');
        $id_arr = $post['id'];
        $pic = M('pic');
        $re = $pic ->where(array('id'=>array('in',$id_arr)))->save(array('status'=>'-1','update_time'=>time()));
        // p(M()->getLastSql());
        $this->redirect('indexPic', array(), 0, '页面跳转中...');

    }

    //应用该图片为首页封面图轮播图
    public function useCover(){
        $id = input('get.id');
        $status = input('get.status',$status);
        $pic = M('pic');
        $re = $pic ->where(array('id'=>$id))->save(array('status'=>$status,'update_time'=>time()));
         //p(M()->getLastSql());
        $this->redirect('indexPic', array(), 0, '页面跳转中...');

    }

    //首页入口管理
    public function indexNav(){
        $class = M('class');
        $entry_class = $class->where(array('level'=>1,'status'=>1))->select();

        $index_nav = M('index_nav');
        $list = $index_nav->select();

        $pic = M('pic');
        $pic_list = $pic->where(array('type'=>2,'e_id'=>0,'status'=>array('in','1,2')))->select();
        //拼装一下图片数组
        $pic_id_index = array();
        foreach ($pic_list as $key => $value) {
            $pic_id_index[$value['id']] = $value;
        }
        foreach ($list as $k => $v) {
            if(!empty($v['pic_id'])){
               $list[$k]['pic_url'] = $pic_id_index[$v['pic_id']]['link'].$pic_id_index[$v['pic_id']]['thumb_name'];
            }
        }
        $this->assign('list',$list);
        $this->assign('pic_id_index',$pic_id_index);
        $this->assign('entry_class',$entry_class);
        $this->display();
    }

    public function postIndexNav(){
        $post = input('post.');
        $index_nav = array();
        foreach ($post['class_id'] as $key => $value) {
            $index_nav[$key]['id'] = $key;
            $index_nav[$key]['class_id'] = $value;
        }

        foreach ($post['pic_id'] as $key => $value) {
            $index_nav[$key]['id'] = $key;
            $index_nav[$key]['pic_id'] = $value;
        }

        foreach ($post['img_url'] as $key => $value) {
            $index_nav[$key]['id'] = $key;
            $index_nav[$key]['img_url'] = $value;
        }

        $index_nav_db = M('index_nav');
        foreach ($index_nav as $v) {
            $index_nav_db->save($v);
        }

        $this->redirect('indexNav', array(), 0, '页面跳转中...');
    }


    public function demo(){
        $index_nav = M('index_nav');
        $list = $index_nav->select();
        $this->assign('list',$list);
        $this->display();
    }
    
    public function buttons(){
        $this->display();
    }

    public function form_basic(){
        $this->display();
    }

}