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

    //详情页
    public function detail(){

        $id = input('get.id');

        $entrydb = M('entry');
        $info =  $entrydb ->where(array('id'=>$id))->find();//内容详情

        $pic_list = M('pic')->where(array('e_id'=>$info['id'],'status'=>1))->select();
        //简介图片和喂养方式图片
        foreach ($pic_list as $key => $value) {
            if($info['feed_pic'] == $value['id']){
                $feed_pic = $value;
            }

            if($info['introduction_pic'] == $value['id']){
                $introduction_pic = $value;
            }
        }

        $question = M('question')->where(array('entry_id'=>$info['id'],'status'=>1))->order('qa_order desc')->select();
        // echo M()->getLastSql();
        //nav 开始
        //1.nav 渲染开始 第一级和第二级导航
        $nav = $this->getNav();
        $top = $nav['top'];
        $class_id = $info['class_id'];
        $class_arr = M('class')->where(array('id'=>$class_id))->find();
        $top_class_id = $class_arr['top_p_id'];
        $sub = $nav['sub'][$top_class_id];
        //p($info);exit();
        //第三级导航内容
        $map['class_id'] = array('IN',$nav['sub_str']);
        $map['status'] = 1;
        $entry = $entrydb->where($map)->select();
        $entry_arr = array();
        foreach ($entry as $key => $value) {
            $entry_arr[$value['class_id']][$value['id']] = $value;
        }
        //p($entry_arr);exit();
        // nav 导航

        //nav dict选中必要参数
        $get_arr['p1'] = $info['position_1'];
        $get_arr['p2'] = $info['position_2'];
        $get_arr['p3'] = $info['position_3'];
        $get_arr['p4'] = $info['position_4'];
        $this->getNavUrl($top_class_id,$get_arr);

        $dict_tags = D('tags')->gettagsdict($top_class_id);//字典
        $this->assign('dict_tags',$dict_tags);

        $this->assign('pic_num',count($pic_list));  
        $this->assign('pic_list',$pic_list);
        $this->assign('pic_cover',current($pic_list));    
        $this->assign('question',$question);
        $this->assign('introduction_pic',$introduction_pic); 
        $this->assign('feed_pic',$feed_pic);  
        $this->assign('entry_arr',$entry_arr);
        $this->assign('info',$info);
        $this->assign('top',$top);
        $this->assign('sub',$sub);
        $this->assign('get_arr',$get_arr);
        $this->assign('class_id',$class_id);
        $this->assign('top_class_id',$top_class_id);
        $this->display();

    }

    //搜索页面
    public function search(){
        $nav = $this->getNav();
        $top = $nav['top'];
        $first = current($top);
        $top_class_id = input('get.top_class_id',$first['id']);
        $p_class_id = input('get.class_id');
        $get_arr = input('get.');
        
        $sub = $nav['sub'][$top_class_id];
        //p($get_arr);exit();
        $this->getNavUrl($top_class_id,$get_arr);

        //列表start
        if(!empty($class_id)){
            $map['class_id'] = $class_id;
            
        } else {

           $class_list =  M('class')->where(array('p_id'=>$top_class_id,'status'=>1))->select();

           foreach ($class_list as $key => $value) {
                $class_id[] = $value['id'];
           }

           $map['class_id'] = array('IN',$class_id);
        }

        if(!empty($get_arr['p1'])){
            $map['position_1'] = $get_arr['p1'];
        }

        if(!empty($get_arr['p2'])){
            $map['position_2'] = $get_arr['p2'];
        }

        if(!empty($get_arr['p3'])){
            $map['position_3'] = $get_arr['p3'];
        }

        if(!empty($get_arr['p4'])){
            $map['position_4'] = $get_arr['p4'];
        }

        //p($map);
        $entry = M('entry');
        $entry_list =  $entry
        ->where($map)
        ->select();
        //echo M()->getLastSql();
        foreach ($entry_list as $key => $v) {
            $entry_data[$v['id']] = $v;
            $e_id[] = $v['id'];
        }

        $pic = M('pic');
        $pic_list = $pic->where(array('e_id'=>array('IN',$e_id)))->where(array('status'=>1))
                    ->group('e_id')
                    ->select();
        foreach ($pic_list as $key => $value) {
                 $entry_data[$value['e_id']]['pic_name'] = $value['name'];
                 $entry_data[$value['e_id']]['link'] = $value['link'];
                 $entry_data[$value['e_id']]['thumb_272'] = $value['thumb_272'];
                 $entry_data[$value['e_id']]['thumb_name'] = $value['thumb_name'];
                }
        $dict_tags = D('tags')->gettagsdict($top_class_id);
        $this->assign('dict_tags',$dict_tags);
        //p($p_class_id);
        $this->assign('entry_data',$entry_data);               
        $this->assign('get_arr',$get_arr);
        $this->assign('class_id',$p_class_id);
        $this->assign('top_class_id',$top_class_id);
        $this->assign('top',$top);
        $this->assign('sub',$sub);
        $this->display();
    }


    public function foot(){
        $this->display();
    }


    //获取导航栏内容
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



    private function getNavUrl($class_id,$map){
        $tags = D('tags')->gettagsinfo($class_id);
        //$map = input('get.');
        foreach ($tags as $key => $value) {
            $tmp = $map;
            unset($tmp['p'.$value['position']]);
            $tags[$key]['re_url'] = U('search',$tmp);
            foreach ($value['sub'] as $k => $v) {
                $tags[$key]['sub'][$k]['url'] =  U('search', array_merge(array('p'.$value['position']=>$v['id']),$tmp));
                if($map['p'.$value['position']] == $v['id']){
                    $tags[$key]['sub'][$k]['checked'] =  'checked';
                }else{
                    $tags[$key]['sub'][$k]['checked'] =  '';
                }
            }
        }
        $this->assign('tags',$tags);
    }

}