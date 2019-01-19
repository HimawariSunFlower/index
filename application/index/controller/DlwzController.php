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
 

class DlwzController extends \think\Controller
{
    //登陆相关 （所有信息都按规则填写的情况下
    public function index(Request $request)
    {
        //return dump($request->param("nickname")); 
        //$jump=$this->home();
        if($request->isAjax())
        {
            if($request->param()){
                $user=$request->param();
                if(!captcha_check($user["captcha"])){
                    return "验证码错误";
                    }else{
                    if($user["username"]&&$user["nickname"]&&$user["password"]){
                        $judge =Db::table('think_dlwz')->where('username',$user["username"])->find();
                            if($judge){
                                if($user["username"]==$judge["username"]){
                                    if($user["password"]==$judge["password"]){
                                        //echo $jump;
                                        return 1;
                                    }else{
                                        return "密码错误";
                                    }
                                 }else{
                                     return "用户名错误";
                                 }
                            }else{
                                return"用户不存在";
                            }
                    }else{
                        return "正确输入用户名，昵称，密码"; 
                    }
                }
                }else{
                return "检查网络";
            }
        }
        return "ajax error";

    }
    public function echos()
    {
        return view();
    }
    public function home()
    {
        $inf=Db::table('think_dlwz')
        ->where('id', '>', 0)
        ->order('create_time')
        ->limit(10)
        ->select();
        $this->assign('name',$inf);
        //return dump($inf);
        return $this->fetch();
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
                            return "该用户名已存在";
                        }else{
                            if($user["nickname"]==$judge["nickname"]){
                                return "该昵称已被使用";
                            }else{
                                Db::table('think_dlwz')->insert($user);
                                return "用户名创建成功";
                            }
                        }
                }else{
                    return "正确输入用户名，昵称，密码"; 
                }
            }else{
                return "{status:0,info:'aaaaaa'}";
            }
        }else{
            return view("add");
        }

        
    }
    public function erase()
    {
        return view();
    }
    public function eraseinf(Request $request)
    {
        if($request->isAjax()){
            if ($request->param()){
                $user=$request->param();
                if($user["id"]){
                    $judge =Db::table('think_dlwz')->where('id',$user["id"])->find();
                    if($judge){
                        Db::table('think_dlwz')->where('id',$user["id"])->delete();
                        return "deleted";
                    }else{
                        return "无此id";
                    }
                }else{
                    return "正确输入id"; 
                }
            }else{
                return "检查网络";
            }
        }
        return "ajax error";

    }
    public function revise()
    {
        return view();

    }
    public function reviseinf(Request $request)
    {
        if ($request->param()){
            $user=$request->param();
            if($user["username"]||$user["nickname"]||$user["password"]){
                $judge =Db::table('think_dlwz')->where('id',$user["id"])->find();
                    if($judge){
                        if($user["username"]!=="nochange"){
                            Db::table('think_dlwz')->where('id',$user["id"])->update(['username'=>$user["username"]]);
                        }
                        if($user["nickname"]!=="nochange"){
                            Db::table('think_dlwz')->where('id',$user["id"])->update(['nickname'=>$user["nickname"]]);
                        }
                        if($user["password"]!=="nochange"){
                            Db::table('think_dlwz')->where('id',$user["id"])->update(['password'=>$user["password"]]);
                        }
                        return "修改完成";
                    }else{
                        return "无此id";
                    }
            }else{
                return "正确输入用户名、昵称、密码"; 
            }
        }else{
            return "检查网络";
        }
        return;
    }



}                       