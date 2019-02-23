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

 

class DlwzController extends \app\index\controller\BaseController
{
    //登陆相关 （所有信息都按规则填写的情况下
    public function index(Request $request)          //检测登陆信息
    {
        
        //return dump($request->param("nickname")); 
        //$jump=$this->home();
        if($request->isAjax())
        {
            $session=$request->header()["cookie"];
            $sessionId=substr($session,10);
            Session::set("id",$sessionId);
            if($request->param()){
                $user=$request->param();
                //if(!captcha_check($user["captcha"])){     验证码好烦啊
                    if(0){
                    return json(['status'=>0, 'info'=>'验证码错误']);
                }else{
                    if($user["username"]&&$user["password"]){
                        $judge =Db::table('think_user')->where('username',$user["username"])->find();
                            if($judge){
                                if($user["username"]==$judge["username"]){
                                    if($user["password"]==$judge["password"]){
                                        //echo $jump;
                                        Session::set("user",$judge["id"]);
                                        return json(['status'=>1, 'info'=>$judge["id"]]);
                                    }else{
                                        return json(['status'=>0, 'info'=>'密码错误']);
                                    }
                                    }else{
                                        return json(['status'=>0, 'info'=>'用户名错误']);
                                    }
                            }else{
                                return json(['status'=>0, 'info'=>'用户名不存在']);
                            }
                    }else{
                        return json(['status'=>0, 'info'=>'请先输入再提交']);
                    }
                }
                }else{
                return json(['status'=>0, 'info'=>'传参错误']);
                }
            }
        return;

    }
    public function echos()                    //登陆界面
    {

        return view();
    }
    public function msg(){                       //侧边栏
        if(session::get("id")){
            $info=session::get("user");
            $msg=[];
            $view = new View();
            $top=[];
            $judge =Db::table('think_user')->where('id',$info)->find();
            $persona=Db::table('think_up')->where('uid',$judge["id"])->find();
            $limit=Db::table('think_lp')->where('pid',$persona["pid"])->select();
           // dump($limit);
            
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
    public function realhome(){

        $view = new view();
        $msg=$this->msg();
        $getHeader=$this->getHeader();
        $view->assign('header',$getHeader);
        $view->assign('msg',$msg);   
        return $view->fetch();

    }
    public function home()                  //首页(用户信息)
    {    
        $view =new view();
        $msg=$this->msg();
        $getHeader=$this->getHeader();
        $userPersona=$this->userPersona();
        $view->assign('msg',$msg);
        $view->assign('header',$getHeader);
        $view->assign('persona5',$userPersona);
        $inf=Db::table('think_user')
        ->where('id', '>', 0)
        ->paginate(10);
        $page=$inf->render();
        $view->assign('name',$inf);
        $view->assign('page',$page);
        return $view->fetch();

    
    }
    public function logout(){                 //注销
        session("id",null);
        
        return 	view("echos");
    }
    public function add()            //添加用户界面
    {
        return view();
    }
    public function addinf(Request $request)    //添加用户方法
    {
        if($request->isAjax()){
            if ($request->param()){
                $user=$request->param();
                if($user["username"]&&$user["nickname"]&&$user["password"]){
                    $judge =Db::table('think_user')->where('username',$user["username"])->find();
                        if($judge){
                            return json(['status'=>0, 'info'=>'该用户名已存在']);
                        }else{
                            if($user["nickname"]==$judge["nickname"]){
                                return json(['status'=>0, 'info'=>'昵称已占用']);
                            }else{
                                Db::table('think_user')->insert($user);
                                return json(['status'=>0, 'info'=>'创建成功']);
                            }
                        }
                }else{
                    return json(['status'=>0, 'info'=>'请正确输入']);
                }
            }else{
                return json(['status'=>0, 'info'=>'传参错误']);
            }
        }else{
            return view("add");
        }

        
    }

    public function eraseinf(Request $request)   //删除方法
    {
        if($request->isAjax()){
            if ($request->param()){
                $user=$request->param();
                if($user["id"]){
                    $judge =Db::table('think_user')->where('id',$user["id"])->find();
                    if($judge){
                        Db::table('think_user')->where('id',$user["id"])->delete();
                        return json(['status'=>0, 'info'=>'删除成功']);
                    }else{
                        return json(['status'=>0, 'info'=>'无此id']);
                    }
                }else{
                    return json(['status'=>0, 'info'=>'正确输入id']);
                }
            }else{
                return json(['status'=>0, 'info'=>'传参错误']);
            }
        }else{
            return view("erase");

        }
        

    }
    public function revise(Request $request)       //修改界面
    {
        $view =new view();
        $msg=$this->msg();
        $view->assign('msg',$msg);
        $info=$request->param();

        $db1=Db::table('think_user')->where('id',$info["id"])->find();
        //dump($db1);
        $view->assign([
            'InfoId'=>$db1["id"],
            'InfoNn'=>$db1["nickname"],
            'InfoUn'=>$db1["username"],
            'InfoPw'=>$db1["password"],

        ]);
        return $view->fetch();

    }
    public function reviseinf(Request $request)           //修改用户信息界面
    {
       
        //return $qqq;
        if($request->isAjax()){ 
            //return json(['status'=>0, 'info'=>' 1']);
            if ($request->param()){
                $user=$request->param();
               // return json(['status'=>0, 'info'=>' 2']);
                if($user["username"]||$user["nickname"]||$user["password"]){
                    $judge =Db::table('think_user')->where('id',$user["id"])->find();
                        if($judge){
                                Db::table('think_user')->where('id',$user["id"])->update(['username'=>$user["username"]]);
                                Db::table('think_user')->where('id',$user["id"])->update(['nickname'=>$user["nickname"]]);
                                Db::table('think_user')->where('id',$user["id"])->update(['password'=>$user["password"]]);
                            
                                return json(['status'=>0, 'info'=>'修改完成']);
                                }else{
                                    return json(['status'=>0, 'info'=>'无此id']);
                                }
                        }else{
                            return json(['status'=>0, 'info'=>'请正确输入']);
                        }
                }else{
                    return json(['status'=>0, 'info'=>'传参错误']);
                }
        }else{
            return ;
        }
        
    }
    
    public function queryinf(Request $re){                  //更具id查询用户
        $info=$re->param();
        if($info["username"]){
            $inf=Db::table('think_user')->where('username',$info["username"])->find();
            return json(["status"=>1,'info'=>$inf]);
        }else{
            return json(['status'=>0, 'info'=>'请正确输入']);
        }
        return json(['status'=>0, 'info'=>'传参错误']);
    }

    public function imgup(Request $request){          //上传头像界面 根据id
        $info=$request->param();

        //$db1=Db::table('think_user')->where('id',$info["id"])->find();
        //dump($db1);
        $this->assign([
            'InfoId'=>$info["id"],
        ]);
        return $this->fetch();
        return view();
 
    }



    public function upload(){                //上传图像逻辑
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        $inf = request()->param();
        $a="";
        for($i=0;$i<count($inf);$i++){
            $a.=$inf[$i];
        }
        //dump($inf);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $info->getSaveName();
                Db::table('think_user')->where('id',$a)->update(['img'=>$info->getSaveName()]);
                return json(["status"=>1,'info'=>"成功"]);

            }else{
              
            
            }
        }
    }

