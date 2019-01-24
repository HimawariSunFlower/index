<?php

/**
 * @Author 张超.
 * @Copyright http://www.zhangchao.name
 * @Email 416716328@qq.com
 * @DateTime 2018/5/20 14:36
 * @Desc
 */

namespace app\index\controller;
use think\Db;
use app\index\model\Dlwz;
use think\model;
use think\Request;
use think\Controller;
use think\captcha\Captcha;
use think\Session;
use think\Paginate;

 

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
                if(!captcha_check($user["captcha"])){
                    return json(['status'=>0, 'info'=>'验证码错误']);
                }else{
                    if($user["username"]&&$user["password"]){
                        $judge =Db::table('think_dlwz')->where('username',$user["username"])->find();
                            if($judge){
                                if($user["username"]==$judge["username"]){
                                    if($user["password"]==$judge["password"]){
                                        //echo $jump;
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
    public function home()
    {
        if(session::get("id")){
           // session("id",null);

            $inf=Db::table('think_dlwz')
            ->where('id', '>', 0)
            ->paginate(10);
            $this->assign('name',$inf);
            //return dump($inf);
            return $this->fetch();
        }else{
            echo "重新登陆";
            return;
        }
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
                    $judge =Db::table('think_dlwz')->where('username',$user["username"])->find();
                        if($judge){
                            return json(['status'=>0, 'info'=>'该用户名已存在']);
                        }else{
                            if($user["nickname"]==$judge["nickname"]){
                                return json(['status'=>0, 'info'=>'昵称已占用']);
                            }else{
                                Db::table('think_dlwz')->insert($user);
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
                    $judge =Db::table('think_dlwz')->where('id',$user["id"])->find();
                    if($judge){
                        Db::table('think_dlwz')->where('id',$user["id"])->delete();
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

        $db1=Db::table('think_dlwz')->where('id',$info["id"])->find();
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
                    $judge =Db::table('think_dlwz')->where('id',$user["id"])->find();
                        if($judge){
                                Db::table('think_dlwz')->where('id',$user["id"])->update(['username'=>$user["username"]]);
                                Db::table('think_dlwz')->where('id',$user["id"])->update(['nickname'=>$user["nickname"]]);
                                Db::table('think_dlwz')->where('id',$user["id"])->update(['password'=>$user["password"]]);
                            
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
            $inf=Db::table('think_dlwz')->where('username',$info["username"])->find();
            return json(["status"=>1,'info'=>$inf]);
        }else{
            return json(['status'=>0, 'info'=>'请正确输入']);
        }
        return json(['status'=>0, 'info'=>'传参错误']);
    }

    public function imgup(Request $request){
        $info=$request->param();

        $db1=Db::table('think_dlwz')->where('id',$info["id"])->find();
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
        //dump($a);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $info->getSaveName();
                Db::table('think_dlwz')->where('id',$a)->update(['img'=>$info->getSaveName()]);
                return json(["status"=>1,'info'=>"成功"]);

            }else{
              
            
            }
        }
    }

    


}                       