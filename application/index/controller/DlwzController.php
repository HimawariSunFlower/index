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

 

class DlwzController extends \think\Controller
{
    //登陆相关 （所有信息都按规则填写的情况下
    public function index(Request $request)
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
    public function echos()
    {

        return view();
    }
    public function msg(){
        if(session::get("id")){
            $info=session::get("user");
            $msg=[];
            $view = new View();
            $top=[];
            $judge =Db::table('think_user')->where('id',$info)->find();
            $persona=Db::table('think_up')->where('uid',$judge["uid"])->find();
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
    public function home()
    {
        $view =new view();
        $msg=$this->msg();
        $view->assign('msg',$msg);
        $inf=Db::table('think_user')
        ->where('id', '>', 0)
        ->paginate(10);
        $page=$inf->render();
        $view->assign('name',$inf);
        $view->assign('page',$page);
        return $view->fetch();
    }
    public function logout(){
        session("id",null);
        
        return 	view("echos");
    }
    public function add()
    {
        return view();
    }
    public function addinf(Request $request)
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
    // public function erase(Request $request)
    // {
    //     //dump($request);
    //     $info=$request->param();
    //     $this->assign('InfoId',$info["id"]);
    //     //return dump($inf);
    //     return $this->fetch();

    // }
    public function eraseinf(Request $request)
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
    public function revise(Request $request)
    {
        $info=$request->param();

        $db1=Db::table('think_user')->where('id',$info["id"])->find();
        //dump($db1);
        $this->assign([
            'InfoId'=>$db1["id"],
            'InfoNn'=>$db1["nickname"],
            'InfoUn'=>$db1["username"],
            'InfoPw'=>$db1["password"],

        ]);
        return $this->fetch();

    }
    public function reviseinf(Request $request)
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
    
    public function queryinf(Request $re){
        $info=$re->param();
        if($info["username"]){
            $inf=Db::table('think_user')->where('username',$info["username"])->find();
            return json(["status"=>1,'info'=>$inf]);
        }else{
            return json(['status'=>0, 'info'=>'请正确输入']);
        }
        return json(['status'=>0, 'info'=>'传参错误']);
    }

    public function imgup(Request $request){
        $info=$request->param();

        $db1=Db::table('think_user')->where('id',$info["id"])->find();
        //dump($db1);
        $this->assign([
            'InfoId'=>$db1["id"],


        ]);
        return $this->fetch();
        return view();
 
    }



    public function upload(){
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

    public function permit(Request $re){
        if(session::get("id")){
            $info=session::get("user");
            $msg=[];
            $view = new View();
            $top=[];
            $judge =Db::table('think_user')->where('id',$info)->find();
            $persona=Db::table('think_up')->where('uid',$judge["uid"])->find();
            $limit=Db::table('think_lp')->where('pid',$persona["pid"])->select();
            if(count($limit)==1){
                $top=Db::table('think_limit')->where('lid',"=",$limit[0]["lid"])->select();
            }elseif(count($limit)==2)
            {
                $top=Db::table('think_limit')
                ->whereOR('lid',"=",$limit[0]["lid"])
                ->whereOr('lid',"=",$limit[1]["lid"])->select();

            }elseif(count($limit)==3){
                $top=Db::table('think_limit')                                 
                ->whereOR('lid',"=",$limit[0]["lid"])
                ->whereOr('lid',"=",$limit[1]["lid"])
                ->whereOr('lid',"=",$limit[2]["lid"])->select();
              
            }
            $msg=$top; 
            // dump($msg);
            // return;
            $view->assign('msg',$msg);
       
            $infomation=Db::table('think_limit')->where('id',">",0)->select();
            //dump($infomation);                               
            //return json(["status"=>1,'info'=>$infomation]);
            return json(
                [
                    "code"=>0,
                    "status"=>0,
                    "message"=> "", 
                    "count"=> 100, 
                    "data"=> $infomation,
                ]
            );         
        }
    }

    public function getHeader(Request $re){
        $info=1;
        $msg=[];
        $view = new View();
        $top=[];
        $judge =Db::table('think_user')->where('id',$info)->find();
        $persona=Db::table('think_up')->where('uid',$judge["uid"])->find();
        $limit=Db::table('think_lp')->where('pid',$persona["pid"])->select();
        if(count($limit)==1){
            $top=Db::table('think_limit')->where('lid',"=",$limit[0]["lid"])->select();
        }elseif(count($limit)==2)
        {
            $top=Db::table('think_limit')
            ->whereOR('lid',"=",$limit[0]["lid"])
            ->whereOr('lid',"=",$limit[1]["lid"])->select();

        }elseif(count($limit)==3){
            $top=Db::table('think_limit')                                 
            ->whereOR('lid',"=",$limit[0]["lid"])
            ->whereOr('lid',"=",$limit[1]["lid"])
            ->whereOr('lid',"=",$limit[2]["lid"])->select();
          
        }
        $msg=$top; 
        // dump($msg);
        // return;
        $view->assign('msg',$msg);
        if($re->isAjax()){
            
            return view("getheader");
            //return json(["code"=>0,"info"=>$info]);

        }else{
            return view("getheader");
        }
        
    }
    

    


}                       