    public function userPersona(){                 //得到角色表信息
        if(session::get("id")){
            $view = new View();
            $persona5 = Db::table('think_persona')->where('id',">",0)->select();
            return $persona5;
        }else{
            echo "重新登陆";
            return;
        }
    }

    
    public function permitAdd(Request $re){             //用户选择角色界面
        $view = new view();
        $data=$re->param();
        $view->assign("userid",$data["id"]);

        return $view->fetch();
    }

    
    public function permitAddinf(Request $re){             //用户选择角色方法
        if($re->isAjax()){
            $userid=$re->param()["id"];
            //dump($re->param()["id"]);
            $persona5 = Db::table('think_persona')->where('id',">",0)->select();
            return json(["status"=>1,'info'=>$persona5]);

        }
    }
    public function permitAddfuc(Request $re){             //up表新建关系
        if($re->isAjax()){
            //dump($re->param());
            $uid=$re->param()["uid"];
            $pid=$re->param()["pid"];
            $user=["uid"=>$uid,"pid"=>$pid];
            Db::table('think_up')->where('uid',"=",$uid)->delete();
            Db::table('think_user')->where('id',"=",$uid)->update(['lv'=>$pid]);;
            Db::table('think_up')->insert($user);
            return json(["status"=>1,'info'=>"角色选择完成"]);

        }

    }


}                       