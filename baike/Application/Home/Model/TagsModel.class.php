<?php
namespace Home\Model;
use Think\Model;
class TagsModel extends Model {
    protected $tablePrefix = 'baike_'; 
    protected $dbName = 'mcbaike';

    //获取顶级标签
    public function getTopTagByclassid($class_id){
           $map['status'] = 1;
           $map['class_id'] = $class_id;
           $map['level'] = 0;
           $tag_list = $this->where($map)->order('position asc')->limit(4)->select();
           $return = array();
           foreach ($tag_list as $key => $value) {
              $return[$value['position']] = $value;
           }
           return $return;
    }
   
}