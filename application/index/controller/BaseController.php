<?php
namespace app\index\controller;
use think\Db;
use think\model;
use think\Request;
use think\Controller;
use think\captcha\Captcha;
use think\Session;
use think\Paginate;
use think\view;

 

class BaseController extends \think\Controller{ 
    public function _initialize(){          // 访问权限
        $data=[];
        $home=0;
        $realhome=0;
        $userId=session::get("user");
        $lv=Db::table('think_up')->where('uid',$userId)->find();
        $lv1=Db::table('think_lp')->where('pid',$lv["pid"])->select();
        $module = $this->request->module();
        $controller = $this->request->controller();
        $action = $this->request->action();
        dump($module);
        dump($controller);
        dump($action);
        for($i=0;$i<count($lv1);$i++){  
            array_push($data,Db::table('think_limit')->field("id")->where('id',"=",$lv1[$i]["lid"])->find());
        }
        for($i=0;$i<count($data);$i++){
            $judge=$data[$i]["id"];
            if($judge==3){
                $home = 1;
            }       
            if($judge==5){
               $realhome = 1;
            }   
        }   
        if($realhome==0){
            $this->error("权限不足");
        }
        //echo $home;
       return ;

    }

}