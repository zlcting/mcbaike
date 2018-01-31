<?php
namespace Home\Controller;
use Think\Controller;

class AdminController extends Controller {


    public function blank(){
        echo "欢迎使用萌宠百科后台";

    }

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

        //  <div id="pages">  
        //       {$page}  
        //  </div>  
        //  $pageStr = $subPages->show_SubPages(2);  
        //  $this->assign('page',$pageStr); 

        //$class = new \Home\Model\ClassModel();
        $class = M('class');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $list = $class->select();
        $this->assign('list',$list);// 赋值数据集
        $count= $class->where('status=1')->count();// 查询满足要求的总记录数
        $Page = new \Org\Util\UserPage($count,2);  
        $show = $Page->Show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出

        $this->display(); // 输出模板

    }

    public function entryClassLevel(){

        $this->display();
    }

    public function buttons(){
        $this->display();
    }

}