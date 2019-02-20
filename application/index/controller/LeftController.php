<?php
namespace app\index\controller;
use think\Db;
use app\index\model\Dlwz;
use think\model;
use think\Request;
use think\Controller;
use think\captcha\Captcha;
use think\Session;
use think\Paginate;
use think\view;

 

class LeftController extends \think\Controller
{
    public function msg(){
        if(session::get("id")){
            $info=session::get("user");
            $msg=[];
            $view = new View();
            $top=[];
            $judge =Db::table('think_user')->where('id',$info)->find();
            $persona=Db::table('think_up')->where('uid',$judge["uid"])->find();
            $limit=Db::table('think_lp')->where('pid',$persona["pid"])->select();
            //dump($limit);
            
            for($i=0;$i<count($limit);$i++){
                //$top->append(array());
                array_push($top,Db::table('think_limit')->where('id',"=",$limit[$i]["lid"])->find());
                
            }
            //dump($top);
            $msg=$top; 
            return $msg;
            // return;
            // if(count($limit)==1){
            //     $top=Db::table('think_limit')->where('id',"=",$limit[0]["lid"])->select();
            // }elseif(count($limit)==2)
            // {
            //     $top=Db::table('think_limit')
            //     ->whereOR('lid',"=",$limit[0]["id"])
            //     ->whereOr('lid',"=",$limit[1]["id"])->select();

            // }elseif(count($limit)==3){
            //     $top=Db::table('think_limit')                                 
            //     ->whereOR('lid',"=",$limit[0]["lid"])
            //     ->whereOr('lid',"=",$limit[1]["lid"])
            //     ->whereOr('lid',"=",$limit[2]["lid"])->select();
              
            // }
            
            // $msg=$top; 
            // return $msg;
        }else{
            echo "重新登陆";
            return;
        }
    }

    public function permit(){
        
        $view = new view();
        $msg=$this->msg();
        $view->assign('msg',$msg);
       
        return $view->fetch();

    }
    
    public function permitTable(Request $re){
        if($re->isAjax()){
            $infomation=Db::table('think_limit')->where('id',">",0)->select();
            //dump($infomation);                               
            return json(
                [
                    "code"=>0,
                    "status"=>0,
                    "message"=> "", 
                    "count"=> 100, 
                    "data"=> $infomation,
                ]
            );         
        }else{
            echo "no";
            return;
        }

    }

    public function add(){
        return view();
        
    }

    public function addinf(Request $re){
        if($re->isAjax()){
            if ($re->param()){
                $data=$re->param();
                //dump($data);
                if($data["name"]&&$data["url"]&&$data["lid"]){ //     parent又可能=0不能&&
                    Db::name("limit")->data($data)->insert();  //                lp表怎么设置   是在这里设置初始值还是说在角色页面分配
                  //  $inf=Db::table('think_limit')->where('url',$data["url"])->find();
                    return json(["status"=>1,"info"=>"创建成功"]);
                }else{
                    return json(["status"=>0,"info"=>"请正确输入信息"]);
                }
            }else{
                return json(['status'=>0, 'info'=>'传参错误']);
            }
        }else{
            return view("add");
        }
    }

    public function update(Request $re){
        $view = new view();
        $data=$re->param();
        $limit=Db::table('think_limit')->where('id',$data["id"])->select();
        //dump($limit);
        $view->assign('msg',$limit);

        return $view->fetch();
    }
    public function updateinf(Request $re){
        if($re->isAjax()){
            if($re->param()){
                $data=$re->param();
                //dump($data["id"]);
                Db::table('think_limit')->where('id',"=",$data["id"])->data($data)->update();   
                return json(["status"=>1,"info"=>"更新成功"]);
            }
        }  

    }
    public function deleteinf(Request $re){
        if($re->isAjax()){
            if($re->param()){
                $data=$re->param();
                //dump($data["id"]);
                Db::table('think_limit')->where('id',"=",$data["id"])->delete();   
                return json(["status"=>1,"info"=>"删除成功"]);
            }
        }  

    }
    public function group(){
        return view();
    }
}

