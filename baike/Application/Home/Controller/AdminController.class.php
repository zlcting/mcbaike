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
     **/
    public function entryClass(){

        $this->display();
    }

    public function entryClassLevel(){

        $this->display();
    }

    public function buttons(){
        $this->display();
    }

}