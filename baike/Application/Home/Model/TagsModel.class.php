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


    public function gettagsinfo($class_id){
          $map['status'] = 1;
          $map['class_id'] = $class_id;
          $tag_list = $this->where($map)->order('position asc')->select();
          $return = array();
          foreach ($tag_list as $key => $value) {
            if ($value['level'] == 0) {
              $return[$value['position']]['name'] = $value['name'];
              $return[$value['position']]['position'] = $value['position'];
            } else {
              $return[$value['position']]['sub'][$value['id']]['id'] = $value['id'];
              $return[$value['position']]['sub'][$value['id']]['name'] = $value['name'];
            }
          }

          foreach ($return as $key => $value) {
              if(empty($value['sub'])){
                unset($return[$key]);
              } else {
                $return[$key]['sub'] = array_values($value['sub']);
              }
          }
          $return = array_values($return);

          return $return;

    }
   
}