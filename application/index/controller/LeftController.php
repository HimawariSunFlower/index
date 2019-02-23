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
    public function msg(){                     //侧边栏+导航栏
        if(session::get("id")){
            $info=session::get("user");
            $msg=[];
            $view = new View();
            $top=[];
            $judge =Db::table('think_user')->where('id',$info)->find();
            $persona=Db::table('think_up')->where('uid',$judge["id"])->find();
            $limit=Db::table('think_lp')->where('pid',$persona["pid"])->select();
            //dump($limit);
            
            for($i=0;$i<count($limit);$i++){
                //$top->append(array());
                array_push($top,Db::table('think_limit')->where('id',"=",$limit[$i]["lid"])->find());
                
            }
            //dump($top);
            $msg=$top; 
            return $msg;
        }else{
            echo "重新登陆";
            return;
        }
    }
    public function getHeader(){               //导航栏头像 id获取并返回
        $info=session::get("user");
        $view = new View();
        $userinf =Db::table('think_user')->where('id',$info)->find();
        return $userinf;

        
    }

    public function permit(){                     //权限界面
        $landingcheck=$this->landingcheck();
        if($landingcheck["permit"]==1){
            $view = new view();
            $msg=$this->msg();
            $getHeader=$this->getHeader();
            $view->assign('header',$getHeader);
            $view->assign('msg',$msg);
            return $view->fetch();
        }

    }
    
    public function permitTable(Request $re){          //权限的表格
        if($re->isAjax()){
            $infomation=Db::table('think_limit')->where('id',">",0)->select();                            
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
    public function add(){                   //增加界面
        return view();   
    }
    public function addinf(Request $re){               //增加方法
        if($re->isAjax()){
            if ($re->param()){
                $data=$re->param();
                //dump($data);
                if($data["name"]&&$data["url"]&&$data["lid"]){ //    判断条件多重if,这里太简陋了
                    Db::name("limit")->data($data)->insert();  //                
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
    public function update(Request $re){                   //编辑 更新界面
        $view = new view();
        $data=$re->param();
        $limit=Db::table('think_limit')->where('id',$data["id"])->select();
        //dump($limit);
        $view->assign('msg',$limit);

        return $view->fetch();
    }
    public function updateinf(Request $re){           //编辑方法  缺少判断
        if($re->isAjax()){
            if($re->param()){
                $data=$re->param();
                //dump($data["id"]);
                Db::table('think_limit')->where('id',"=",$data["id"])->data($data)->update();   
                return json(["status"=>1,"info"=>"更新成功"]);
            }
        }  

    }
    public function deleteinf(Request $re){                         //删除方法
        if($re->isAjax()){
            if($re->param()){
                $data=$re->param();
                //dump($data["id"]);
                Db::table('think_limit')->where('id',"=",$data["id"])->delete();   
                return json(["status"=>1,"info"=>"删除成功"]);
            }
        }  

    } 
    public function group(){                //权限组界面
        $landingcheck=$this->landingcheck();
        if($landingcheck["group"]==1){
            $view = new view();
            $msg=$this->msg();
            $getHeader=$this->getHeader();
            $view->assign('header',$getHeader);
            $view->assign('msg',$msg);   
            return $view->fetch();
        }
    }
    public function groupinf(Request $re){           //权限组表格
        if($re->isAjax()){
            $infomation=Db::table('think_persona')->where('id',">",0)->select();
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
    public function personaAdd(){                   //添加角色界面
        return view();  
    }
    public function personaAddinf(Request $re){            //添加角色方法
        if($re->isAjax()){
            if ($re->param()){
                $data=$re->param();
                if($data["id"]&&$data["lv"]&&$data["name"]){ 
                    Db::name("persona")->data($data)->insert();  
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
    public function personaUpdate(Request $re){                //角色更新界面
        $view = new view();
        $data=$re->param();
        $limit=Db::table('think_persona')->where('id',$data["id"])->select();
       //dump($limit);
        $view->assign('msg',$limit);

        return $view->fetch();

    }
    public function personaUpdateinf(Request $re){                    //角色更新方法
        if($re->isAjax()){
            if($re->param()){
                $data=$re->param();
                //dump($data["id"]);
                Db::table('think_persona')->where('id',"=",$data["id"])->data($data)->update();   
                return json(["status"=>1,"info"=>"更新成功"]);
            }
        }  
    }
    public function personaDeleteinf(Request $re){                //角色删除方法
        if($re->isAjax()){
            if($re->param()){
                $data=$re->param();
                //dump($data["id"]);
                Db::table('think_persona')->where('id',"=",$data["id"])->delete();   
                return json(["status"=>1,"info"=>"删除成功"]);
            }
        }  

    }
    public function groupP(Request $re){                  //角色与权限连接
        $view = new view();
        $data=$re->param();
        $view->assign("personaId",$data["id"]);

        return $view->fetch();
    }
    public function personaTable(Request $re){        //角色与权限挂钩的表格
        if($re->isAjax()){
            $infomation=Db::table('think_limit')->where('id',">",0)->select();                            
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
    public function persona_limit(Request $re){        //保存角色与权限的关联关系
        if($re->isAjax()){
           $inf=$re->param();
          // dump($inf["info"]);
           //echo $inf["id"];
           if($inf["info"]!="noop"){
                Db::table('think_lp')->where('pid',"=",$inf["id"])->delete();   
                for($i=0;$i<count($inf["info"]);$i++){
                    $lid=$inf["info"][$i]["id"];
                    Db::name("lp")->data(["pid"=>$inf["id"],"lid"=>$lid])->insert();
                }
               //echo $lid;
           }else{
                Db::table('think_lp')->where('pid',"=",$inf["id"])->delete();  
           }
           return json(["status"=>1,"info"=>"权限赋予成功"]);
           
           
        }else{
            echo "no";
            return;
        }
    }

    public function personaCheckbox(Request $re){         //角色与权限的默认勾选栏
        if($re->isAjax()){
            $inf=$re->param();
            $id =$inf["id"];
            //dump($inf["id"]);
            $checked=Db::table('think_lp')->field("lid")->where('pid',"=",$id)->select();
            //dump($checked);
            return json(["status"=>0,"info"=>"默认选中成功","data"=>$checked]);
            
         }else{
             echo "no";
             return;
         }

    }
    public function landingcheck(){              //权限访问限制
        $data=[];
        $permit=0;
        $group=0;
        $userId=session::get("user");
        $lv=Db::table('think_up')->where('uid',$userId)->find();
        $lv1=Db::table('think_lp')->where('pid',$lv["pid"])->select();
        for($i=0;$i<count($lv1);$i++){  
            array_push($data,Db::table('think_limit')->field("id")->where('id',"=",$lv1[$i]["lid"])->find());
        }
        for($i=0;$i<count($data);$i++){
            $judge=$data[$i]["id"];
            if($judge==7){
                $permit = 1;
            }       
            if($judge==8){
                $group = 1;
            }   
        }   
       return ["permit"=>$permit,"group"=>$group];
    }

}

