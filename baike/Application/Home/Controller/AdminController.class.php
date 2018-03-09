<?php
namespace Home\Controller;
use Think\Controller;

class AdminController extends Controller {

    //构造函数
    public function _initialize()  
    {  
        $user = $_SESSION['baike'];
        if(empty($user) || $user['group'] !=2){
            // echo "没有权限:";
            // $this->redirect('index/index', array(),5, '页面跳转中...');
        }
        $this->assign('user',$user);
    } 

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
        $list = $class->where($map)->order('level asc')->order('sort desc')->page($p.','.$limit)->select();
       // p($list);
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
            $data['sort'] = !empty($post['sort'])?$post['sort']:0;
            if($data['level'] == 1){
                $data['top_p_id'] = 0;
                $data['p_id'] = 0;
            }elseif($data['level'] == 2){//分类等级未level2时 top_p_id 与p_id相同
                $data['top_p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
                $data['p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
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
                $this->redirect('entryClass', array(), 0, '页面跳转中...');
                //$this->success('新增成功', 'entryClass');
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
            $data['sort'] = !empty($post['sort'])?$post['sort']:0;
            $data['top_p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
            $data['p_id'] = !empty($post['top_p_id'])?$post['top_p_id']:0;
            $data['create_time'] = time();
            $data['status'] = 1;
            $class = M('class');
            $re = $class->save($data);
    
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

    public function entryClassdel(){
        $id = input('get.id');
        $class = M('class');
        $class_top = $class->where(array('id'=>$id))->find();
        if($class_top['level'] == 1){
            $sub_re = $class->where(array('p_id'=>$id))->save(array('status'=>-1));
        }
        $re = $class->where(array('id'=>$id))->save(array('status'=>-1));

        if($re){
            $this->redirect('entryClass', array(), 0, '页面跳转中...');
        }else{
            $this->error('保存失败');
        }
    }

    //获取class信息
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

    //获取标签ajax
    public function tagAjax(){
        $class_id = input('get.class_id');
        $tags = D('tags')->gettagsinfo($class_id);
        if (!empty($class_id)) {
            echo json_encode($tags);
        }
    }


    //导航管理
    public function entryClassLevel(){
        $post = input('post.');
        if(!empty($post)){
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
            //p($tree);
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
            $data['position_1'] = $post['position_1'];
            $data['position_2'] = $post['position_2'];
            $data['position_3'] = $post['position_3'];
            $data['position_4'] = $post['position_4'];
            $data['life'] = $post['life'];
            $data['feed'] = $post['feed'];
            $data['descent'] = $post['descent'];
            $data['e_disease'] = $post['e_disease'];
            $data['introduction'] = $post['introduction'];
            $data['en_name'] = $post['en_name'];
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
            $data['id'] = $post['id'];
            $data['name'] = $post['name'];
            $data['character'] = $post['character'];
            $data['position_1'] = $post['position_1'];
            $data['position_2'] = $post['position_2'];
            $data['position_3'] = $post['position_3'];
            $data['position_4'] = $post['position_4'];
            $data['en_name'] = $post['en_name'];
            $data['life'] = $post['life'];
            $data['feed'] = $post['feed'];
            $data['descent'] = $post['descent'];
            $data['e_disease'] = $post['e_disease'];
            $data['introduction'] = $post['introduction'];
            $data['class_id'] = !empty($post['class_id'])?$post['class_id']:'';
            //$data['create_time'] = time();
            $data['update_time'] = time();
            $data['status'] = 1;
            //p($data);exit;
            $entry = M('entry');
            $re = $entry->save($data);
            $this->redirect('entryList', array('entry_id'=>$data['entry_id']));
        } else {

            $id = input('get.e_id');
            $map['status'] = 1;
            $map['level'] = 1;
            $class = D('class');
            $class_list = $class->where($map)->select();
            $entry = D('entry');
            $entry_info = $entry->where(array('id'=>$id))->find();
            $class_info = $class->where(array('id'=>$entry_info['class_id']))->find();
            $s_class_list = $class->where(array('p_id'=>$class_info['p_id']))->select();
            $dict = get_dict('entry');
            $tags = D('tags')->gettagsinfo($class_info['p_id']);
            //p($entry_info);
            $this->assign('tags',$tags);
            $this->assign('s_class_list',$s_class_list);
            $this->assign('top_class_id',$class_info['p_id']);
            $this->assign('e_id',$id);
            $this->assign('dict',$dict);
            $this->assign('class_list',$class_list);
            $this->assign('entry_info',$entry_info);
            $this->display();
        }
    }

    public function entry_del(){
        $id = input('get.id');
        $data['id'] = $id;
        $data['status'] = -1;
        $entry = M('entry');
        $re = M('entry')->save($data);
        $this->redirect('entryList');
    }

    //问答列表
    public function qaEditor(){
        $entry_id = input('get.e_id');
        $dict = get_dict('qa');
        $qa = D('question');
        $list = $qa->where(array('entry_id'=>$entry_id,'status'=>1))->order('updatetime desc')->select();
        
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

    public function qadelet(){
        $id = input('get.id');
        $entry_id = input('get.entry_id');
        $data['id'] = $id;
        $data['updatetime'] = time();
        $data['status'] = -1;
        //p($data);exit();
        $qa = M('question');
        $re = $qa->save($data);
        if($re){
                $this->redirect('qaEditor', array('e_id'=>$entry_id));
        }else{
                $this->error('保存失败');
         } 

    }

    //问答置顶
    public function qaup(){
        $id = input('get.id');
        $entry_id = input('get.entry_id');
        $status = input('get.status');
        $data['id'] = $id;
        $data['updatetime'] = time();

        if ($status == 'cancel') {
            $data['qa_order'] = 0;
        }else{
            $data['qa_order'] = 100;
        }
        //p($data);exit();
        $qa = M('question');
        $re = $qa->save($data);
        if($re){
                $this->redirect('qaEditor', array('e_id'=>$entry_id));
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
        if(!empty($map['e_id'])){
           $list = $class->where($map)->order('create_time desc')->page($p.','.$limit)->select(); 
        }
        $entry_info = M('entry')->where(array('id'=>$map['e_id'],'status'=>1))->find();
        
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
        $this->assign('entry_info',$entry_info);
        
        $this->display();
    }

    public function picdelet(){
        $id = input('get.id');
        $e_id = input('get.e_id');
        $data['id'] = $id;
        $data['status'] = -1;
        $re = M('pic')->save($data);

        if($re){
            if(!empty($e_id)){
                $this->redirect('picList?e_id='.$e_id, array(), 0, '页面跳转中...');
            }else{
                $this->redirect('indexPic', array(), 0, '页面跳转中...');
            }
        }else{
            $this->error('更新失败');
        }
    }

    //pic外链
    public function thechan(){

        $post = input('post.');
        if($post){
            $data['id'] = $post['id'];
            $data['thechan'] = $post['thechan'];
            $re = M('pic')->save($data);
            if(!empty($re)){

                $this->redirect('indexPic', array(), 0, '页面跳转中...');

            }else{

                $this->error('更新失败');
            }

        }else{
            $id = input('get.id');
            $pic_info = M('pic')->where(array('id'=>$id))->find();
            $this->assign('pic_info',$pic_info);
            $this->display(); 
        }

    }

    //设词条-喂养或简介配图
    public function setEntryPic(){
        $feed_pic = input('get.feed_pic');
        $introduction_pic = input('get.introduction_pic');
        $id = input('get.e_id');

        if(!empty($feed_pic)){
            $data['feed_pic'] = $feed_pic;
        }
        if(!empty($introduction_pic)){
            $data['introduction_pic'] = $introduction_pic;
        }

        $data['id'] = $id;
        
        if(!empty($data['id'])&&( $data['feed_pic']|| $data['introduction_pic'])){
            $re = M('entry')->save($data);
        }

        if($re){
            $this->redirect('picList?e_id='.$id, array(), 0, '页面跳转中...');
        }else{
            $this->error('更新失败');
        }
        

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

        if(!empty($e_id)){//当上传首页图片素材时 则不会裁剪原图
            $image->thumb( 1136, 460 )->save($path);
        }
        $image->thumb( 310, 220 )->save($thumb_path);
        $image->open($path);
        $image->thumb( 272, 272 )->save($thumb272_path);
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxReturn($upload->getError());

        } else {// 上传成功
            $image_size = getimagesize($path);
            $width = $image_size['0'];
            $height = $image_size['1'];

            $pic = D('pic');
            $data['e_id'] = $e_id;
            $data['name'] = $info['file']['savename'];
            $data['thumb_name'] = $thumb_name;
            $data['thumb_272'] = $thumb_272;
            $data['link'] = $info['file']['savepath'];
            $data['type'] = $type;

            $data['width'] = $width;
            $data['height'] = $height;

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
        $pic_list = $pic->where(array('type'=>2,'e_id'=>0,'status'=>array('in','1,2')))
                    ->where(array('width'=>array('EGT','272'),'height'=>array('EGT','272')))
                    ->select();
        //拼装一下图片数组
        $pic_id_index = array();
        $pic_choose_arr = array();
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


    //标签管理
    public function tag(){
        $class = M('class');
        $entry_class = $class->where(array('level'=>1,'status'=>1))->select();
        $frist = current($entry_class);
        $class_id = input('get.class',$frist['id']);
        $tag = D('tags')->getTopTagByclassid($class_id);
        $this->assign('tag',$tag);
        $this->assign('class_id',$class_id);
        $this->assign('entry_class',$entry_class);
        $this->display();
    }

    //父标签post提交
    public function post_top_tag(){
        $post = input('post.');
        $insert_tag = array();
        if($post['insert_tag']){
            foreach ($post['insert_tag'] as $key => $value) {
                if(!empty($value['name'])){
                    $insert_tag[$key]['name'] = $value['name'];
                    $insert_tag[$key]['level'] = 0;
                    $insert_tag[$key]['p_id'] = 0;
                    $insert_tag[$key]['class_id'] = $post['class_id'];
                    $insert_tag[$key]['position'] = $key;  
                }
            }
        }

        if($post['update_tag']){
            foreach ($post['update_tag'] as $key => $value) {
                if(!empty($value['name'])){
                    $update_tag[$key]['id'] = $value['id'];
                    $update_tag[$key]['name'] = $value['name'];
                }
            }
        }
        if(!empty($insert_tag)){
            foreach ($insert_tag as $key => $value) {
                M('tags')->add($value);
            }
        }

        if(!empty($update_tag)){
            foreach ($update_tag as $key => $value) {
                M('tags')->save($value);
            }
        }

         $this->redirect('tag?class='.$post['class_id'], array(), 0, '页面跳转中...');

    }

    //标签添加编辑
    public function subtag_add(){
        $post = input('post.');
        if(!empty($post)){
            if(!empty($post['id'])){
                M('tags')->save($post);
            }else{
                unset($post['id']);
                 M('tags')->add($post);
            }

            $this->redirect('subtag_list?p_id='.$post['p_id'], array(), 0, '页面跳转中...');

        }else{
            $id = input('get.id');
            $tag = M('tags')->where(array('id'=>$id))->find();

            $p_id = input('get.p_id',$tag['p_id']);
            $p_tag = M('tags')->where(array('id'=>$p_id))->find();
            $this->assign('p_tag',$p_tag);
            $this->assign('tag',$tag);
            $this->display(); 
        }

    }

    public function subtag_list(){
        $p_id = input('get.p_id');
        $tag = M('tags')->where(array('p_id'=>$p_id,'status'=>1))->select();
        
        $this->assign('p_id',$p_id);
        $this->assign('tag',$tag);
        $this->display(); 
    }


    public function subtag_del(){
        $id = input('get.id');
        $p_id = input('get.p_id');
        $data['id'] = $id;
        $data['status'] = -1;
        $re = M('tags')->save($data);

        if($re){
            $this->redirect('subtag_list?p_id='.$p_id, array(), 0, '页面跳转中...');
         }else{
            $this->error('保存失败');
         }
    }




}