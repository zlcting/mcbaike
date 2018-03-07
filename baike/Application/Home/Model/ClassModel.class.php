<?php
namespace Home\Model;
use Think\Model;
class ClassModel extends Model {
    protected $tablePrefix = 'baike_'; 
    protected $dbName = 'mcbaike';

    //生成class树
    //effective =1 时候只生成有效classlist

	public function classtree($effective = 1){

	    $top_map['status'] = 1;
        $class_list = $this->where($top_map)->order('level asc')->order('sort desc')->select();
        $tree = array();
        foreach ($class_list as $key => $value) {

        	if($value['level'] == 1){
        		$tree[$value['id']] = $value;
        		unset($class_list[$key]);
        	}
        }

        foreach ($class_list as $key => $value) {
        	if($value['level'] == 2){
        		$tree[$value['p_id']]['sub'][$value['id']] = $value;
        		unset($class_list[$key]);
        	}
        }
        //p($tree);exit();

        //判断删除不完整的树
        if(!empty($effective)){
        	foreach ($tree as $k => $v) {
        		if(empty($v['sub'])){
        			unset($tree[$k]);
        		} 
        		
        	}
        }

        return $tree;
	}    

	public function getClassbyMap($map){
		   $map['status'] = 1;

		   $class_list = $this->where($map)->select();
		   $return = array();
		   foreach ($class_list as $key => $value) {
		   	  $return[$value['id']] = $value;
		   }
		   return $return;
	}


}